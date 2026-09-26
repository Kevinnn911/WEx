---
name: PKL Monitoring Design System
description: Friendly, trustworthy monitoring interface for PKL students and school staff, adapted from a soft playful mobile card UI.
colors:
  primitive:
    teal-700: "#0B7C8A"
    teal-500: "#20A7B8"
    cyan-100: "#BFEAF4"
    mint-100: "#CEF4D8"
    yellow-100: "#FFF0BD"
    orange-500: "#FF9B2F"
    orange-700: "#B85C00"
    neutral-950: "#142A33"
    neutral-600: "#5F6F76"
    neutral-200: "#E3ECEE"
    neutral-050: "#F7FBFC"
    white: "#FFFFFF"
    success-700: "#18794E"
    warning-700: "#A15C00"
    error-700: "#B42318"
  semantic:
    primary: "#0B7C8A"
    on-primary: "#FFFFFF"
    primary-soft: "#BFEAF4"
    accent: "#FF9B2F"
    on-accent: "#142A33"
    background: "#F7FBFC"
    surface: "#FFFFFF"
    text-primary: "#142A33"
    text-secondary: "#5F6F76"
    border: "#E3ECEE"
    success: "#18794E"
    warning: "#A15C00"
    error: "#B42318"
  component:
    button-primary-bg: "{colors.semantic.primary}"
    button-primary-fg: "{colors.semantic.on-primary}"
    card-bg: "{colors.semantic.surface}"
    card-border: "{colors.semantic.border}"
    input-bg: "{colors.semantic.surface}"
    input-border: "{colors.semantic.border}"
    nav-active: "{colors.semantic.primary}"
    badge-success: "{colors.semantic.success}"
    badge-warning: "{colors.semantic.warning}"
    badge-error: "{colors.semantic.error}"
typography:
  h1:
    fontFamily: "Nunito Sans"
    fontSize: "2rem"
    fontWeight: 800
    lineHeight: 1.2
    letterSpacing: "-0.01em"
  h2:
    fontFamily: "Nunito Sans"
    fontSize: "1.5rem"
    fontWeight: 700
    lineHeight: 1.25
  h3:
    fontFamily: "Nunito Sans"
    fontSize: "1.125rem"
    fontWeight: 700
    lineHeight: 1.3
  body-md:
    fontFamily: "Nunito Sans"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.5
  body-sm:
    fontFamily: "Nunito Sans"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.5
  label-sm:
    fontFamily: "Nunito Sans"
    fontSize: "0.75rem"
    fontWeight: 600
    lineHeight: 1.4
rounded:
  sm: 8px
  md: 12px
  lg: 16px
  xl: 24px
  full: 9999px
spacing:
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  3xl: 64px
  4xl: 96px
components:
  button-primary:
    backgroundColor: "{colors.semantic.primary}"
    textColor: "{colors.semantic.on-primary}"
    rounded: "{rounded.md}"
    minHeight: "44px"
    padding: "12px 20px"
  button-accent:
    backgroundColor: "{colors.semantic.accent}"
    textColor: "{colors.semantic.on-accent}"
    rounded: "{rounded.md}"
    minHeight: "44px"
    padding: "12px 20px"
  card:
    backgroundColor: "{colors.semantic.surface}"
    border: "1px solid {colors.semantic.border}"
    rounded: "{rounded.lg}"
    padding: "{spacing.lg}"
  input:
    backgroundColor: "{colors.semantic.surface}"
    textColor: "{colors.semantic.text-primary}"
    border: "1px solid {colors.semantic.border}"
    rounded: "{rounded.md}"
    minHeight: "44px"
    padding: "10px 14px"
---

# Overview

PKL Monitoring menggunakan visual yang friendly dan approachable untuk siswa, namun tetap trustworthy dan produktif untuk guru/admin. Area siswa mengambil karakter soft-card, pastel cyan/mint/yellow, rounded corners, dan ilustrasi/icon sederhana. Area sekolah memakai token yang sama dengan density lebih tinggi agar tabel monitoring tetap efisien.

# Colors

Gunakan arsitektur token 3-tier: primitive → semantic → component.

- `primary` hanya untuk CTA utama, active navigation, focus/selected state, dan key brand accent.
- `primary-soft` untuk background hero/status non-kritis.
- `accent` digunakan secara hemat untuk action sekunder penting dan highlight hangat.
- Orange terang selalu memakai dark text; jangan white text kecil pada orange terang.
- Success/warning/error harus selalu disertai icon dan label teks.
- Canvas utama memakai off-white `#F7FBFC`; pure white hanya untuk surface/card yang terbingkai.

# Typography

Gunakan Nunito Sans atau rounded humanist/geometric sans yang sangat dekat.

- Heading 700–800 untuk friendly confidence.
- Body 400–500 agar tetap mudah dibaca.
- KPI memakai tabular figures bila font mendukung.
- Display tidak boleh terlalu besar; ini adalah product UI, bukan marketing landing page.

# Spacing & Layout

Gunakan scale `4 / 8 / 16 / 24 / 32 / 48 / 64 / 96`.

- Student mobile UI default base 8px.
- Admin dense UI boleh memakai 4px sub-grid.
- Desktop 12-column, tablet 8-column, mobile 4-column.
- Student desktop container dibatasi sekitar 480–560px.
- School desktop max content width sekitar 1440px.
- Heading spacing asymmetric: space above lebih besar daripada space below.

# Shapes

Radius bersifat proporsional:

- Inputs/buttons: 8–12px.
- Cards: 16px.
- Hero/feature card: sampai 24px.
- Badges/avatar: full pill/circle.

Untuk nested component, usahakan radius inner mengikuti `max(0, outer radius - padding)` secara visual.

# Elevation & Depth

Flat/tonal first.

Default card:

```css
border: 1px solid #E3ECEE;
box-shadow: 0 1px 2px rgba(20, 42, 51, 0.05);
```

Hover desktop boleh naik menjadi shadow ringan, bukan dramatic floating card.

# Components

## Buttons

- Min touch target 44×44px.
- Primary button menggunakan `#0B7C8A` + white.
- Accent button menggunakan orange + dark text.
- Focus ring 2px primary dengan offset 2px.
- Disabled state tetap terbaca dan tidak hanya mengurangi opacity ekstrem.

## Cards

- Height auto + consistent padding.
- Jangan memotong konten dengan fixed height.
- Gunakan border ringan + tonal surface sebelum shadow.

## Inputs

- Visible labels.
- Helper/error text dekat field.
- Minimum height 44px.

## Status Badges

- Full pill.
- Icon + text.
- Jangan hanya color dot.

## Navigation

Student:
- Bottom navigation 5 item pada mobile.
- Active item memakai primary teal.

School:
- Sidebar desktop.
- Collapsible icon rail pada tablet.
- Drawer pada mobile.

## Tables

- Sticky header bila list panjang.
- Row height auto dengan min-height.
- Filter area dapat wrap.
- Pada mobile prioritaskan card list/detail daripada memaksa semua kolom tetap terlihat.

# Interaction States

- Hover: 150–200ms ease-out, color/elevation shift ringan.
- Pressed: transform sangat kecil atau tonal darkening; jangan bounce berlebihan.
- Focus: visible 2px ring.
- Loading: skeleton.
- Empty: explanatory empty state.
- Error: inline message + retry bila sesuai.
- Success: toast untuk CRUD, strong success card untuk submit absensi/laporan.

# Accessibility

- WCAG AA minimum.
- Body text ≥ 4.5:1.
- Large text ≥ 3:1.
- UI components/focus indicator ≥ 3:1.
- Touch target ≥ 44×44px.
- Semantic HTML.
- Keyboard-usable admin dashboard.
- Status tidak bergantung pada warna.

# Responsive

Breakpoints:

```text
sm 640
md 768
lg 1024
xl 1280
2xl 1536
```

Grid:

```text
Desktop 12
Tablet 8
Mobile 4
```

# Rules to Never Break

- Never use random spacing outside 4/8px rhythm.
- Never use `#000000` as primary text or `#FFFFFF` as full-page canvas.
- Never use bright orange with small white body text.
- Never communicate state using color only.
- Never give content-variable cards/list rows a rigid fixed height.
- Never hide keyboard focus.
- Never use decorative heavy shadows that conflict with the soft reference style.
- Always keep one dominant primary CTA per screen.
- Always preserve the same semantic token roles across student and school interfaces.
