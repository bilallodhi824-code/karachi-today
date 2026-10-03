# KARACHI TODAY v4.0 - Laravel 13 Backend & REST API Architecture Specification

## 1. Executive Summary & Architectural Layering

The backend of **KARACHI TODAY v4.0** is an enterprise-grade, RESTful API platform built on **Laravel 13 (PHP 8.5 target)**. The backend owns all business logic, ingestion pipelines, AI processing, user authentication, publishing workflows, caching, and audit logging.

```
┌─────────────────────────────────────────────────────────────┐
│                 Next.js 16 Client Frontend                  │
└──────────────────────────────┬──────────────────────────────┘
                               │ HTTPS REST API Requests (/api/v1/...)
┌──────────────────────────────▼──────────────────────────────┐
│                    API Gateway / Router                     │
│        (Cors, SecurityHeaders, RateLimiting, Sanctum)       │
└──────────────────────────────┬──────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────┐
│                  Form Request Validation                    │
│   (StoreArticleRequest, PublishArticleRequest, SearchReq)   │
└──────────────────────────────┬──────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────┐
│                     REST Controllers                        │
│ (HomepageController, ArticleController, AdminControllers)   │
└──────────────────────────────┬──────────────────────────────┘
                               │
┌──────────────────────────────▼──────────────────────────────┐
│                     Application Services                    │
│  (ArticlePublishingService, NewsIngestionService, AI...)    │
└──────────────┬───────────────────────────────┬──────────────┘
               │                               │
┌──────────────▼──────────────┐  ┌─────────────▼──────────────┐
│      Eloquent ORM           │  │   Asynchronous Redis Jobs  │
│  (Strict Models & MySQL)    │  │ (Ingestion, AI, Publish)   │
└──────────────┬──────────────┘  └─────────────┬──────────────┘
               │                               │
┌──────────────▼──────────────┐  ┌─────────────▼──────────────┐
│        MySQL 8.4 LTS        │  │     Redis Queue & Cache    │
└─────────────────────────────┘  └────────────────────────────┘
```

---

## 2. Monorepo Backend Folder Structure (`/backend`)

```text
backend/
├── app/
│   ├── Console/
│   │   └── Commands/             # Artisan Commands (PollFeedsCommand, PublishScheduledCommand)
│   ├── Events/                   # Domain Events (ArticlePublishedEvent, BreakingNewsTriggeredEvent)
│   ├── Http/
│   │   ├── Controllers/Api/V1/
│   │   │   ├── Public/           # Homepage, Article, Category, Search, BreakingNews Controllers
│   │   │   └── Admin/            # Editorial, Source, AI, User, System Admin Controllers
│   │   ├── Middleware/           # SanctumAuth, CheckPermission, AuditLogger, RateLimiting
│   │   ├── Requests/V1/          # Form Requests for Strict Validation
│   │   └── Resources/V1/         # API JSON Resource Transformers
│   ├── Jobs/                     # Redis Queue Jobs (FetchNewsFeedJob, ProcessAiArticleJob, etc.)
│   ├── Listeners/                # Event Handlers (InvalidateHomepageCache, SendPushNotification)
│   ├── Models/                   # Eloquent Models (Article, Category, NewsSource, User, etc.)
│   ├── Services/                 # Business Logic Services
│   │   ├── AI/                   # AI Provider Interface & Adapters (OpenAI, Gemini, Anthropic)
│   │   ├── Ingestion/            # RSS Parsing, Wire Drivers, Normalizers
│   │   ├── Deduplication/        # SimHash & Content Fingerprinting Engine
│   │   ├── Publishing/           # ArticlePublishingService & Scheduling Engine
│   │   ├── Homepage/             # HomepageCompositionService & Slotting Engine
│   │   └── Search/               # SearchService Abstraction
│   └── Support/                  # Enums & Helper Classes
├── config/                       # Laravel App Configs (cors, database, queue, ai, caching)
├── database/                     # MySQL 8.4 Migrations & Seeders
├── routes/
│   ├── api.php                   # Versioned REST API Routes (/api/v1/)
│   └── console.php               # Scheduler Task Rules
├── tests/                        # Feature & Unit Test Suites
├── composer.json
└── .env.example
```

---

## 3. Core Application Service Map

| Service Name | Primary Responsibility | Key Methods |
| :--- | :--- | :--- |
| `ArticlePublishingService` | Handles article lifecycle (draft -> review -> scheduled -> published), cache invalidation, and version snapshots. | `publish(Article $a)`, `schedule(Article $a, DateTime $time)`, `unpublish(Article $a)` |
| `NewsIngestionService` | Orchestrates feed fetching, rate limits, source health logging, and item creation. | `pollSource(NewsSource $s)`, `ingestItem(SourceItem $item)` |
| `AIProcessingService` | Routes content to configured LLM driver for categorization, summarization, and confidence scoring. | `processArticle(Article $a)`, `evaluateConfidence(SourceItem $item)` |
| `HomepageCompositionService` | Dynamically builds responsive homepage slotting payload with fallback handling. | `getHomepagePayload()`, `slotArticle(Article $a, string $slot)` |
| `BreakingNewsService` | Manages real-time ticker events, priority overrides, and expiration timers. | `triggerBreaking(Article $a, string $text)`, `resolveBreaking(int $id)` |
| `DuplicateDetectionService` | Computes content hashes and detects duplicate external wire stories. | `calculateHash(string $content)`, `isDuplicate(string $hash)` |
| `MediaService` | Handles secure uploads, MIME validation, image variant generation, and S3 storage. | `upload(UploadedFile $file)`, `createVariants(Media $m)` |
| `SearchService` | Abstracted search provider supporting MySQL full-text with Meilisearch driver readiness. | `search(string $query, array $filters, int $page)` |

---

## 4. Asynchronous Queue Architecture (Redis Jobs & Queues)

Redis Queues are isolated into dedicated functional channels to guarantee performance:

```
┌─────────────────────────────────────────────────────────────┐
│                      Redis Queue Broker                     │
├───────────────────┬───────────────────┬─────────────────────┤
│ Queue Name        │ Worker Concurrency│ Priority            │
├───────────────────┼───────────────────┼─────────────────────┤
│ `publishing`      │ 10 Workers        │ High (Immediate)    │
│ `ai-processing`   │ 8 Workers         │ Medium              │
│ `news-ingestion`  │ 5 Workers         │ Normal              │
│ `media`           │ 3 Workers         │ Background          │
│ `notifications`   │ 5 Workers         │ Medium              │
│ `analytics`       │ 2 Workers         │ Low / Batch         │
└───────────────────┴───────────────────┴─────────────────────┘
```

### Core Redis Queue Jobs:
1. `FetchNewsFeedJob`: Fetches XML/API feed for a specific `NewsSource`.
2. `ProcessImportedNewsJob`: Normalizes raw HTML content, purifies XSS, and dispatches AI processing.
3. `DetectDuplicateArticleJob`: Compares SimHash fingerprint against existing database articles.
4. `ClassifyArticleJob`: Calls AI service to categorize story and extract tags.
5. `GenerateArticleSummaryJob`: Produces 2-sentence executive briefs.
6. `PublishScheduledArticleJob`: Executed by minutely scheduler to transition scheduled posts to published.
7. `ProcessBreakingNewsJob`: Broadcasts breaking news alerts and flushes ticker cache.

---

## 5. REST API Endpoint Map (V1 Specification)

### Public Read-Only Endpoints (`/api/v1/`)

```text
GET /api/v1/homepage             -> HomepageCompositionController@index
GET /api/v1/articles             -> ArticleController@index
GET /api/v1/articles/{slug}      -> ArticleController@show
GET /api/v1/categories           -> CategoryController@index
GET /api/v1/categories/{slug}     -> CategoryController@show
GET /api/v1/latest-news          -> LatestNewsController@index
GET /api/v1/breaking-news        -> BreakingNewsController@index
GET /api/v1/trending             -> TrendingController@index
GET /api/v1/search               -> SearchController@index
GET /api/v1/authors/{slug}       -> AuthorController@show
GET /api/v1/health               -> HealthCheckController@index
```

### Authenticated Admin Endpoints (`/api/v1/admin/`)

```text
POST /api/v1/admin/auth/login    -> AdminAuthController@login
POST /api/v1/admin/auth/logout   -> AdminAuthController@logout

GET  /api/v1/admin/articles      -> AdminArticleController@index
POST /api/v1/admin/articles      -> AdminArticleController@store
GET  /api/v1/admin/articles/{id} -> AdminArticleController@show
PUT  /api/v1/admin/articles/{id} -> AdminArticleController@update
POST /api/v1/admin/articles/{id}/publish  -> AdminArticleController@publish
POST /api/v1/admin/articles/{id}/schedule -> AdminArticleController@schedule
POST /api/v1/admin/articles/{id}/approve  -> AdminArticleController@approve

POST /api/v1/admin/homepage/slot -> AdminHomepageController@assignSlot
POST /api/v1/admin/breaking-news -> AdminBreakingNewsController@store
POST /api/v1/admin/sources       -> AdminSourceController@store
GET  /api/v1/admin/ai-logs       -> AdminAiController@index
```

---

## 6. Standardized Response & Error Contracts

### Successful Response Format (HTTP 200 / 201)
```json
{
  "success": true,
  "data": {
    "id": 101,
    "title": "Karachi Port Expansion Project Reaches Major Milestone",
    "slug": "karachi-port-expansion-project-reaches-major-milestone"
  },
  "meta": {
    "timestamp": "2026-08-10T17:36:54+05:00",
    "version": "v1.0"
  }
}
```

### Error Response Format (HTTP 400 / 401 / 403 / 422 / 429 / 500)
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "The given data failed validation rules.",
    "details": {
      "title": ["The title field is required."],
      "primary_category_id": ["The selected primary category is invalid."]
    }
  }
}
```

---

## 7. Caching & Cache Invalidation Strategy

- **Driver**: Redis 7
- **Key Schemas**:
  - `homepage:composition` (TTL: 15 minutes, invalidated instantly on breaking news or hero publish).
  - `breaking_news:active` (TTL: 5 minutes, invalidated instantly on breaking ticker update).
  - `category:{slug}:page:{n}` (TTL: 1 hour, invalidated when new article published in category).
  - `article:{slug}` (TTL: 24 hours, invalidated when article is edited).

---

## 8. Security & Rate Limiting Checklist

1. **Authentication**: Laravel Sanctum bearer tokens for Admin endpoints.
2. **Permission Checks**: Spatie RBAC permissions (`articles.publish`, `homepage.manage`, `sources.manage`).
3. **Rate Limiting Rates**:
   - `GET /api/v1/search`: `30 requests / minute`
   - `POST /api/v1/admin/auth/login`: `5 requests / minute` (IP throttled)
   - `GET /api/v1/articles`: `120 requests / minute`
4. **AI Credentials Safeguard**: API keys stored in environment variables; never output in JSON responses or frontend code.
