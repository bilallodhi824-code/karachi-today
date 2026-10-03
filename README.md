# KARACHI TODAY v4.0

> **Karachi Today** is a professional Pakistani digital news platform and newsroom automation engine built with Next.js 16, React 19, TypeScript, Tailwind CSS 4, Laravel 13 (PHP 8.5), and MySQL 8.4 LTS.

---

## Technical Stack Overview

- **Frontend**: Next.js 16 (App Router), React 19.2, TypeScript, Tailwind CSS 4
- **Backend**: Laravel 13, PHP 8.5 (REST API V1 Architecture)
- **Database**: MySQL 8.4 LTS (InnoDB, Spatial Indexes, FullText Search)
- **Cache & Queue**: Redis 7.2, Laravel Horizon (Asynchronous Queue Workers)
- **Design System**: Deep Navy `#0A192F`, Crimson Accent `#B91C1C`, Charcoal Text `#1E293B`

---

## Quick Start & Local Setup

### 1. Prerequisites
- Node.js `^20.x` or `^22.x`
- PHP `^8.2` or `^8.3` / `^8.5`
- Composer `^2.x`
- MySQL `^8.4`
- Redis Server `^7.x`

### 2. Frontend Setup
```bash
# Install NPM dependencies
npm install

# Run Next.js Development Server
npm run dev

# Production Build Verification
npm run build
```

### 3. Backend Setup (Laravel Monorepo API)
```bash
# Navigate to backend directory or run Artisan commands
composer install

# Environment Configuration
cp .env.example .env
php artisan key:generate

# Execute Database Migrations
php artisan migrate --seed

# Start Laravel Queue Worker
php artisan horizon
```

---

## Architecture Documentation Index

| System Domain | Specification File |
| :--- | :--- |
| **System Architecture** | [docs/architecture.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/architecture.md) |
| **Database Schema & ERD** | [docs/database.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/database.md) |
| **Backend REST API** | [docs/backend-api-architecture.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/backend-api-architecture.md) |
| **Authentication & RBAC** | [docs/auth-rbac-security.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/auth-rbac-security.md) |
| **News CMS & Editorial Workflow** | [docs/cms-editorial-workflow.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/cms-editorial-workflow.md) |
| **Automatic News Ingestion** | [docs/news-ingestion-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/news-ingestion-engine.md) |
| **AI Processing Engine** | [docs/ai-news-processing.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/ai-news-processing.md) |
| **Automatic Publishing Engine** | [docs/automatic-publishing-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/automatic-publishing-engine.md) |
| **Breaking News Ticker** | [docs/breaking-news-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/breaking-news-engine.md) |
| **Dynamic Homepage Rotation** | [docs/homepage-rotation-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/homepage-rotation-engine.md) |
| **Search & Topic Discovery** | [docs/search-discovery-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/search-discovery-engine.md) |
| **Web Push & Alerts** | [docs/notification-webpush-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/notification-webpush-engine.md) |
| **Live News & Live Blogs** | [docs/live-news-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/live-news-engine.md) |
| **Media & Video Engine** | [docs/media-rich-content-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/media-rich-content-engine.md) |
| **Admin Control Center** | [docs/admin-cms-control-center.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/admin-cms-control-center.md) |
| **News SEO & Google News** | [docs/seo-google-news-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/seo-google-news-engine.md) |
| **Performance & Scalability** | [docs/performance-scalability-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/performance-scalability-engine.md) |
| **Enterprise Security** | [docs/enterprise-security-privacy.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/enterprise-security-privacy.md) |
| **Newsroom Analytics** | [docs/newsroom-analytics-intelligence.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/newsroom-analytics-intelligence.md) |
| **Visual Design System** | [docs/visual-design-system.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/visual-design-system.md) |
| **Production Operations & DevOps** | [docs/production-devops-operations-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/production-devops-operations-engine.md) |
| **QA & Quality Gate** | [docs/qa-testing-quality-gate.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/qa-testing-quality-gate.md) |
| **Personalization & Recommendations** | [docs/personalization-recommendation-engine.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/personalization-recommendation-engine.md) |
| **Monetization & Revenue Ops** | [docs/monetization-revenue-operations.md](file:///c:/Users/Dw/Desktop/karachi%20today/docs/monetization-revenue-operations.md) |
| **Final Audit & Handover** | [FINAL-AUDIT-REPORT.md](file:///c:/Users/Dw/Desktop/karachi%20today/FINAL-AUDIT-REPORT.md) |

---

## License & Copyright

© 2026 Karachi Today Digital Media Group. All rights reserved.
