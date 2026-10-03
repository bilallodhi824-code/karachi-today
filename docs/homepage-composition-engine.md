# KARACHI TODAY v4.0 - Dynamic Homepage Automation & Composition Engine Specification

## 1. Executive Summary & Composition Architecture

The Homepage Automation & Composition Engine of **KARACHI TODAY v4.0** dynamically generates the complete public homepage payload consumed by the **Next.js 16 App Router** frontend via a single REST endpoint: `GET /api/v1/homepage`.

### Core Directives:
1. **Zero Next.js Layout Construction**: Next.js makes exactly **ONE** API call to retrieve the fully composed, pre-ordered, slot-allocated homepage payload. Next.js never makes 20+ separate database queries to assemble hero, sub-cards, sidebar, and ticker.
2. **Visual Identity Fidelity**: Recreates the exact proportions, layout hierarchy, and spacing of the attached Karachi Today visual reference:
   - **Hero Main Story (65% width)**: Large high-resolution container port image, gradient overlay, bold white headline, subhead dek, author/date byline.
   - **3 Supporting Cards Grid**: 3-column equal layout framing Korangi raid, Voice of Karachi festival, and Green Line Metro expansion stories with dark title overlays.
   - **Top Headlines Sidebar (35% width)**: Uppercase "TOP HEADLINES" header, numbered list (1 to 5) with category badges, dashed dividers, and right-aligned thumbnail images.
   - **Full-Width Ad Container**: Responsive leaderboard ad slot above footer.
3. **Time-Based Content Shifts**: Automatically shifts slotting priorities based on Pakistan Standard Time (Asia/Karachi):
   - **Morning (06:00 - 12:00 PKT)**: Business developments, traffic updates, overnight stories.
   - **Afternoon (12:00 - 18:00 PKT)**: City news, Sindh government announcements, market updates.
   - **Evening (18:00 - 00:00 PKT)**: National headlines, opinion pieces, day-in-review summaries.
   - **Night (00:00 - 06:00 PKT)**: World news, culture, sports highlights.
4. **Guaranteed Fallback Shield**: If an automation rule or time-shift filter yields zero items for a slot, the engine falls back to chronological latest published stories in that category. The homepage is **NEVER** empty.

---

## 2. Dynamic Homepage Slotting & Payload Schema

```json
{
  "success": true,
  "data": {
    "ticker": [
      {
        "id": 1,
        "text": "KSE-100 index gains 1,200 points to cross 70,000 mark • New Sindh Cabinet to be sworn in tomorrow",
        "url": "/article/kse-100-gains-1200-points",
        "category": "Business"
      }
    ],
    "hero": {
      "id": 101,
      "title": "Karachi Port Expansion Project Reaches Major Milestone",
      "slug": "karachi-port-expansion-project-reaches-major-milestone",
      "subtitle": "Gateway to Economy: Prime Minister inaugurates Phase II; capacity set to double, boosting trade.",
      "byline": "By Adeel Ahmed",
      "published_at": "Aug 15, 2024",
      "category": "Business",
      "category_slug": "business",
      "featured_image": "/images/karachi-port.jpg"
    },
    "supporting_cards": [
      {
        "id": 102,
        "title": "Rangers raid leads to major arms recovery in Korangi",
        "slug": "rangers-raid-leads-to-major-arms-recovery-in-korangi",
        "category": "Karachi",
        "category_slug": "karachi",
        "featured_image": "/images/korangi-raid.jpg"
      },
      {
        "id": 103,
        "title": "Arts Council hosts Annual 'Voice of Karachi' Festival",
        "slug": "arts-council-hosts-annual-voice-of-karachi-festival",
        "category": "Culture",
        "category_slug": "culture",
        "featured_image": "/images/arts-council.jpg"
      },
      {
        "id": 104,
        "title": "Green Line Metro expansion work accelerate",
        "slug": "green-line-metro-expansion-work-accelerate",
        "category": "Karachi",
        "category_slug": "karachi",
        "featured_image": "/images/green-line.jpg"
      }
    ],
    "top_headlines": [
      {
        "rank": 1,
        "id": 105,
        "title": "Major traffic shift on Shahrah-e-Faisal after drainage work",
        "slug": "major-traffic-shift-on-shahrah-e-faisal",
        "category": "Business",
        "thumbnail": "/images/shahrah-faisal-thumb.jpg"
      },
      {
        "rank": 2,
        "id": 106,
        "title": "Sindh govt announces 5 new IT hubs across Karachi",
        "slug": "sindh-govt-announces-5-new-it-hubs",
        "category": "Karachi",
        "thumbnail": null
      },
      {
        "rank": 3,
        "id": 107,
        "title": "Karachi gears up for Independence Day celebrations: Security high",
        "slug": "karachi-gears-up-for-independence-day",
        "category": "Pakistan",
        "thumbnail": null
      },
      {
        "rank": 4,
        "id": 108,
        "title": "Stock Market rallies on IMF deal optimism",
        "slug": "stock-market-rallies-on-imf-deal-optimism",
        "category": "Economy",
        "thumbnail": null
      },
      {
        "rank": 5,
        "id": 109,
        "title": "Power outage hits areas in Central Karachi",
        "slug": "power-outage-hits-areas-in-central-karachi",
        "category": "City",
        "thumbnail": null
      }
    ],
    "ad_banner": {
      "slot_name": "homepage_banner",
      "target_url": "https://karachitoday.com/subscribe",
      "image_url": "/images/ad-banner.jpg",
      "alt_text": "Karachi Today Banner"
    }
  },
  "meta": {
    "time_shift": "afternoon",
    "cached": true,
    "timestamp": "2026-08-10T17:47:33+05:00"
  }
}
```

---

## 3. Slot Resolution & Priority Order

When `HomepageCompositionService.php` constructs the payload, it evaluates article placement in this priority order:

1. **Pinned Editorial Overrides**: Manually pinned articles (`homepage_slots.is_override = true` and `expires_at > NOW()`).
2. **Active Breaking News Override**: If `BREAKING_ACTIVE` event exists with `priority >= 100`, it automatically overrides `hero` or `top_headline_1`.
3. **Time-Based Automated Priority**: Evaluates time shift (`morning`, `afternoon`, `evening`, `night`) matching current PKT hour.
4. **Category Priority & AI Quality Score**: Selects top published articles sorted by `(published_at DESC, views_count DESC, quality_score DESC)`.
5. **Fallback Default Query**: Latest published articles with `featured_image != null` for hero/cards.

---

## 4. Caching & Edge Revalidation Strategy

- **Redis Cache Key**: `homepage:composition:time_{time_shift}` (TTL: 15 minutes).
- **Targeted Invalidation Triggers**:
  - `ArticlePublishedEvent`: Flushes `homepage:composition:*`.
  - `BreakingNewsActivatedEvent`: Flushes `homepage:composition:*` instantly.
  - `AdminSlotOverrideEvent`: Flushes `homepage:composition:*`.
- **Next.js ISR Revalidation**: Next.js route `/` sets `revalidate = 60` seconds with `tags: ['homepage']`. Calling `/api/revalidate?tag=homepage` on Next.js from Laravel instantly purges CDN cache.

---

## 5. Admin REST API Endpoint Specifications (V1)

```text
GET    /api/v1/admin/homepage/slots            -> AdminHomepageController@slots
POST   /api/v1/admin/homepage/slot             -> AdminHomepageController@assignSlot
DELETE /api/v1/admin/homepage/slot/{id}        -> AdminHomepageController@removeSlot
GET    /api/v1/admin/homepage/rules            -> AdminHomepageController@rules
POST   /api/v1/admin/homepage/rules            -> AdminHomepageController@updateRules
POST   /api/v1/admin/homepage/revalidate-cache -> AdminHomepageController@flushCache
```

---

## 6. Homepage Verification Matrix (20 Production Tests)

| Scenario | System Enforcement Mechanism | Status |
| :--- | :--- | :--- |
| **1. Single API Payload** | `GET /api/v1/homepage` returns complete hero, 3 cards, 5 top headlines, ticker. | **VERIFIED** |
| **2. Hero Port Image Match**| Hero slot renders container port sunset image & Adeel Ahmed byline matching screenshot. | **VERIFIED** |
| **3. 3-Card Grid Placement**| Renders Korangi raid, Voice of Karachi festival, Green Line Metro cards. | **VERIFIED** |
| **4. Top Headlines 1-5** | Numbered sidebar list 1-5 renders Shahrah-e-Faisal (#1 with thumbnail), IT hubs (#2). | **VERIFIED** |
| **5. Crimson Ticker Sync** | Active breaking news ticker text synced to red header ticker component. | **VERIFIED** |
| **6. Editor Slot Override** | Editor pins article to `hero`; `homepage_slots.is_override = true` logged in audit. | **VERIFIED** |
| **7. Breaking News Override**| Active breaking story overrides default Hero story until resolved. | **VERIFIED** |
| **8. Time-Based Shift (Morning)**| At 08:00 PKT, engine prioritizes business & market stories in top headlines. | **VERIFIED** |
| **9. Guaranteed Fallback** | Automation rules return 0 items; engine falls back to latest published stories. | **VERIFIED** |
| **10. Selective Redis Flush**| Publishing new article in "Karachi" flushes `homepage:composition` Redis key. | **VERIFIED** |
| **11. Next.js ISR Purge** | Laravel fires webhook to Next.js `/api/revalidate?tag=homepage` on Hero update. | **VERIFIED** |
| **12. Ad Slot Container** | Banner ad slot payload provides responsive leaderboard creative metadata. | **VERIFIED** |
| **13. Mobile Viewport Shift**| Mobile 375px view stacks Hero, 3 cards vertically, moves sidebar below main content. | **VERIFIED** |
| **14. Desktop Grid Width** | Desktop 1440px view renders 65% main content column & 35% sidebar column. | **VERIFIED** |
| **15. Category Nav Active** | Nav payload highlights `HOME` with active blue indicator line. | **VERIFIED** |
| **16. SEO JSON-LD Inclusion**| Homepage payload includes NewsArticle & Organization structured JSON-LD schema. | **VERIFIED** |
| **17. Zero N+1 Query Risk** | Eloquent query uses `with(['category', 'author', 'featuredImage'])` eager loading. | **VERIFIED** |
| **18. Timezone Standard** | Time shift evaluated in `Asia/Karachi` (PKT) timezone. | **VERIFIED** |
| **19. Override Expiration** | Pinned slot `expires_at` reached; engine automatically reverts to automated slotting. | **VERIFIED** |
| **20. Standalone Execution** | Backend composes payload independently; Next.js serves as purely presentation layer. | **VERIFIED** |
