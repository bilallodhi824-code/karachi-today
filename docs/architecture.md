# KARACHI TODAY v4.0 - Enterprise System Architecture & Specifications

## 1. Overview
KARACHI TODAY is a modern, production-ready digital news organization platform engineered for real-time publishing, high scalability, automated content ingestion, AI-driven content processing, and enterprise-grade editorial workflow.

This document specifies the technical architecture, technology decisions, component boundaries, and security model.

---

## 2. Technology Stack & Decoupled Paradigm

```
┌─────────────────────────────────────────────────────────┐
│              Next.js 16 (App Router / RSC)              │
│       React 19.2 | Tailwind CSS 4 | Lucide Icons        │
└────────────────────────────┬────────────────────────────┘
                             │ REST API (HTTPS / JSON)
┌────────────────────────────▼────────────────────────────┐
│                  Laravel 13 (PHP 8.5)                   │
│         REST Controllers | Eloquent ORM | Sanctum       │
└──────────────┬───────────────────────────┬──────────────┘
               │                           │
┌──────────────▼──────────────┐  ┌─────────▼──────────────┐
│       MySQL 8.4 LTS         │  │     Redis 7 Queue/Cache │
│ Strict Foreign Keys & Index │  │ Jobs, Schedules & Lock │
└─────────────────────────────┘  └────────────────────────┘
```

### Key Rules:
1. **Separation of Layers**: Frontend (Next.js) renders views using Server Components and calls Laravel REST APIs. Frontend never connects directly to MySQL.
2. **Strict Data Integrity**: All write operations occur through Laravel validation, database transactions, foreign key constraints, and audit logging.
3. **Cache & Queue Offloading**: Heavy background jobs (RSS polling, AI summarization, deduplication) execute asynchronously via Redis queues and Laravel Horizon.

---

## 3. Detailed Component Map

### Frontend (`/frontend`)
- **App Router (`src/app/`)**:
  - `(public)`: SSR/ISR routes for Homepage, Categories (`/karachi`, `/pakistan`, `/world`, `/business`, `/opinion`, `/sports`, `/culture`, `/live`), Article details (`/article/[slug]`), and Search (`/search`).
  - `admin/`: CMS Portal for editorial review, source management, slotting, and manual publishing.
- **Design System (`src/components/`)**:
  - Recreates visual identity from reference screenshot:
    - Breaking News Ticker (Crimson background, live feed).
    - Navy Header & Logo with crescent star design.
    - Category Nav Bar with active tab indicators.
    - Hero & 3-Column Supporting Card Grid.
    - Top Headlines Numbered Sidebar (1-5).
    - Banner Ad container.

### Backend (`/backend`)
- **Core API (`app/Http/Controllers/Api/`)**: REST V1 controllers for Public and Editorial consumption.
- **Ingestion Driver Engine (`app/Services/Ingestion/`)**: Connects to RSS feeds, wire services, and news APIs.
- **AI Processing Pipeline (`app/Services/AI/`)**: Integrates LLMs for categorization, 2-sentence executive summary generation, tag extraction, confidence scoring, and breaking news detection.
- **Deduplication Engine (`app/Services/Deduplication/`)**: MinHash / SimHash algorithm preventing duplicate stories from multiple wire feeds.
- **Homepage Engine (`app/Services/Homepage/`)**: Priority slotting engine supporting dynamic time-based content shifts (morning, afternoon, evening, night).

---

## 4. Security Architecture

1. **API Security**: CORS restricted strictly to Next.js origin, Sanctum stateful/token authentication, API rate limiting (`60 req/min` for public, strict limits for auth endpoints).
2. **XSS & Injection Protection**: HTML Purifier sanitization on incoming RSS content, PDO parameterized queries via Eloquent ORM.
3. **Role-Based Access Control (RBAC)**: Admin, Senior Editor, Journalist, Automator, Guest reader roles enforced at API middleware level.
4. **Audit Logging**: Every editorial decision, automated publication, and setting override logged in `audit_logs`.

---

## 5. Deployment & Scalability Architecture

1. **Development Environment**: Local Docker Compose / XAMPP / Redis stack.
2. **Production Environment**:
   - Next.js hosted on Vercel / Node cluster with Edge ISR.
   - Laravel API hosted on PHP 8.5 / FPM with Redis queue workers.
   - MySQL 8.4 LTS with read replicas and automated hourly backups.
   - Cloudflare CDN for static asset delivery and edge caching.
