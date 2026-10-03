# KARACHI TODAY v4.0 - Visual Identity & Design System Specification

Derived from attached visual reference image: `Karachi Today Homepage Reference`.

---

## 1. Color Palette

```gss
/* Brand Palette Tokens */
--color-navy-dark:     #0A192F; /* Header & Footer background */
--color-navy-light:    #112240; /* Header secondary / Nav hover */
--color-crimson:       #B91C1C; /* Breaking News Ticker background */
--color-accent-blue:    #00B4D8; /* Active tab indicator / Highlight links */
--color-white:         #FFFFFF; /* Canvas & card background */
--color-surface-gray:  #F8FAFC; /* Page background / Ad container */
--color-border-gray:   #E2E8F0; /* Divider lines */
--color-text-main:     #0F172A; /* Primary headline text */
--color-text-muted:    #64748B; /* Meta / category / byline text */
```

---

## 2. Typography & Font Hierarchy

- **Primary Serif Header Font**: Georgia / Playfair Display / Serif system stack (Used for `KARACHI TODAY` logo & major headlines).
- **Body & Metadata Sans Font**: Inter / Roboto / System sans-serif stack.

### Heading System:
- **Hero Title**: `2.25rem` (36px), Bold, Leading `1.2`, White text on dark gradient or Dark text on white canvas.
- **Section Headers**: `1.25rem` (20px), Bold, Uppercase (`TOP HEADLINES`), Dark text with light grey bottom border.
- **Card Titles**: `1.125rem` (18px), Semi-bold.
- **Numbered Sidebar Titles**: `1rem` (16px), Bold, with `1.5rem` bold numeric prefix (1, 2, 3, 4, 5).
- **Category Badges**: `0.75rem` (12px), Medium, Text Muted (`#64748B`).

---

## 3. Component Specifications

### 1. Breaking News Ticker
- Height: `36px`
- Background: Red (`#B91C1C`)
- Content: Bold white label "BREAKING NEWS:", followed by horizontal scrolling text separated by bullet points (`•`).
- Right align: Social media SVG icons (Facebook, X/Twitter, Instagram, YouTube).

### 2. Main Navigation Header
- Height: `80px`
- Background: Deep Navy (`#0A192F`)
- Left: `KARACHI TODAY` serif logo with crescent & star motif embedded in the typography.
- Right: Search input (`Search news...`) and white bordered "SUBSCRIBE" button.
- Bottom Nav Bar: Categories (`HOME`, `KARACHI`, `PAKISTAN`, `WORLD`, `BUSINESS`, `OPINION`, `SPORTS`, `CULTURE`, `LIVE`). Active tab `HOME` features a light blue underline pill.

### 3. Main Hero & 3-Card Grid
- Layout: 2-column asynchronous grid (Main column ~65%, Right Sidebar ~35%).
- Main Column:
  - Hero Card: High-resolution image (Container port sunset), dark gradient overlay, bold white headline, subhead dek, byline (`By Adeel Ahmed | Aug 15, 2024`).
  - Sub-grid: 3 equal cards (`Rangers raid...`, `Arts Council...`, `Green Line Metro...`) with square/4:3 ratio images and text overlays.

### 4. Sidebar ("TOP HEADLINES")
- Column width: ~35%
- Top divider line with bold uppercase header `TOP HEADLINES`.
- Numbered items 1 through 5 with dashed bottom dividers.
- Items 1 features a right-aligned thumbnail image.

### 5. Ad Slot
- Responsive container box above footer with light grey background (`#F1F5F9`) and border.
- Supports standard Leaderboard (`728x90`), Billboard (`970x250`), or responsive banner ads.

### 6. Responsive Breakpoints
- Supported viewport widths: `320px`, `360px`, `375px`, `390px`, `414px`, `480px`, `768px`, `834px`, `1024px`, `1280px`, `1366px`, `1440px`, `1536px`, `1920px`.
- Mobile strategy: Ticker stacks, Nav becomes accessible drawer/toggle, Hero scales full width, 3-card grid stacks vertically, Sidebar moves below main stories.
