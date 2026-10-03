# KARACHI TODAY v4.0 - Page-by-Page Real News Dynamic Audit

**Audit Date**: August 10, 2026  
**Status**: 100% Dynamic / Connected to Database & Real News Engine  

---

## Public Routes Audit Matrix

| Page / Route | Primary Data Source | Database Table / Query | Dynamic Status | Loading / Empty / Error State Support |
| :--- | :--- | :--- | :--- | :--- |
| **`/` (Homepage)** | `HomepageCompositionService` | `articles`, `breaking_news`, `categories` | **DYNAMIC** | Skeleton loading, Fallback news, Custom error boundary |
| **`/news/[slug]`** | `ArticleService` | `articles` JOIN `users`, `categories` | **DYNAMIC** | Loading skeleton, 404 Not Found, Attribution badge |
| **`/pakistan`** | `CategoryService` | `articles` WHERE `category_id = 'pakistan'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/karachi`** | `CategoryService` | `articles` WHERE `category_id = 'karachi'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/sindh`** | `CategoryService` | `articles` WHERE `category_id = 'sindh'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/world`** | `CategoryService` | `articles` WHERE `category_id = 'world'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/business`** | `CategoryService` | `articles` WHERE `category_id = 'business'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/technology`** | `CategoryService` | `articles` WHERE `category_id = 'technology'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/sports`** | `CategoryService` | `articles` WHERE `category_id = 'sports'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/entertainment`**| `CategoryService` | `articles` WHERE `category_id = 'entertainment'`| **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/health`** | `CategoryService` | `articles` WHERE `category_id = 'health'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/science`** | `CategoryService` | `articles` WHERE `category_id = 'science'` | **DYNAMIC** | Category grid skeleton, Empty category alert |
| **`/search`** | `SearchService` | `articles` FULLTEXT INDEX MATCH | **DYNAMIC** | Search skeleton, No search results state |
| **`/trending`** | `TrendDetectionService` | `article_metrics` ORDER BY velocity | **DYNAMIC** | Velocity ranking loader, Fallback to latest |
| **`/most-read`** | `AnalyticsService` | `article_metrics` ORDER BY views_count | **DYNAMIC** | Views list loader, Fallback to recent |
| **`/topic/[slug]`** | `TopicService` | `articles` JOIN `article_topics` | **DYNAMIC** | Topic header skeleton, Empty topic state |
| **`/author/[slug]`**| `AuthorService` | `articles` WHERE `user_id = id` | **DYNAMIC** | Author profile skeleton, Verified bio display |
| **`/live`** | `LiveBlogService` | `live_blogs`, `live_blog_updates` | **DYNAMIC** | Live update stream loader, Offline badge |

---

## Admin Control Center Routes Audit Matrix

| Admin Route | Operational Function | Backend Controller / Service | Status |
| :--- | :--- | :--- | :--- |
| **`/admin/news-sources`** | Licensed Provider Connection & Quota | `Admin\NewsSourceController` | **OPERATIONAL** |
| **`/admin/automation/ingestion`** | Real-Time Ingestion Logs & Queue Status | `Admin\IngestionMonitorController` | **OPERATIONAL** |
| **`/admin/articles`** | Article Management & Editorial Approval | `Admin\ArticleController` | **OPERATIONAL** |
| **`/admin/system/operations`** | Health Diagnostics & AES-256 Backups | `Admin\OperationsController` | **OPERATIONAL** |

---

**AUDIT CERTIFICATION**: Every public route and admin dashboard module is 100% dynamic, connected to MySQL 8.4 LTS and the Licensed News Ingestion Pipeline.
