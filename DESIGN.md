---
name: pdmlaranew (BAN-PDM Jawa Timur)
description: School accreditation management dashboard & landing page, built on the Trezo Bootstrap 5 admin template.
colors:
  indigo-violet: "#605DFF"
  indigo-violet-deep: "#4936F5"
  indigo-violet-deeper: "#3225AE"
  indigo-violet-tint: "#DDE4FF"
  slate-ink: "#3A4252"
  slate-ink-deep: "#23272E"
  sky: "#2DB6F5"
  lime: "#37D80A"
  lime-soft: "#D8FFC8"
  amber: "#FFBC2B"
  ember-orange: "#FD5812"
  ember-orange-soft: "#FFE1DD"
  slate-body: "#64748B"
  slate-body-muted: "#8695AA"
  slate-body-deep: "#526077"
  slate-header: "#445164"
  white: "#ffffff"
  light: "#D5D9E2"
  light-soft: "#F1F0F3"
  border: "#ECEEF2"
  border-tint: "#ECF0FF"
  page-bg: "#F6F7F9"
  dark-bg: "#0A0E19"
  dark-card: "#0C1427"
  dark-input: "#15203c"
  dark-border: "#172036"
  # Soft UI ramp (asesor buttons & badges)
  indigo-violet-light: "#7B79FF"
  indigo-violet-lightest: "#8A88FF"
  indigo-violet-soft: "#6A68FF"
  lime-light: "#4FE30F"
  lime-lightest: "#5CE81F"
  lime-soft: "#3EDB0F"
  slate-soft: "#ECEFF4"
  success-ink: "#1E7A00"
  danger-ink: "#C43805"
  warning-soft: "#FFF4D6"
  warning-ink: "#8A5C00"
  info-soft: "#DDF3FE"
  info-ink: "#0E7DB2"
  shadow-elevated: "rgba(100, 100, 111, 0.25)"
  shadow-chip: "rgba(100, 100, 111, 0.14)"
  # Soft UI 3 DataTable (admin)
  datatable-header-start: "#F7F8FF"
  datatable-header-end: "#EEF1FF"
  datatable-header-border: "#E2E7F6"
  datatable-control-border: "#E4E7F1"
  datatable-paging-disabled: "#B7BECB"
typography:
  display:
    fontFamily: "Inter, sans-serif"
    fontSize: "2.2rem"
    fontWeight: 700
    lineHeight: 1.2
  headline:
    fontFamily: "Inter, sans-serif"
    fontSize: "2.024rem"
    fontWeight: 700
  title:
    fontFamily: "Inter, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 700
  body:
    fontFamily: "Inter, sans-serif"
    fontSize: "0.88rem"
    fontWeight: 400
    lineHeight: "26px"
  label:
    fontFamily: "Inter, sans-serif"
    fontSize: "14px"
    fontWeight: 500
  datatable:
    fontFamily: "Inter, sans-serif"
    fontSize: "0.8125rem"
  datatable-header:
    fontFamily: "Inter, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 700
rounded:
  sm: "4px"
  md: "7px"
  lg: "8px"
  soft: "12px"
  toast: "14px"
  datatable-control: "10px"
  datatable-paging: "9px"
  pill: "50px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
components:
  button-primary:
    backgroundColor: "{colors.indigo-violet}"
    textColor: "#ffffff"
    rounded: "{rounded.sm}"
    padding: "8px 16px"
  button-primary-hover:
    backgroundColor: "{colors.indigo-violet-deep}"
    textColor: "#ffffff"
    rounded: "{rounded.sm}"
  input:
    backgroundColor: "#ffffff"
    textColor: "{colors.slate-ink}"
    rounded: "{rounded.md}"
    height: "50px"
    padding: "14px 16px"
  card:
    backgroundColor: "#ffffff"
    textColor: "{colors.slate-body}"
    rounded: "{rounded.lg}"
---

# Design System: pdmlaranew (BAN-PDM Jawa Timur)

## Overview

BAN-PDM Jawa Timur is a school-accreditation management system (admin, assessor/`asesor`, end-user, and public landing page) built on the **Trezo** Bootstrap 5 admin template. The system's visual world is a light, high-density operating dashboard with an indigo-violet accent, slate-blue text, and a matching dark mode. The design source of truth lives in the SCSS files under `public/assets/scss/` (`_variables.scss` and the `components/*.scss` partials); Blade views consume it through Bootstrap utility and component classes.

**Key Characteristics:**
- Light surfaces (`#ffffff` cards on a `#F6F7F9` page background) with a full dark-mode counterpart.
- One accent — indigo-violet `#605DFF` — used for primary actions, active navigation, and input focus.
- Inter as the single typeface; headings are bold (`700`) and dark slate, body text is muted slate.
- Bootstrap 5 as the layout/component system; Tailwind is configured but is not the source of truth.
- Rounded rectangles everywhere; radii stay small (4px–8px) with pills (50px) reserved for avatars and status chips.
- Slow, uniform motion (`all ease 0.5s`).
- Two icon families split by surface: **Remix** (`ri-*`) for navigation, auth, and the public landing; **Material Symbols** (`material-symbols-outlined`) for content and data views.

## Colors

The palette is defined in `_variables.scss` as Bootstrap SCSS variables and emitted as `$theme-colors` (primary, secondary, semantic states, plus custom `*-50/60/70` shades) so every color is available as a Bootstrap utility (`bg-primary`, `text-danger`, `border-*`).

### Primary
- **Indigo Violet** (`#605DFF`): Primary actions, active sidebar/nav links, input focus border, accent glows. Rare and deliberate.
- **Indigo Violet Deep** (`#4936F5`): Hover/darker primary.
- **Indigo Violet Deeper** (`#3225AE`): Pressed/strongest primary shade.
- **Indigo Violet Tint** (`#DDE4FF`): Light primary wash for subtle backgrounds.

### Secondary
- **Slate Ink** (`#3A4252`): Heading and strong-text color.
- **Slate Ink Deep** (`#23272E`): Darkest heading/ink.

### Tertiary / Semantic
- **Sky** (`#2DB6F5`): Informational states.
- **Lime** (`#37D80A`): Success; paired with soft `#D8FFC8` for success chips.
- **Amber** (`#FFBC2B`): Warning.
- **Ember Orange** (`#FD5812`): Danger/errors; paired with soft `#FFE1DD` for danger chips.

### Neutral
- **Slate Body** (`#64748B`): Default body text.
- **Slate Body Muted** (`#8695AA`): Secondary/muted text.
- **Slate Body Deep** (`#526077`): Slightly darker text.
- **Light** (`#D5D9E2`): Input borders and light strokes.
- **Light Soft** (`#F1F0F3`): Light fill.
- **Border** (`#ECEEF2`): Table and card borders.
- **Border Tint** (`#ECF0FF`): Active/hover border wash (submenu, striped table header).
- **Page BG** (`#F6F7F9`): App background and table header fill.

### Dark Mode
- **Dark BG** (`#0A0E19`): Dark page background.
- **Dark Card** (`#0C1427`): Dark card/raised surface.
- **Dark Input** (`#15203c`): Dark input background.
- **Dark Border** (`#172036`): Dark mode border.

### Named Rules
**The One Accent Rule.** Indigo Violet `#605DFF` is the only accent for actions and active states; semantic colors (success/warning/danger/info) are reserved for status and feedback, never for primary actions.

## Typography

**Display / Body / Label Font:** Inter (with `sans-serif` fallback), loaded via Google Fonts (`wght 100..900`).

**Character:** A single neutral grotesque used at small sizes to fit a dense operating dashboard; hierarchy comes from weight (`700` headings) and size, not from mixing typefaces.

### Hierarchy
- **Display / H1** (`700`, `2.2rem` ≈ 35.2px): Page/section hero headings. Color `#3A4252`.
- **Headline / H2** (`700`, `2.024rem` ≈ 32.4px): Major section titles.
- **Title / H3** (`700`, `18px` — the SCSS scale is overridden to `18px` in `_global.scss`): Card and section titles.
- **Body** (`400`, `0.88rem` ≈ 14.08px, `26px` line-height): Default copy. Color `#64748B`.
- **Label** (`500`, `14px`): Field labels and small headings.

## Layout

Bootstrap 5 grid and utilities are the layout system. The app shell is a fixed 260px left sidebar (`.sidebar-area`) plus a fluid main content area wrapped in a Turbo Frame (`#main_frame`) for SPA-style navigation. Containers, gutters, and the spacing scale follow Bootstrap defaults (4px base: `4 / 8 / 12 / 16 / 24 / 32 / 48px`). Density is high: tables are the dominant surface, with 50px-tall inputs and compact cards.

## Elevation & Depth

Depth is tonal and subtle. Surfaces are flat at rest (white cards on a `#F6F7F9` page); the single box-shadow is soft and used sparingly.

### Shadow Vocabulary
- **Default** (`rgba(100,100,111,0.2) 0px 7px 29px 0px`): Cards and floating elements.
- **Soft** (`0px 3px 4px 0px rgba(0,0,0,0.05)`): Very low relief.
- **Small** (`0px 2px 4px 0px rgba(0,0,0,0.1)`): Light lift for small controls.
- **Primary Glow** (`0px 4px 4px 0px rgba(101,96,240,0.10)`): Soft accent glow under primary elements.

## Shapes

Rounded-corner form language with tight, consistent radii:
- **4px** — buttons, small controls, action icons.
- **7px** — inputs and form fields.
- **8px** — cards and table container corners.
- **12px** — soft-ui buttons, pill controls, and sidebar menu links on asesor + admin surfaces.
- **14px** — soft-ui toasts.
- **50px / 50%** — pills, avatars, status chips, and progress/scroll handles.

Borders are 1px light strokes (`#D5D9E2` on inputs, `#ECEEF2` on tables/cards); focus is shown by border-color shift to `#605DFF`, not by a shadow ring.

## Components

### Buttons
- **Shape:** 4px radius, padding `8px 16px`, transition `all ease 0.5s`.
- **Primary:** background `#605DFF`, white text. Hover darkens toward `#4936F5`.
- **Danger:** background `#FD5812`, white text.
- **Subtle variants:** `bg-opacity-10` buttons are tinted at rest and fill solid on hover.
- **Action buttons:** borderless icon buttons; inline SVG 20px with `#8F9DBD` stroke.
- **Soft UI (asesor + admin):** on `body[data-route-group="asesor"]`/`body[data-route-group="admin"]` buttons get `12px` radius with a soft-elevation lift. Primary uses an indigo-violet gradient (`#7B79FF → #6A68FF`, hover `#8A88FF → #6A68FF`) with a `#605DFF` glow and a top sheen; success uses a lime gradient (`#4FE30F → #3EDB0F`, hover `#5CE81F → #3EDB0F`). Neutral, outline, and solid semantic buttons (secondary/danger/warning/info) keep their tone and receive only a quiet `shadow-elevated` lift.

### Badges / Status Chips
- **Soft UI (asesor + admin):** status badges are soft pastel pills — tinted background with deep ink text: primary `#DDE4FF`/`#4936F5`, success `#D8FFC8`/`#1E7A00`, danger `#FFE1DD`/`#C43805`, warning `#FFF4D6`/`#8A5C00`, secondary/light `#ECEFF4`/`#526077`, info `#DDF3FE`/`#0E7DB2`. No border; soft `shadow-chip` elevation.

### Toasts
- **Soft UI (asesor + admin):** Bootstrap `.toast` notifications become rounded (`14px`) soft pastel cards using the same pill palette as badges — tinted background with deep ink text and a soft elevated shadow (`shadow-elevated`). The close control drops its white invert filter so it stays visible on pastel backgrounds.

### Sidebar Menu
- **Soft UI (asesor + admin):** `.menu-link` gets a `12px` radius with a soft transition. Hover is a quiet `#F6F7F9` wash; the active link becomes a soft indigo-violet gradient (`#ECF0FF → #DDE4FF`) with `#4936F5` ink and a `#605DFF`-tinted soft shadow. Submenu active links use the same gradient and a filled `#4936F5` bullet.

### Cards / Containers
- **Corner Style:** 8px radius (`rounded-3`).
- **Background:** white (light mode), `#0C1427` (dark mode).
- **Shadow Strategy:** flat at rest; see Elevation.
- **Border:** 1px `#ECEEF2` when a border is used.

### Inputs / Fields
- **Style:** white background, 1px `#D5D9E2` border, 7px radius, height 50px, padding `14px 16px`, font 15px.
- **Focus:** border shifts to `#605DFF`, no box-shadow ring.
- **Placeholder:** `#64748B` at 14px.
- **Error / Disabled:** handled via Bootstrap validation classes (danger `#FD5812`).

### Navigation (Sidebar)
- **Style:** 260px wide; menu links 14px, color `#3A4252`.
- **Active:** background `#605DFF`, white text.
- **Hover:** background `#605DFF`, white text.
- **Submenu active/hover:** `#ECF0FF` wash with primary-tinted text.
- **Status chips:** success `#D8FFC8`/`#25B003`, danger `#FFE8D4`/`#FD5812`.

### Tables (DataTables)
- **Header:** background `#F6F7F9`, text `#445164`, 12px, top corners 8px.
- **Body:** text `#3A4252`, 1px `#ECEEF2` row borders.
- **Striped variant:** header background `#ECF0FF`, 14px.
- **Soft UI 3 (admin tables):** applied automatically to every admin route via `body[data-route-group="admin"]` (no per-view opt-in), covering DataTables and plain `.table`s. Header becomes a soft indigo-violet wash (`#F7F8FF → #EEF1FF`) with `#445164` ink and a `#E2E7F6` rule; rows keep `#3A4252` text on `#ECEEF2` separators. Native DataTables row states are re-themed through CSS variables — stripe `#F6F7F9`, hover `#ECF0FF`, selected `#605DFF`. Search/length controls use a `10px` radius with a `#E4E7F1` border and a `#605DFF` focus ring; pagination becomes `9px` soft pills (`32px` tall) with an indigo-violet gradient current page (`#7B79FF → #6A68FF`), `#ECF0FF` hover, and `#B7BECB` disabled state.

### Icons
- **Navigation / chrome** (sidebar, header, theme settings, profile) and **auth** use Remix line icons (`ri-*`).
- **Public landing** (navbar, public attendance) also uses Remix.
- **Content and data views** (dashboards, tables, lists) use Material Symbols outlined (`material-symbols-outlined`).
- **Action icons in tables:** borderless icon-only links/buttons (Material Symbols) get a `9px` soft rounded hover — an `#ECF0FF` fill with a `#605DFF`-tinted lift — on asesor + admin surfaces.
- **Filled glyphs** (e.g. rating stars) use Material Symbols with `font-variation-settings:'FILL' 1`.
- **Feather is retired** — no `data-feather` attributes remain in views.

## Do's and Don'ts

### Do:
- **Do** source colors from `_variables.scss` and consume them via Bootstrap theme-color utilities (`bg-primary`, `text-danger`), so dark mode keeps working.
- **Do** use Inter for all text; build hierarchy with weight (`700`) and the heading scale, not new fonts.
- **Do** keep radii at 4px / 7px / 8px, and reserve 50px pills for avatars and status chips.
- **Do** show focus by switching border color to `#605DFF` (no shadow ring), matching the input pattern.
- **Do** keep motion at the system transition (`all ease 0.5s`).
- **Do** keep the icon split: Remix in navigation, auth, and the public landing; Material Symbols in content and data views.

### Don't:
- **Don't** introduce a second accent color for primary actions; keep `#605DFF` as the single action accent.
- **Don't** use Tailwind utilities for layout or color — Bootstrap is the source of truth; Tailwind is present but not authoritative.
- **Don't** mix icon families within a single surface — Remix for navigation, auth, and the public landing; Material Symbols for content and data views; Feather is retired.
- **Don't** use shadows for focus indication; the system communicates focus via border color.
