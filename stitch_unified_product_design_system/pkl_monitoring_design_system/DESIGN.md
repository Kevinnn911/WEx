---
name: PKL Monitoring Design System
colors:
  surface: '#f3faff'
  surface-dim: '#c6deea'
  surface-bright: '#f3faff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#e6f6ff'
  surface-container: '#daf2fe'
  surface-container-high: '#d4ecf8'
  surface-container-highest: '#cfe6f2'
  on-surface: '#071e27'
  on-surface-variant: '#3e494b'
  inverse-surface: '#1e333c'
  inverse-on-surface: '#dff4ff'
  outline: '#6e797b'
  outline-variant: '#bdc8cb'
  surface-tint: '#006875'
  primary: '#00626d'
  on-primary: '#ffffff'
  primary-container: '#0b7c8a'
  on-primary-container: '#e2faff'
  inverse-primary: '#7bd4e3'
  secondary: '#8d4f00'
  on-secondary: '#ffffff'
  secondary-container: '#fc992d'
  on-secondary-container: '#673800'
  tertiary: '#00616c'
  on-tertiary: '#ffffff'
  tertiary-container: '#007c8a'
  on-tertiary-container: '#e0faff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#9af0ff'
  primary-fixed-dim: '#7bd4e3'
  on-primary-fixed: '#001f24'
  on-primary-fixed-variant: '#004f58'
  secondary-fixed: '#ffdcc0'
  secondary-fixed-dim: '#ffb876'
  on-secondary-fixed: '#2d1600'
  on-secondary-fixed-variant: '#6b3b00'
  tertiary-fixed: '#98f0ff'
  tertiary-fixed-dim: '#62d6e8'
  on-tertiary-fixed: '#001f24'
  on-tertiary-fixed-variant: '#004f58'
  background: '#f3faff'
  on-background: '#071e27'
  surface-variant: '#cfe6f2'
  primary-soft: '#BFEAF4'
  mint-soft: '#CEF4D8'
  yellow-soft: '#FFF0BD'
  accent-deep: '#B85C00'
  text-secondary: '#5F6F76'
  border-hairline: '#E3ECEE'
  background-canvas: '#F7FBFC'
  surface-white: '#FFFFFF'
  status-success: '#18794E'
  status-warning: '#A15C00'
  status-error: '#B42318'
typography:
  display-lg:
    fontFamily: Nunito Sans
    fontSize: 32px
    fontWeight: '800'
    lineHeight: 38px
    letterSpacing: -0.02em
  display-lg-mobile:
    fontFamily: Nunito Sans
    fontSize: 26px
    fontWeight: '800'
    lineHeight: 32px
    letterSpacing: -0.015em
  kpi-metric:
    fontFamily: Nunito Sans
    fontSize: 36px
    fontWeight: '800'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Nunito Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 30px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Nunito Sans
    fontSize: 20px
    fontWeight: '700'
    lineHeight: 26px
    letterSpacing: -0.005em
  headline-sm:
    fontFamily: Nunito Sans
    fontSize: 16px
    fontWeight: '700'
    lineHeight: 22px
  body-lg:
    fontFamily: Nunito Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Nunito Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 21px
  body-sm:
    fontFamily: Nunito Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
  label-lg:
    fontFamily: Nunito Sans
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 18px
  label-md:
    fontFamily: Nunito Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.01em
  caption:
    fontFamily: Nunito Sans
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  gutter-desktop: 1.5rem
  margin: 1rem
  margin-desktop: 2rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
---

## Brand & Style

This design system is tailored for a vocational-school internship (PKL - Praktik Kerja Lapangan) monitoring platform in Indonesia. It balances two key user groups:
1. **Students**: Who require an encouraging, approachable, and delightfully simple mobile-first tool to record daily presence, submit face verification photos, digital signatures, and task logs.
2. **Teachers & School Administrators**: Who need a structured, reliable, and high-clarity desktop-first interface to monitor student placements, track real-time compliance, verify attendance, and manage organizational data.

### Design Movement & Mood
The visual character marries **Soft Neo-Humanist Minimalism** with **Playful Card-Based UI**. Inspired by modern educational mobile experiences, it utilizes warm pastel containers, soft geometric curves, and friendly outline iconography, while strictly anchoring data density with structured borders, reliable typography, and predictable spacing. 

- **Approachable & Trustworthy**: Calm teal and mint tones replace sterile institutional blues, creating an encouraging environment for high school students.
- **Clarity & Productivity**: Tonal pastel card grouping ensures high glanceability on handheld devices, while tabular number alignments and clean hairline dividers provide professional structure for high-density administrative oversight.
- **Defensive Restraint**: Eliminates noisy gradients, excessive drop shadows, or juvenile decorations. It remains a resilient educational productivity system.

## Colors

The color system operates on strict role assignments to maintain contrast, accessibility, and cognitive clarity across varying viewport scales. Pure black (`#000000`) is prohibited across all UI surfaces; deep tinted neutral (`#142A33`) is utilized instead.

### Key Roles
- **Primary (`#0B7C8A`)**: Deep Teal 700. Reserved strictly for dominant CTAs, active bottom navigation highlights, focus outlines, and primary brand markers. Pairs exclusively with white (`#FFFFFF`) for text and icons.
- **Secondary (`#FF9B2F`)**: Warm Orange 500. Applied selectively to secondary highlight actions, attention-guiding status badges, and warm cues. Because of its high luminance, it is paired strictly with deep neutral text (`#142A33`) rather than white.
- **Tertiary (`#20A7B8`)**: Mid Teal 500. Used for supporting decorative accents, icon badge fills, and supportive brand illustration elements.
- **Neutral (`#142A33`)**: Deep Ink Neutral. Used for primary typography, titles, interactive text on bright surfaces, and deep high-contrast structural iconography.

### Tonal Surfaces & Canvas
- **Canvas (`#F7FBFC`)**: Soft cool off-white that prevents eye fatigue across long monitoring sessions. The global page canvas should never use pure `#FFFFFF`.
- **Surface (`#FFFFFF`)**: Strictly for framed cards, modal dialogs, flyout sheets, and elevated input backgrounds.
- **Tonal Pastel Cards**:
  - `primary-soft` (`#BFEAF4`): Student hero status cards, active filters, and subtle teal highlights.
  - `mint-soft` (`#CEF4D8`): Positive confirmation panels and complete status container badges.
  - `yellow-soft` (`#FFF0BD`): Attention warnings, pending verification alerts, and incomplete task warnings.

### Feedback & Status Tokens
- **Success (`#18794E`)**: Paired with `mint-soft` (`#CEF4D8`) for "Lengkap" (complete) tags.
- **Warning (`#A15C00`)**: Paired with `yellow-soft` (`#FFF0BD`) for "Belum Lengkap" (incomplete) tags.
- **Error (`#B42318`)**: Destructive actions, validation alerts, and missing submission warnings.
- **Unstarted (`#5F6F76`)**: Paired with `border-hairline` (`#E3ECEE`) for "Belum Aktivitas" (no activity).

## Typography

The typography system is centered on **Nunito Sans**, chosen for its friendly rounded terminals that make the student experience feel warm and accessible, backed by geometric structural proportions that ensure dense admin tables remain clear and easily scannable.

### Typographic Rules
- **KPI Metrics**: Dashboard metric cards must apply `font-variant-numeric: tabular-nums` to ensure columnar numerical stability during real-time attendance rollups.
- **Hierarchy Ratios**: Asymmetric spacing is strictly enforced above and below section headings—spacing above headings should always be at least double the spacing between the heading and its immediate child text or card.
- **Density Adaptation**: Admin tables, filter dropdowns, and status badges snap to `body-sm`, `label-md`, and `caption`, whereas student cards prioritize larger, more legible touches with `headline-md` and `body-lg`.

## Layout & Spacing

The design system enforces a strict 8px primary grid with a 4px sub-grid for dense UI elements (such as badges, table cells, and compact controls).

### Spacing Scale
- `space-xs` (4px): Sub-grid spacing, icon-to-label gaps inside pill badges, micro offsets.
- `space-sm` (8px): Inline chip spacing, table cell vertical padding, compact form element gaps.
- `space-md` (16px): Standard internal card padding on mobile, default layout gutter, component spacing.
- `space-lg` (24px): Standard internal card padding on desktop, section row gaps, modal internal padding.
- `space-xl` (32px): Major layout block division on dashboards, hero card inner padding.
- Extended step tokens: `48px`, `64px`, and `96px` are reserved strictly for layout macro-spacing, hero headers, and page-level top margins.

### Grid & Form Factors
The design system operates on an asymmetrical layout approach:
- **Mobile (< 768px)**: 4-column fluid layout with 16px margins and 16px gutters. The student interface is designed mobile-first. When rendered on larger screens (tablet/desktop), the student experience is constrained to a centered app frame with a `max-width` of 480px to 560px.
- **Tablet (768px – 1023px)**: 8-column fluid layout with 24px margins. Navigation switches from a bottom bar to an icon-rail sidebar.
- **Desktop (≥ 1024px)**: 12-column fixed-max layout with a maximum container width of `1440px`, 32px canvas margins, and 24px column gutters. Used for the school teacher and admin monitoring console.

## Elevation & Depth

This design system eschews multi-layered blurry shadows and dark skeuomorphic drops. Instead, depth and visual separation are generated through **Tonal Surface Layering** and **Hairline Outlines**.

### Elevation Hierarchy
1. **Level 0 (Canvas)**: Background canvas (`#F7FBFC`) is completely flat with no border and no shadow.
2. **Level 1 (Default Cards & Surfaces)**: All standard component cards, inputs, and tables use:
   - Surface color: `#FFFFFF`
   - Border: `1px solid #E3ECEE`
   - Ambient Shadow: `0 1px 2px rgba(20, 42, 51, 0.05)`
3. **Level 2 (Active & Hover Cards)**: On desktop hover or active selection, elevation rises delicately without dramatic blur:
   - Shadow: `0 4px 12px rgba(20, 42, 51, 0.08)`
   - Border color shifts to `#20A7B8` (Teal 500) or stays `#E3ECEE` depending on interactivity.
4. **Level 3 (Overlays & Sheets)**: Mobile sheets, dropdowns, and confirmation modals:
   - Surface color: `#FFFFFF`
   - Shadow: `0 12px 32px rgba(20, 42, 51, 0.12)`
   - Scrim backdrop: `rgba(20, 42, 51, 0.4)` with 4px backdrop blur.

### Focus States
Focus indicators are essential for accessibility across forms and table interactions. The focus ring is a visible 2px solid line in Primary Teal (`#0B7C8A`) with a 2px offset, ensuring a contrast ratio ≥ 3:1 against any adjacent surface.

## Shapes

The shape system expresses friendly accessibility through generous corner radii that soften data-dense educational workflows.

### Corner Radius Standards
- **Radius SM (8px)**: Small action chips, dense data table badges, media thumbnails, and inline input tags.
- **Radius MD (12px)**: Primary action buttons, text input fields, signature pad frame containers, and dropdown menus.
- **Radius LG (16px)**: Standard default cards, monitoring data tables, and camera preview viewfinders.
- **Radius XL (24px)**: Hero status cards, mobile bottom sheets, and student daily summary feature cards.
- **Radius Full (9999px)**: Status pill badges, circular camera capture triggers, and user profile avatar frames.

### Nested Geometry Formula
To maintain visual harmony when components are nested, inner elements must calculate their corner radius relative to the container:
$$\text{Inner Radius} = \max(0, \text{Outer Radius} - \text{Padding})$$
For example, an inner container inside a 16px radius card with 8px internal padding must use an 8px radius.

## Components

### 1. Buttons
- **Touch Target**: Minimum `44px × 44px` interactive area across all devices.
- **Primary Button**: Faux flat surface in `#0B7C8A` (Teal 700) with white text, font weight 700, 12px border radius. Hover state shifts to `#08636E` with 150ms transition.
- **Secondary / Accent Button**: Warm Orange `#FF9B2F` fill paired strictly with `#142A33` deep ink typography, 12px radius.
- **Soft Button**: `#BFEAF4` (Cyan 100) fill with `#0B7C8A` text.
- **Outline Button**: `#FFFFFF` background, `1px solid #E3ECEE` border, `#142A33` text. Hover adds subtle `rgba(20, 42, 51, 0.03)` tint.

### 2. Cards
- **Base Card**: Never fixed-height when holding variable dynamic text. Background `#FFFFFF`, border `1px solid #E3ECEE`, radius `16px`, shadow `0 1px 2px rgba(20, 42, 51, 0.05)`.
- **Tonal Feature Cards**:
  - Hero Status Card: Light Cyan (`#BFEAF4`) or Mint (`#CEF4D8`) background, radius `20px` to `24px`, padding `20px` to `24px`.
- **Action Card**: Interactive cards include subtle active compression (`transform: scale(0.99)`) on tap/click.

### 3. Status Badges & Chips
- **Structure**: Always pill-shaped (`border-radius: 9999px`), padding `4px 10px`, typography `label-md` (`12px`, weight 600).
- **Icon Requirement**: State must never be conveyed via color alone. Every badge requires a leading 14px icon + text label.
  - **Lengkap (Complete)**: Background `#CEF4D8`, border `#CEF4D8`, text `#18794E`, icon: checkmark circle.
  - **Belum Lengkap (Incomplete)**: Background `#FFF0BD`, border `#FFF0BD`, text `#A15C00`, icon: clock or alert circle.
  - **Belum Aktivitas (Unstarted)**: Background `#E3ECEE`, border `#E3ECEE`, text `#5F6F76`, icon: minus circle.

### 4. Input Fields & Form Controls
- **Text Inputs**: Minimum height `44px`, background `#FFFFFF`, border `1px solid #E3ECEE`, radius `12px`, padding `10px 14px`.
- **Labels**: Always top-aligned and persistently visible above the input (`label-lg`, `#142A33`).
- **Signature Canvas Area**: Pure white pad enclosed in `1px solid #E3ECEE` with radius `12px`, accompanied by clear instruction text and a secondary "Hapus" (Clear) button.
- **Camera Frame**: 4:3 aspect ratio viewfinder with rounded corners (`16px`), high-contrast shutter button (`9999px` circle with 64px diameter).

### 5. Data Tables (Admin Monitoring)
- **Header**: Sticky `top: 0`, background `#F7FBFC`, bottom border `1px solid #E3ECEE`, typography `label-md` uppercase.
- **Rows**: Auto-height with minimum height `52px`, hover background `rgba(191, 234, 244, 0.15)` (ultra-soft cyan).
- **Responsive Fallback**: On viewports `< 768px`, data tables automatically reflow into stacked cards containing student avatar, name, and key status badges.

### 6. Navigation
- **Student (Mobile)**: Fixed bottom navigation bar with 5 items (Home, Absensi, Laporan, Riwayat, Profil). Height `64px`, background `#FFFFFF`, top border `1px solid #E3ECEE`. Active icon & label highlight in `#0B7C8A`.
- **School (Desktop)**: Collapsible left sidebar (width `260px` expanding, `72px` collapsed rail on tablet), with soft hover pills and rounded active indicator states.