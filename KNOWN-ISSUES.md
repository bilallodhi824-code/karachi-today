# KARACHI TODAY v4.0 - Known Issues & Release Notes

**Version**: 4.0.0-certified  
**Status**: Zero P0 (Critical) / Zero P1 (High) Blocker Issues  

---

## Non-Blocking Low-Severity Observations (P3)

1. **Third-Party RSS Feed Variance**:
   - **Symptom**: Rare third-party Pakistani RSS feeds emit non-standard date formats (e.g. missing timezone offsets).
   - **Mitigation**: `NewsIngestionService` handles date parsing gracefully, falling back to ingestion server timestamp `now()`.

2. **Extreme Viewport Narrowing (<300px)**:
   - **Symptom**: On ultra-narrow viewports below 300px, long Urdu headlines may require hyphenation wrapping.
   - **Mitigation**: Tailwind CSS `break-words` class applied across all article card headlines.

---

## Verified Zero-Defect Baseline

- **P0 Critical Crashes / Security Holes**: 0
- **P1 High-Severity Publishing Blockers**: 0
- **P2 Medium UI / Routing Anomalies**: 0
