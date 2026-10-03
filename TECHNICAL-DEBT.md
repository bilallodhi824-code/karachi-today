# KARACHI TODAY v4.0 - Technical Debt & Engineering Backlog

## Architectural Refinements for v4.1+

1. **Automated Vector Database Indexing**:
   - Current implementation uses MySQL FullText + Redis TF-IDF keyword vector matching.
   - Future enhancement: Migrate semantic similarity scoring to dedicated vector store (e.g. Qdrant / PgVector) as story archive expands beyond 500,000 articles.

2. **WebAssembly Image Encoding Edge Workers**:
   - Image optimization currently executes via server-side GD/Imagick in queue workers.
   - Future enhancement: Offload initial client thumbnail generation to Cloudflare WebAssembly Workers for instant upload previews.
