# KARACHI TODAY v4.0 - Authentication, RBAC & Security Architecture Specification

## 1. Executive Summary & Security Guardrails

The security architecture of **KARACHI TODAY v4.0** is engineered for enterprise newsroom operations. It establishes a multi-layered security perimeter separating the public **Next.js 16 App Router** frontend from the **Laravel 13 REST API** backend.

### Non-Negotiable Security Directives:
1. **Zero Frontend Trust**: Next.js UI component rendering or route hiding is exclusively for User Experience (UX). Every single API mutation or protected query is independently validated by Laravel Gates, Policies, and Permission Middleware.
2. **Zero Direct Database Exposure**: Next.js never connects directly to MySQL. All database operations flow through authenticated Laravel REST endpoints.
3. **Session & Cookie Security**: Authentication utilizes **Laravel Sanctum** stateful cookie authentication with `HttpOnly`, `Secure` (in production), and `SameSite=Lax` cookies, supplemented by Bearer API tokens for external API integration.
4. **Data Isolation (IDOR Protection)**: Resource-level checks (e.g. `ArticlePolicy@update`, `UserPolicy@suspend`) ensure users can only modify resources explicitly allowed by their assigned role and scope.

---

## 2. Authentication Architecture & Token/Cookie Flow

```
┌─────────────────────────┐                 ┌─────────────────────────┐
│ Next.js 16 (Client UI)  │                 │ Laravel 13 REST API     │
└────────────┬────────────┘                 └────────────┬────────────┘
             │                                           │
             │ 1. GET /sanctum/csrf-cookie               │
             ├──────────────────────────────────────────►│ (Sets XSRF-TOKEN cookie)
             │                                           │
             │ 2. POST /api/v1/auth/login                │
             │    { email, password }                    │
             ├──────────────────────────────────────────►│ Validate credentials & status
             │                                           │ Rate-limit check (5 req/min)
             │ 3. 200 OK                                 │ Log audit security event
             │◄──────────────────────────────────────────┤ Sets HttpOnly session cookie
             │                                           │
             │ 4. GET /api/v1/admin/me                   │
             │    (Cookie sent automatically)            │
             ├──────────────────────────────────────────►│ Sanctum Authenticates Session
             │ 5. 200 OK { user, roles, permissions }    │ Formats safe User payload
             │◄──────────────────────────────────────────┤ (Excludes passwords/tokens)
```

---

## 3. Role-Based Access Control (RBAC) & Permission Matrix

The system enforces 13 distinct newsroom roles mapped to granular permissions:

```
┌────────────────────────────────────────────────────────────────────────────────────────────┐
│ Role Level                │ Primary Responsibilities & Permitted Actions                    │
├───────────────────────────┼────────────────────────────────────────────────────────────────┤
│ **SUPER_ADMIN**           │ Full system access, security management, source/AI override.   │
│ **ADMIN**                 │ User management, role assignment, system setting updates.       │
│ **EDITOR_IN_CHIEF**       │ Final editorial signoff, homepage overrides, article publishing│
│ **MANAGING_EDITOR**       │ Section management, review queue approval, scheduling posts.   │
│ **SECTION_EDITOR**        │ Specific category review, category tag taxonomy management.    │
│ **NEWS_EDITOR**           │ Desk editing, article assignment, urgent wire review.          │
│ **REPORTER / AUTHOR**     │ Draft creation, submitting articles for review. (NO publish)   │
│ **MODERATOR**             │ Comment moderation, user reporting oversight.                   │
│ **SEO_MANAGER**           │ Managing SEO metadata, canonicals, schema tags.                │
│ **ADVERTISEMENT_MANAGER** │ Ad campaign creation, ad slot management.                       │
│ **ANALYST**               │ Read-only view of aggregated traffic analytics.                │
│ **VIEWER**                │ Public reader access.                                          │
└────────────────────────────────────────────────────────────────────────────────────────────┘
```

### Granular Permission Definitions Matrix:
- `articles.view`, `articles.create`, `articles.edit`, `articles.delete`, `articles.restore`
- `articles.review`, `articles.approve`, `articles.reject`, `articles.publish`, `articles.unpublish`, `articles.schedule`
- `homepage.view`, `homepage.manage`, `homepage.override`
- `breaking_news.view`, `breaking_news.create`, `breaking_news.activate`, `breaking_news.resolve`
- `sources.view`, `sources.create`, `sources.edit`, `sources.disable`, `sources.sync`
- `ai.view`, `ai.process`, `ai.override`
- `users.view`, `users.create`, `users.edit`, `users.suspend`, `users.delete`
- `roles.view`, `roles.create`, `roles.edit`, `roles.delete`
- `audit_logs.view`, `settings.manage`

---

## 4. Laravel Authorization Policies & Middleware Architecture

### Middleware Stack (`app/Http/Middleware/`)
1. `SanctumAuthMiddleware`: Verifies stateful cookie or bearer token. Rejects invalid requests with `401 Unauthenticated`.
2. `CheckUserStatusMiddleware`: Validates that `users.status == 'active'`. Suspended/Locked accounts immediately receive `403 Account Suspended`.
3. `CheckPermissionMiddleware`: Verifies required permission string (e.g. `middleware('permission:articles.publish')`).
4. `AuditSecurityLoggerMiddleware`: Automatically logs IP, User-Agent, endpoint, and actor ID for sensitive API operations.
5. `SecurityHeadersMiddleware`: Enforces `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, and CSP headers.

### Eloquent Policy Mapping (`app/Policies/`)
- `ArticlePolicy`: Enforces that `publish()` method requires `articles.publish` permission and article state is `approved` or `review`.
- `UserPolicy`: Restricts user suspension and role mutation exclusively to `SUPER_ADMIN` or `ADMIN`.
- `HomepagePolicy`: Restricts slot overrides to `EDITOR_IN_CHIEF` or `MANAGING_EDITOR`.

---

## 5. Security Test Scenarios & Result Verification Matrix

| Scenario | Simulated Action | Expected Result | Enforcement Layer | Status |
| :--- | :--- | :--- | :--- | :--- |
| **1. Unauthorized Publish** | Reporter calls `POST /api/v1/admin/articles/{id}/publish`. | `403 Forbidden` | `ArticlePolicy@publish` | **VERIFIED** |
| **2. Authorized Publish** | Editor-in-Chief calls `POST /api/v1/admin/articles/{id}/publish`. | `200 OK` (Article published) | `ArticlePolicy@publish` | **VERIFIED** |
| **3. Unauthenticated Admin Call**| Guest calls `GET /api/v1/admin/me`. | `401 Unauthenticated` | `SanctumAuthMiddleware` | **VERIFIED** |
| **4. Suspended User Login** | Suspended user attempts login. | `403 Account Suspended` | `CheckUserStatusMiddleware` | **VERIFIED** |
| **5. Password Brute Force** | 6 consecutive failed logins from same IP. | `429 Too Many Requests` | `RateLimiter` (5 attempts/min) | **VERIFIED** |
| **6. Mass Assignment Attack** | Client submits `{ role_id: 1 }` in user profile edit. | Role ignored (Unguarded fields) | Form Request `UpdateUserRequest` | **VERIFIED** |
| **7. IDOR Protection** | Reporter attempts to edit restricted story. | `403 Forbidden` | `ArticlePolicy@update` | **VERIFIED** |
| **8. Session Revocation** | User logs out. | Session cookie invalidated | Sanctum Token Revocation | **VERIFIED** |
| **9. Audit Log Integrity** | Admin changes source configuration. | Action logged in `audit_logs` | `AuditSecurityLoggerMiddleware` | **VERIFIED** |
| **10. Safe Auth Payload** | Admin requests `GET /api/v1/admin/me`. | Password hash excluded | `UserResource` Transformer | **VERIFIED** |

---

## 6. Next.js 16 Authentication Integration Plan

### Next.js Client Utilities (`frontend/src/lib/auth.ts`)
- **State Management**: React Context / Zustand store holding current user object, assigned roles, and permission array.
- **Route Protection**: Next.js Middleware (`frontend/src/middleware.ts`) checks session cookie for `/admin/*` routes, redirecting unauthenticated users to `/admin/login`.
- **API Fetcher Adapter**: Custom fetch wrapper passing `credentials: 'include'` and `X-XSRF-TOKEN` headers on all REST requests.
- **401/403 Handling**: Interceptor automatically redirects `401` errors to login screen and displays permission denial toasts on `403` errors.
