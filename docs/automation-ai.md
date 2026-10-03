# KARACHI TODAY v4.0 - Automation Engine & AI Pipeline Architecture

## 1. Automation Workflow

```
[ Scheduled RSS / API Cron ]
            │
            ▼
[ Ingestion Driver Queue Job ]
            │
            ▼
[ Deduplication Check (SimHash) ] ── (Duplicate?) ──► [ Log & Discard ]
            │ (New Article)
            ▼
[ Raw Content Extraction & Sanitization ]
            │
            ▼
[ AI Pipeline Processing Driver ]
   ├── Category Classification
   ├── Entity Extraction & Auto-Tagging
   ├── 2-Sentence Summary Generation
   └── AI Confidence Scoring & Sensitivity Flagging
            │
            ▼
[ Automated Publishing Logic ]
   ├── IF Trust Level == 'trusted' AND Confidence >= 85%:
   │     STATUS = 'published' (Instant Dynamic Revalidation)
   └── ELSE:
         STATUS = 'review' (Routed to Editorial CMS Queue)
```

---

## 2. Ingestion Engine Architecture

### Polling Engine
- **Driver Abstraction**: Interfaces with RSS/Atom XML, REST JSON, and agency socket feeds.
- **Source Configuration**: Each source maintains trust level (`verified`, `trusted`, `review_required`, `blocked`) and backoff rate.
- **Failover & Backoff**: Exponential decay if feed returns 4xx/5xx errors; logs failure event in `ingestion_logs`.

---

## 3. AI Processing Pipeline

### Prompts & Provider Integration
- Primary LLM driver using structured output (JSON schema).
- Providers: OpenAI GPT-4o / Google Gemini 1.5 Pro / Anthropic Claude 3.5 Sonnet.

### Standardized AI Response Payload Schema:
```json
{
  "category_slug": "karachi",
  "tags": ["Shahrah-e-Faisal", "Traffic", "Drainage", "KMC"],
  "summary": "Sindh government initiates major drainage upgrades along Shahrah-e-Faisal resulting in traffic shifts near Nursery.",
  "confidence_score": 92,
  "is_breaking_news": false,
  "sensitivity_flags": [],
  "headline_suggestions": [
    "Major traffic shift on Shahrah-e-Faisal after drainage work",
    "Shahrah-e-Faisal drainage project causes temporary traffic re-routing"
  ]
}
```

---

## 4. Breaking News & Time-Based Homepage Engine

- **Breaking News Engine**: Triggered automatically when an incoming article matches high confidence + critical keyword matrices (e.g. "Earthquake", "Curfew", "IMF Deal", "Prime Minister"). Immediately updates the crimson Breaking News Ticker and sends webhook alerts to editorial teams.
- **Time-Based Slotting Engine**: Shifts content priorities based on local time (PKT - Pakistan Standard Time):
  - **Morning (06:00 - 12:00)**: Business news, traffic updates, overnight developments.
  - **Afternoon (12:00 - 18:00)**: Breaking city news, government announcements, market updates.
  - **Evening (18:00 - 24:00)**: Top national stories, opinion pieces, evening summaries.
  - **Night (00:00 - 06:00)**: World news, sports highlights, culture features.
