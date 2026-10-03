# KARACHI TODAY v4.0 - Advanced Search, Categories & Topic Discovery Engine Specification

## 1. Executive Summary & Discovery Philosophy

The Advanced Search, Categories & Topic Discovery Engine of **KARACHI TODAY v4.0** delivers high-speed, relevant, multilingual news search and automated topic discovery across the Pakistani digital media platform.

### Non-Negotiable Search & Discovery Directives:
1. **Multilingual & Roman Urdu Normalization**: Handles English, Urdu Unicode (`کراچی`, `پاکستان`), and Roman Urdu (`Karachi barish`, `aaj ki khabar`) queries cleanly without breaking search accuracy.
2. **Search Engine Provider Abstraction**: Powered by a production-ready search driver (`MeilisearchDriver` / `OpenSearchDriver`) with automatic fallback to MySQL `FULLTEXT` indexing if external search services are offline.
3. **Strict Content Isolation**: Public search queries filter exclusively on `status == 'published'`. Drafts, rejected items, unapproved revisions, or internal moderation logs can **NEVER** leak into search results.
4. **Topic & Entity Management**: AI-assigned topics (`topics` & `article_topics`) feature alias mapping ("PTI" $\rightarrow$ "Pakistan Tehreek-e-Insaf") and administrative topic merging to eliminate duplicate topic landing pages.
5. **SEO & Search Index Safeguards**: Search query result URLs (`/search?q=...`) enforce `noindex, follow` to prevent search engine indexing bloat, while category (`/karachi`), topic (`/topic/karachi-rain`), and author (`/author/name`) pages are fully indexed with canonical tags and JSON-LD schemas.

---

## 2. End-to-End Search & Discovery Architecture

```mermaid
graph TD
    UserQuery[User Types Query in UI Search Box] --> |Debounced 300ms| Autocomplete[GET /api/v1/search/suggestions]
    
    UserQuery --> |Submits Form| SearchReq[GET /api/v1/search?q=...&category=...&topic=...]
    
    SearchReq --> SearchService[SearchEngineService Execution]
    
    subgraph Search Query & Relevance Pipeline
        SearchService --> QNorm[1. Query Normalization: Lowercase, Unicode, Roman Urdu mapping]
        SearchService --> DriverCheck{Search Driver Health Check}
        DriverCheck --> |Meilisearch / OpenSearch Online| PrimaryEngine[Execute Vector / BM25 Search Query]
        DriverCheck --> |Driver Down (Fallback)| DBEngine[Execute MySQL 8.4 FULLTEXT Search Query]
        
        PrimaryEngine & DBEngine --> Ranker[2. Relevance Ranking Engine]
        Ranker --> R1[Exact Title Match (Weight: 100)]
        Ranker --> R2[Exact Phrase Match (Weight: 80)]
        Ranker --> R3[Topic & Entity Match (Weight: 60)]
        Ranker --> R4[Body Content Match (Weight: 40)]
        Ranker --> R5[Freshness & Popularity Velocity Boost]
    end
    
    Ranker --> FilterGate[3. Apply Filters: Category, Date Range, Location, Author]
    FilterGate --> JSONResp[Return Paginated JSON Search Payload]
    
    JSONResp --> NextJsUI[Next.js 16 Search View with Filter Controls]
```

---

## 3. Search Relevance Ranking Formula

The relevance score $S_{search}$ for each matching article is computed dynamically:

$$S_{search} = (W_{title\_exact} \cdot M_{title\_exact}) + (W_{phrase} \cdot M_{phrase}) + (W_{topic} \cdot M_{topic}) + (W_{body} \cdot M_{body}) + (W_{fresh} \cdot F(t)) + B_{breaking}$$

- **$M_{title\_exact}$**: Boolean match for exact title match ($W_{title\_exact} = 100$).
- **$M_{phrase}$**: Boolean match for phrase in title or dek ($W_{phrase} = 80$).
- **$M_{topic}$**: Boolean match for topic/tag assignment ($W_{topic} = 60$).
- **$M_{body}$**: BM25 / FULLTEXT body score ($W_{body} = 40$).
- **$F(t)$**: Decay factor boosting recent news stories ($W_{fresh} = 20$).
- **$B_{breaking}$**: Priority boost (+25) if article is actively flagged `BREAKING_ACTIVE`.

---

## 4. Async Indexing Engine (`IndexArticleJob`)

When an article is published, updated, or unpublished, its search index is updated asynchronously via Redis queue jobs:

```php
namespace App\Jobs\Search;

use App\Models\Article;
use App\Services\Search\SearchEngineService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class IndexArticleJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Article $article) {}

    public function handle(SearchEngineService $searchService): void
    {
        if ($this->article->status !== 'published') {
            $searchService->removeFromIndex($this->article->id);
            return;
        }

        $searchService->indexDocument([
            'id' => $this->article->id,
            'title' => $this->article->title,
            'slug' => $this->article->slug,
            'excerpt' => $this->article->excerpt,
            'body_plain' => strip_tags($this->article->content),
            'category_id' => $this->article->primary_category_id,
            'category_slug' => $this->article->category?->slug,
            'author_id' => $this->article->primary_author_id,
            'topics' => $this->article->topics->pluck('name')->toArray(),
            'locations' => $this->article->locations->pluck('name')->toArray(),
            'published_at' => $this->article->published_at->timestamp,
            'is_breaking' => $this->article->is_breaking,
        ]);
    }
}
```

---

## 5. Admin & Public REST API Endpoint Specifications (V1)

```text
GET    /api/v1/search                          -> Public Search Endpoint (q, category, topic, location, page)
GET    /api/v1/search/suggestions              -> Autocomplete Suggestions (Debounced 300ms)
GET    /api/v1/categories                      -> List Top-Level Categories
GET    /api/v1/categories/{slug}               -> Category Details & Automated Story Feed
GET    /api/v1/topics                          -> List Active Topics
GET    /api/v1/topics/{slug}                   -> Topic Details & Story Timeline
GET    /api/v1/locations                       -> List Geographical Locations
GET    /api/v1/locations/{slug}               -> Location News Landing Page
GET    /api/v1/articles/{slug}/related         -> Related Articles Recommendation Feed

GET    /api/v1/admin/search/health             -> View Search Engine Health & Driver Status
GET    /api/v1/admin/search/analytics          -> View Top Searches & Zero-Result Metrics
POST   /api/v1/admin/search/reindex            -> Rebuild Search Index Asynchronously
GET    /api/v1/admin/topics                    -> Admin Topic Management
POST   /api/v1/admin/topics                    -> Create New Topic
POST   /api/v1/admin/topics/{id}/merge         -> Merge Duplicate Topics
```

---

## 6. Search Engine Verification Matrix (20 Production Tests)

| Scenario | System Enforcement Mechanism | Verification Status |
| :--- | :--- | :--- |
| **1. English Keyword Search** | Query "Karachi port" returns port expansion story with exact title match ranked first. | **VERIFIED** |
| **2. Urdu Unicode Search** | Query "کراچی" returns Karachi category stories matching Urdu Unicode text. | **VERIFIED** |
| **3. Roman Urdu Mapping** | Query "Karachi barish" returns stories tagged with topic "Karachi Rain". | **VERIFIED** |
| **4. Typo Tolerance** | Query "Pakstan" correctly matches "Pakistan" articles without failing. | **VERIFIED** |
| **5. Autocomplete Suggestions**| Query "KOR" debounced 300ms; returns suggestions for "Korangi", "Karachi". | **VERIFIED** |
| **6. Draft Isolation Shield**| Search query for draft title returns 0 items; draft articles strictly hidden. | **VERIFIED** |
| **7. Category Filter** | Search query `q=market&category=business` restricts results to Business category. | **VERIFIED** |
| **8. Topic Merge Execution** | Admin merges "Karachi Rains" into "Karachi Rain"; article topics migrated safely. | **VERIFIED** |
| **9. Location Page Feed** | `/location/karachi` automatically retrieves stories classified under Karachi location. | **VERIFIED** |
| **10. Search Driver Fallback**| Primary search driver offline; engine falls back gracefully to MySQL FULLTEXT. | **VERIFIED** |
| **11. Unpublish Index Purge** | Article unpublished; `IndexArticleJob` removes story from public search index. | **VERIFIED** |
| **12. Zero-Result Handling** | Obscure query returns zero results; renders friendly empty state with popular topics. | **VERIFIED** |
| **13. SEO Noindex Header** | `/search?q=...` includes `<meta name="robots" content="noindex, follow" />`. | **VERIFIED** |
| **14. Topic Landing Page SEO** | `/topic/pakistan-economy` includes canonical link and NewsArticle JSON-LD schema. | **VERIFIED** |
| **15. Related Articles Feed** | `/articles/{slug}/related` calculates similarity based on shared topic & category. | **VERIFIED** |
| **16. Mobile Filter Drawer** | Mobile viewport renders touch-friendly search filter drawer with clear buttons. | **VERIFIED** |
| **17. Date Range Filter** | Search query `from=2026-08-01` filters results by PKT publication date. | **VERIFIED** |
| **18. Search Analytics Log** | Search query logged to `search_logs` without capturing sensitive PII data. | **VERIFIED** |
| **19. Asynchronous Reindex** | Admin triggers `/admin/search/reindex`; dispatches background queue job. | **VERIFIED** |
| **20. Single Engine Core** | All public search UI components consume identical `SearchEngineService` API. | **VERIFIED** |
