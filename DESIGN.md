# Design System — Church Directory

## Current Color Palette

All colors defined as CSS variables in [css/components.css](css/components.css):

```
--aba-dark: #1a242f      /* Dark navy, top bar */
--aba-cream: #f4eee1     /* Warm cream, main nav & pages */
--aba-accent: #ee6352    /* Coral red, CTAs */
--aba-green: #4a6d55     /* Deep green, logo text */
```

### Color Usage
- **Background**: cream (#f4eee1) dominates
- **Accent borders**: green (#4a6d55) on cards
- **CTA buttons**: coral (#ee6352)
- **Header/footer**: dark (#1a242f)

## Current Typography

**Font stack used across site**: System defaults (appears to be serif in headers, sans-serif in body)

**Hierarchy observations:**
- H1: Large, bold, serif
- H2: Medium, serif
- H3: Smaller headings
- Body: Serif font, reasonable contrast

**Issues:**
- Line length not optimized for readability
- Inconsistent font family usage
- Limited weight variation

## Current Components

### Header/Navigation
- Top utility bar with dark background
- Main nav bar with cream background
- Sticky positioning
- Logo on left, nav center, CTA button right
- Good layout but spacing could be refined

### Cards
- Event cards: light blue background with green top border
- Church cards: rounded corners, light blue background
- Image placeholder areas with church icons
- Simple text overlay with location tags

**Issues:**
- Cards lack depth and shadow
- Insufficient visual hierarchy within cards
- Hover states not visible/defined
- No micro-interactions

### Search/Filter
- Text input with search icon
- Region filter tabs (All, Luzon, Visayas, Mindanao)
- Simple styling without visual feedback

### Buttons
- "Get Listed" button: coral red, large, rounded
- "View Details" / "Read More": text links with underline
- Minimal hover feedback

### Footer
- Dark background, links + copyright
- Utilitarian styling

## Current Spacing & Layout

- Page max-width: 100% (full width on desktop)
- Padding: 40px horizontal
- Card gaps: moderate but could vary more
- Vertical rhythm: inconsistent

## Responsive Behavior
- Not fully reviewed yet in small viewports
- Navigation needs assessment for mobile

## Accessibility Notes
- Good contrast on most text
- Color dependency on cards (green border = important, but no other distinction)
- Missing ARIA labels in some interactive elements
- Focus states not visible

## Visual Hierarchy Issues
- Hero section is strong but content below lacks depth
- Card sections feel flat
- Limited use of elevation/shadow
- Too much uniformity in spacing
