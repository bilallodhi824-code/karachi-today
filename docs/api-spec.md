# KARACHI TODAY v4.0 - REST API Specification (V1)

## Base URL: `https://api.karachitoday.com/api/v1`

All responses follow a strict standard JSON structure:

```json
{
  "success": true,
  "data": {},
  "meta": {
    "timestamp": "2026-08-10T17:27:46+05:00",
    "version": "v1.0"
  },
  "error": null
}
```

---

## Public Endpoints

### 1. `GET /homepage`
Returns the dynamic payload for rendering the public home page.

**Response Data Structure:**
```json
{
  "ticker": [
    {
      "id": 1,
      "text": "KSE-100 index gains 1,200 points to cross 70,000 mark",
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
    "featured_image": "/images/karachi-port.jpg"
  },
  "supporting_cards": [
    {
      "id": 102,
      "title": "Rangers raid leads to major arms recovery in Korangi",
      "slug": "rangers-raid-leads-to-major-arms-recovery-in-korangi",
      "category": "Karachi",
      "featured_image": "/images/korangi-raid.jpg"
    },
    {
      "id": 103,
      "title": "Arts Council hosts Annual 'Voice of Karachi' Festival",
      "slug": "arts-council-hosts-annual-voice-of-karachi-festival",
      "category": "Culture",
      "featured_image": "/images/arts-council.jpg"
    },
    {
      "id": 104,
      "title": "Green Line Metro expansion work accelerate",
      "slug": "green-line-metro-expansion-work-accelerate",
      "category": "Karachi",
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
  ]
}
```

---

### 2. `GET /articles`
Paginated article list with filtering.

**Query Parameters:**
- `category`: string (e.g. `karachi`, `pakistan`, `business`)
- `tag`: string
- `search`: string
- `page`: integer
- `per_page`: integer (default 15)

---

### 3. `GET /articles/{slug}`
Retrieves article view payload including author profile, category hierarchy, structured NewsArticle JSON-LD schema, and related articles.

---

## Editorial Admin API Endpoints

### 1. `POST /admin/articles`
Creates a manual article or edits existing content.

### 2. `PATCH /admin/articles/{id}/publish`
Publishes an article from the review queue.

### 3. `POST /admin/homepage/slot`
Sets an article directly into a homepage slot (`hero`, `sub_hero_1`, `top_headline_1`, etc.).
