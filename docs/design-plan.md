# Design plan: AYESHA Movers & Packers

Phase 2 deliverable. No theme code yet. Everything here is a plan for Phases 3–7 and can change on your feedback.

## 0. Brief, as I understand it

- **Subject:** a Bahrain crew that moves houses, villas, flats and offices; packs; dismantles and refits furniture; removes and refits ACs, TVs and curtains; rents out Dyna and 6-wheel trucks; and loads 20ft/40ft containers for KSA, the GCC, the UK, the USA and Canada. Open 24 hours.
- **Audience:** expats and families in Bahrain and KSA who are moving house, plus small offices. Mostly on a phone, often arriving from a classified ad or Instagram, often in a hurry and mid-move.
- **Primary job:** one tap to WhatsApp or call. Second job: a detailed quote request for people who want a written price.

### What the client photos tell us

Only the photos not tagged DO NOT USE were reviewed (IDs 9, 10, 12, 14, 15).

| ID | What it shows | Design use |
|---|---|---|
| 9 | Yellow box truck with a teal cab and yellow/black reflective chevron tape, parked in front of a Gulf apartment block | The **source of the palette** (box yellow, cab teal, chevron tape). Home hero photo, optional: alt text and caption make no ownership claim, and the hero must work without it (see section 4). |
| 15 | Dark box truck, man in a blue shirt standing in front, villa street | About Us. Alt text must not claim the man is the GM or that the truck is AYESHA's until the client confirms. |
| 14 | Red curtain-side truck (has a manufacturer model name on the cab) | Our Services, transport section. Crop so the cab lettering is not the focus. |
| 12 | Small pickup loaded high with boxes and furniture, palm trees | Our Services, truck hire / small moves. The most "real job" photo we have. |
| 10 | Two movers in red/blue overalls lifting a white sofa | **Placeholder only** (looks like stock). Use nowhere prominent; replace before go-live. |

Two constraints from the photos:
1. **They are small** (640 px wide originals). A full-bleed desktop hero photo would look soft. The design must not depend on a big photograph; the hero is typographic and the photo sits at its natural size.
2. **They are daylight street photos of real trucks on real Gulf streets.** That is more convincing than any stock image, so photos are shown as plain, uncropped-feeling evidence (no duotones, no overlays, no text on top of them).

---

## 1. Colour tokens

Every colour comes from truck 9: the yellow box body, the teal cab, the grey street, and the white facade. WhatsApp green is the one colour taken from outside, because people recognise it as "this opens WhatsApp".

| Token | Hex | Taken from | Role |
|---|---|---|---|
| `box-yellow` | `#F2B705` | Yellow box body | Hero panel, the chevron strip, CTA band. Never text on white. |
| `cab-teal` | `#0F4D4A` | Teal cab | **All text** (headings and body), header, footer, sticky bar Call half. There is no black in the palette. |
| `tarmac` | `#3F6F6B` | Teal in shadow | Secondary text: captions, hints, form help text. |
| `concrete` | `#E8EBE9` | Cool grey street / facade | Alternate section background, form field backgrounds. Deliberately cool, not cream. |
| `paper` | `#FFFFFF` | White facade | Main page background. |
| `chat-green` | `#25D366` | WhatsApp brand | WhatsApp buttons only. Always with `cab-teal` text, never white text. |

### Contrast (WCAG 2.1, computed)

| Text on background | Ratio | AA normal (4.5) | AA large (3.0) | Used for |
|---|---|---|---|---|
| cab-teal on paper | **9.63** | Pass | Pass | Body text, headings |
| cab-teal on concrete | **8.02** | Pass | Pass | Body text in alternate sections, form fields |
| cab-teal on box-yellow | **5.30** | Pass | Pass | Hero headline, number, CTA band text |
| box-yellow on cab-teal | **5.30** | Pass | Pass | Highlighted number in footer, header logo mark |
| concrete on cab-teal | **8.02** | Pass | Pass | Footer body text |
| paper on cab-teal | 9.63 | Pass | Pass | Call button text, header text |
| cab-teal on chat-green | **4.86** | Pass | Pass | WhatsApp button label (bold) |
| tarmac on paper | **5.68** | Pass | Pass | Captions, help text |
| tarmac on concrete | **4.73** | Pass | Pass | Help text inside form sections |
| chat-green on cab-teal | 4.86 | Pass | Pass | WhatsApp icon in the sticky bar |

**Banned pairs (fail AA):** paper on chat-green (1.98), box-yellow on paper (1.82). These are written into theme.json as rules in Phase 3: the button styles simply never offer them.

Focus ring: 3 px `cab-teal` outline with 2 px `paper` offset on light backgrounds; 3 px `box-yellow` on `cab-teal` backgrounds. Non-text contrast ≥ 3:1 in both cases.

---

## 2. Type

**One family: Archivo (variable, wght 100–900, wdth 62–125), SIL Open Font License, self-hosted as a single Latin-subset woff2.**

Why Archivo: it is a grotesque with a real **width axis**. The display voice is Archivo squeezed to wdth 62–68 at weight 800, which looks like the condensed lettering painted on the side of Gulf trucks and on shipping containers. Body text is the same family at normal width, so one font file covers both voices and the page stays light. No stencil font (the cliché for logistics); the condensed grotesque gives the same "painted on a vehicle" feeling without the costume.

| Role | Settings | Notes |
|---|---|---|
| Display (H1, hero number, CTA band number) | wght 800, wdth 62, line-height 0.95, tracking −0.01em | Sentence case. Phone numbers use `font-variant-numeric: tabular-nums`. |
| Headings (H2, H3) | wght 700, wdth 75, line-height 1.1 | Sentence case. |
| Body | wght 400, wdth 100, line-height 1.55, max 68ch | 17 px mobile / 18 px desktop. |
| UI (buttons, form labels, nav) | wght 600, wdth 100 | Sentence case. No all-caps anywhere, no letter-spaced labels. |

### Type scale

A 1.25 ratio (major third) on mobile, 1.333 (perfect fourth) from 960 px up, built from the body size, with `clamp()` between them. Values in px at 375 px wide → 1280 px wide:

| Step | Use | Mobile | Desktop |
|---|---|---|---|
| −1 | Captions, help text, footer small print | 14 | 15 |
| 0 | Body | 17 | 18 |
| 1 | Lead paragraph, FAQ questions, card titles | 21 | 24 |
| 2 | H3 | 27 | 32 |
| 3 | H2 | 33 | 43 |
| 4 | H1 on inner pages | 41 | 57 |
| 5 | H1 on Home | 52 | 76 |
| Hero number | The painted phone number | fluid, fits the column on 2 lines (≈ 64) | fluid, one line, max 128 (see section 4) |

Rhythm: vertical spacing in multiples of the body line-height (≈ 26 px mobile, 28 px desktop). Section padding 2× on mobile, 3–4× on desktop.

Loading: one `woff2` file (~70–90 KB Latin subset, estimated), `font-display: swap`, preloaded in the head. **Phase 3:** measure the real file size after subsetting; if it's over 100 KB, subset more tightly or limit the axis ranges to those we use (wght 400–800, wdth 62–100). System fallback stack with `size-adjust` tuned to Archivo so the swap does not shift the layout.

---

## 3. Layout concept per page

**Global alignment:** everything is **left aligned** to one left edge (text, buttons, photos), including section headings. Nothing is centred except the two labels inside the sticky mobile bar. Max content width 1200 px; text columns max 68 characters. Desktop uses a 12-column grid with 24 px gutters; mobile is a single column with 16 px side gutters.

The repeated structural device is the **chevron strip**: the yellow/black-teal diagonal reflective tape from the truck's side, as a 12 px band. It appears **exactly twice per page**: under the hero and on top of the footer, like the tape on the bottom edge of a truck body. It is never used as decoration elsewhere.

`[S]` = sticky mobile call/WhatsApp bar, present on every page under 960 px.

### 3.1 Home

Mobile (375 px):

```
┌──────────────────────────────┐
│ AYESHA Movers & Packers  [☎] │  header: wordmark + call icon, menu button
├──────────────────────────────┤
│▓▓▓▓▓▓▓▓ box-yellow ▓▓▓▓▓▓▓▓▓▓│
│ Movers and packers in        │  H1 (step 5)
│ Bahrain, day and night.      │
│                              │
│ +973                         │  the painted number (tap = call)
│ 3444 8236                    │
│                              │
│ Houses, flats, offices.      │  one line of what we do
│ Labour, trucks and carpenters│
│ from one team.               │
│ [ WhatsApp us  ] [ Call ]    │  primary buttons
│ Send a detailed quote request│  text link to /contact-us/#quote
│▚▚▚▚▚▚▚▚▚ chevron strip ▚▚▚▚▚▚│
├──────────────────────────────┤
│ [photo 9: yellow truck]      │  natural size, full column width
│ caption                      │
├──────────────────────────────┤
│ What we do                   │  H2
│ ┌──────────────────────────┐ │
│ │ House, villa, flat and   │ │  the lead service: larger, with what's
│ │ office shifting          │ │  included as a list
│ │ • packing • loading ...  │ │
│ │ Ask about this on WhatsApp│ │
│ └──────────────────────────┘ │
│ Packing and unpacking        │  the other 5: compact rows
│ Furniture dismantling...     │
│ AC, TV and curtain removal   │
│ Trucks by the hour or day    │
│ Container cargo abroad       │
│ See all services             │
├──────────────────────────────┤
│ How a move works             │  H2 (a real sequence, so numbered)
│ 1 Send your list or photos   │
│ 2 Get your price             │
│ 3 We pack and load           │
│ 4 We unload, set up, clear   │
│   the packing debris         │
├──────────────────────────────┤
│ Where we go                  │  service-area route line
│ ● Your door                  │
│ │ Manama and every city      │
│ │ Mina Salman, Khalifa port, │
│ │ airport (list items)       │
│ │ Saudi Arabia               │
│ │ GCC countries              │
│ ● UK, USA, Canada, worldwide │
├──────────────────────────────┤
│ Why people book us           │  4 short statements, no icons, no stats
├──────────────────────────────┤
│ (Reviews: hidden until real) │
├──────────────────────────────┤
│ Questions                    │  FAQ, details/summary
├──────────────────────────────┤
│▓ CTA band (yellow) ▓▓▓▓▓▓▓▓▓▓│  number again + 2 buttons
├▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚┤
│ footer (cab-teal)            │
├──────────────────────────────┤
│ [S]  ☎ Call  │  WhatsApp     │
└──────────────────────────────┘
```

Desktop (1280 px):

```
┌──────────────────────────────────────────────────────────────────┐
│ AYESHA Movers & Packers   Home  About  Services  Contact  [☎ +973 3444 8236] │
├──────────────────────────────────────────────────────────────────┤
│▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ box-yellow ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓│
│ Movers and packers in Bahrain,      │                            │
│ day and night.                      │   [photo 9, 560 px wide,   │
│                                     │    sits on the yellow,     │
│ +973 3444 8236                      │    bottom edge on the      │
│ (cols 1–7, fluid ≤128 px, one line) │    chevron strip]          │
│ Houses, flats, offices...           │                            │
│ [WhatsApp us] [Call]  Send a quote request                       │
│▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚│
├──────────────────────────────────────────────────────────────────┤
│ What we do                                                        │
│ ┌──── lead service (cols 1–7) ────┐  Packing and unpacking       │
│ │ House, villa, flat and office    │  Furniture dismantling...    │
│ │ shifting + included list         │  AC, TV and curtains         │
│ └──────────────────────────────────┘  Trucks / Container cargo    │
├──────────────────────────────────────────────────────────────────┤
│ How a move works   1 ──── 2 ──── 3 ──── 4   (horizontal line)    │
├──────────────────────────────────────────────────────────────────┤
│ Where we go (cols 1–5)        │ route line drawn left→right (7–12)│
├──────────────────────────────────────────────────────────────────┤
│ Why people book us (2×2 text)  │  Questions (FAQ)                 │
├──────────────────────────────────────────────────────────────────┤
│▓ CTA band ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓│
├▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚▚┤
│ footer                                              [WhatsApp ●] │ floating btn
└──────────────────────────────────────────────────────────────────┘
```

### 3.2 About Us

Purpose: show who the people are and how they work. **No founding year and no "years of experience"** (open question).

Mobile:

```
┌──────────────────────────────┐
│ header                       │
├──────────────────────────────┤
│ About AYESHA Movers &        │  H1 (step 4), on paper, no yellow panel
│ Packers                      │  (yellow is kept for Home and CTAs)
│ Lead paragraph: one team for │
│ labour, trucks and carpenters│
├──────────────────────────────┤
│ [photo 15]                   │
├──────────────────────────────┤
│ Who runs it                  │  GM name: Mohammad Ayub Khokhear
│ short paragraph              │  (also trading as AYESHA Cargo Handling)
├──────────────────────────────┤
│ How we work                  │  4 short commitments from the brief:
│ Labour included in the price │  lowest rates with labour, door-to-door,
│ Door to door                 │  responsible service, one team
│ One team, start to finish    │
│ Day or night                 │
├──────────────────────────────┤
│ Where we go (same block as   │  synced pattern
│ Home)                        │
├──────────────────────────────┤
│▓ CTA band ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓│  synced pattern
│ footer / [S]                 │
└──────────────────────────────┘
```

Desktop: H1 + lead in cols 1–7, photo 15 in cols 8–12 aligned to the H1's top. "Who runs it" and "How we work" as two columns (5 + 7). Route line full width.

### 3.3 Our Services

Purpose: the full detail for each of the 6 services, each with its own "Ask about this" WhatsApp link (prefilled with the service name) so people can act from the middle of the page.

Mobile:

```
┌──────────────────────────────┐
│ header                       │
├──────────────────────────────┤
│ Our services                 │  H1
│ Jump to: House, Packing,     │  in-page links (a list), wrap onto lines
│ Furniture, Appliances,       │  (anchor chips, not a carousel)
│ Trucks, Cargo                │
├──────────────────────────────┤
│ House, villa, flat and       │  H2 per service, each with an id
│ office shifting              │
│ paragraph                    │
│ What's included              │  checklist (✓) from client facts
│ ✓ Packing ✓ Loading ...      │
│ [Ask about this on WhatsApp] │
├──────────────────────────────┤  services alternate paper / concrete
│ Packing and unpacking  ...   │
│ Furniture  ...               │
│ Appliance removal and fixing │
│ Trucks by the hour or day    │  photo 12 + photo 14; "8 hours or a full
│  [photo 12]                  │  day"; port + courier depot runs
│ International cargo         │  20ft / 40ft, customs papers, KSA, GCC,
│                              │  UK, USA, Canada
├──────────────────────────────┤
│ Questions (service FAQs)     │
│▓ CTA band ▓ / footer / [S]   │
└──────────────────────────────┘
```

Desktop: a sticky left rail (cols 1–3) with the 6 service links, highlighting the current one; service content in cols 4–10. Photos sit in cols 4–10 at natural size, never wider than 640 px.

### 3.4 Contact Us

Purpose: every way to reach the team, then the quote form. No address and no map until the client provides an address.

Mobile:

```
┌──────────────────────────────┐
│ header                       │
├──────────────────────────────┤
│ Contact us                   │  H1
│ Open 24 hours, every day.    │
├──────────────────────────────┤
│ [WA] WhatsApp +973 3444 8236 │  big tap rows, 56 px tall each,
│ Mobile    +973 3444 8236     │  number in display type
│ Mobile    +973 3642 9850     │
│ Office    +973 7736 0292     │
│ Email     ayeshamoversbh786@ │  (breaks safely on small screens)
│ Instagram @ayesha_movers_... │
├──────────────────────────────┤
│ Request a detailed quote     │  H2, id="quote"
│ Takes about 3 minutes. We    │
│ reply on WhatsApp or by phone│
│ [ form, grouped fieldsets ]  │
├──────────────────────────────┤
│ footer / [S]                 │
└──────────────────────────────┘
```

Desktop: contact rows in cols 1–5 (sticky while the form scrolls), form in cols 6–12.

---

## 4. Home hero concept: the number painted on the truck

**The single memorable element is the phone number, set huge in condensed display type on a box-yellow panel, the way movers in the Gulf paint their number on the side of the truck.**

Why it fits:
- In Bahrain, people find a mover by **calling the number they saw on a truck, in a classified ad or in an Instagram post**. The number is the product. Making it the hero puts the primary job (call or WhatsApp in one tap) in the most visible place, instead of hiding it behind a slogan.
- The **yellow panel with the reflective chevron strip along its bottom edge** is taken from the Gulf moving-truck look in photo 9 (yellow box body, teal cab, reflective tape), not a stock "logistics" look.
- It **works with small photos**. The hero is type and colour, so it is sharp on any screen and loads with no image at all. Photo 9 sits next to it at its true size.
- It is **one memorable thing**. The rest of the page is quiet: paper background, teal text, left aligned, no animation.

Details:
- The whole number is a `tel:` link. Tapping it calls. Under it, two buttons: **WhatsApp us** (chat-green, opens `wa.me/97334448236` with a prefilled message from Business Info settings) and **Call** (cab-teal). Then a text link: **Send a detailed quote request**.
- The number, headline and button labels are **bound to the Business Info settings** (Block Bindings), so changing the number in the settings updates the hero, the sticky bar, the CTA band and the footer at once. The client can still edit the layout and text in the editor.
- **One motion moment on the whole site:** on first load, the chevron strip slides in once from the left (400 ms), like a truck pulling into frame. Off under `prefers-reduced-motion`. No other entrance animations.
- No text on top of the photo, no gradient, no overlay.

Photo 9 ownership (open question for the client):
- Photo 9's **alt text and caption must not say or imply the truck belongs to AYESHA** until the client confirms it is his. Alt text describes only what is visible, e.g. "Yellow box truck with a teal cab parked in front of an apartment building". No caption like "our truck" or "the AYESHA fleet".
- **The hero must look complete with the photo removed**, in case the client says it is not his truck. The photo is a separate Image block in its own column; deleting it lets the text column take the full width with no empty gap, broken grid or orphaned spacing, and the yellow panel, number, buttons and chevron strip still read as a finished hero. Phase 4 tests the hero both with and without the photo.

Hero number sizing:
- The number must **never overflow**: **one line on desktop** (≥ 960 px), **two lines on mobile** ("+973" / "3444 8236", via a controlled break between the country code and the local number, not by wrapping wherever it lands).
- The font size is fluid and based on the **hero text column's width, not the viewport**. The column is a CSS size container (`container-type: inline-size`), and the number uses container query units, e.g. `font-size: clamp(2.75rem, 17cqi, 8rem)` on mobile (sized for the longer half, "3444 8236") and `clamp(3rem, 11.5cqi, 8rem)` on desktop (sized for the whole number on one line). The exact `cqi` values are tuned in Phase 3/4 against the measured width of Archivo wght 800 wdth 62 with tabular figures, with a few percent spare.
- The 128 px in the type scale is a **maximum**, not a fixed size.
- Overflow guard: `white-space: nowrap` on each half plus `max-inline-size: 100%`; if a much longer number is entered in settings, it still fits because the size scales with its container. The test below catches any case where it doesn't.
- **Phase 3/4 tests:** check the hero number at **320, 375, 768, 1280 and 1920 px** wide, with the real number and with a **longer test number (+966 55 123 4567)**. Pass = no horizontal scroll, no clipping, one line at 1280 and 1920, two lines at 320 and 375, and the tap target still covers the whole number.

---

## 5. Components

All components are block patterns built from core blocks so the client can edit them. Shared ones (header, footer, CTA band, service area, sticky bar) are template parts or synced patterns, so one edit changes every page.

| Component | Build | Behaviour and rules |
|---|---|---|
| **Header** | Template part. Wordmark (text, "AYESHA Movers & Packers"; no logo until the client sends one), core Navigation, a Call button bound to the primary number. | Mobile: wordmark + call icon button + menu button (core Navigation overlay). Desktop: inline nav + number shown in full. Not sticky (the sticky bar does that job on mobile). Height 64 px. |
| **Sticky mobile call/WhatsApp bar** | Template part, shown < 960 px only. Two buttons side by side, each 50 % wide, 56 px tall: **Call** (cab-teal, paper text) and **WhatsApp** (chat-green, cab-teal text). | Fixed to the bottom, respects `env(safe-area-inset-bottom)`. Page gets matching bottom padding so the footer is never covered. Hidden when the on-screen keyboard is open in the quote form (so it doesn't cover fields). |
| **Floating WhatsApp button** | Small plugin-rendered element using the settings value. | **Desktop only (≥ 960 px)**, bottom-right, 56 px round, chat-green with teal icon, accessible label "Chat on WhatsApp". On mobile the sticky bar already has WhatsApp, so the floating button is not shown (two WhatsApp buttons on a phone screen would be clutter). |
| **Service card** | Pattern: H3 + paragraph + list + "Ask about this on WhatsApp" link. Two variants: **lead** (larger, concrete background, with list) and **row** (title + one line, no box). | Each card's WhatsApp link has the service name in the prefilled message ("Hi, I'd like a price for furniture dismantling and refitting."). Radius 4 px, no shadow. Cards are not all the same size: hierarchy follows importance. |
| **Process steps** | Pattern: ordered list (`<ol>`), 4 steps. | Numbered because it is a real sequence. Mobile: vertical, numbers in display type at step 2. Desktop: horizontal with a 2 px teal line joining the numbers. |
| **Service-area block** | Synced pattern: a route line (list with a vertical line and stop dots, styled with CSS). | Stops: Your door → Manama and every city in Bahrain → Mina Salman, Khalifa Bin Salman Port, the airport → Saudi Arabia → all GCC countries → UK, USA, Canada and worldwide. Plain list in the markup, so it reads correctly to screen readers and search engines. |
| **FAQ** | Core Details blocks (`<details>/<summary>`). | No JavaScript. Question in step 1, 600 weight; a + that turns into − on open. Answers only use client facts; questions we can't answer yet (e.g. how pricing is calculated) are listed as open questions, not invented. Also output as FAQPage JSON-LD in Phase 7. |
| **Reviews (placeholder)** | Pattern with 3 cards, each clearly labelled "Placeholder: replace with a real customer review", plus a "Show reviews" toggle in the Business Info settings (off by default). | **Hidden on the front end while the toggle is off**, so placeholders never reach visitors. When the client adds real reviews: name, area, date, source (Google/Instagram) and a link to the original. No star graphics unless the source has a star rating. |
| **CTA band** | Synced pattern. Box-yellow, H2 "Moving soon? Message us now.", the number in display type, WhatsApp + Call buttons. | Appears once per page, just above the footer. |
| **Footer** | Template part, cab-teal background with the chevron strip on top. Wordmark, all numbers (bound), email, Instagram, "Open 24 hours", page links, "Also trading as AYESHA Cargo Handling", © year. | No address. Number in box-yellow display type. |
| **Quote form** | Custom block from `ayesha-core` (Phase 6). | See below. |

### Quote form

Grouped into short fieldsets so it feels like 3 small steps on one page (no multi-step JavaScript wizard):

1. **About you:** name*, phone/WhatsApp number* (`type=tel`, Bahrain/KSA format hint), email (optional), "Best way to reply" (WhatsApp / call / email).
2. **Your move:** type of move* (house / villa / flat / office / international cargo / truck hire only / other); moving from (area)*; moving to (area or country)*; preferred date (`type=date`) + "my date is flexible"; property size (studio, 1–5+ bedrooms, villa, office); floor and lift at pickup and drop-off.
3. **What you need:** checkboxes: packing, unpacking, fragile/crockery packing, furniture dismantling and refitting, AC removal/fitting, TV removal/mounting, curtains and blinds, debris removal, 20ft/40ft container, customs documents. Free-text "Big or special items". Truck hire: Dyna or 6-wheel, 8 hours or full day.

Rules: labels above fields (never placeholder-only), 48 px tall inputs, inline errors in plain words ("Enter a phone number so we can reply"), the submit button says **Send quote request**, and the success message says **Quote request sent** and offers a WhatsApp button with the reference number prefilled, so they can send photos straight away. Honeypot field, nonce, rate limit, sanitised input, saved as a private submission and emailed to the client.

---

## 6. How we beat typical Bahrain mover sites

I checked bahrainmovers.com in this phase. gulfmoversbahrain.com did not resolve (DNS error) and bhmoversbahrain.com returned 403, so for those two I'm relying on your description. bahrainmovers.com matched it: an all-caps generic hero ("We make moving easy for you"), "Best movers and packers in Bahrain" repeated, a "100 % satisfaction rate" claim, and no quote form on the home page.

| Typical weakness | What we do instead |
|---|---|
| **Generic "No.1 / Best company" copy** | No superlatives and no claims we can't prove. Copy says what is actually included: labour in the price, carpenters who dismantle and refit, AC and TV removal, Dyna and 6-wheel trucks by 8 hours or a full day, 20/40ft containers, customs papers, 24 hours. Specific beats "best". |
| **No detailed quote form** (or a name/phone/message box) | A form that collects what a mover needs to price a job: from/to, date, size, floors and lifts, services ticked, special items. The client can price faster, and the customer gets a quicker, more accurate answer. Every submission is saved, not only emailed. |
| **Fake-looking reviews** (stock faces, identical five-star quotes) | Reviews are **hidden until real**. Real ones show name, area, date and a link to the source. We'd rather have no reviews section than one that makes people distrust the rest of the page. Photos are real street photos, not stock (the stock-looking one is marked placeholder), and captions make no ownership claim until the client confirms which trucks are his. |
| **Weak mobile UX** (tiny phone links, desktop layouts squeezed down, popups) | Designed for the phone first: the number is the hero, a sticky Call/WhatsApp bar on every page, 48–56 px tap targets, WhatsApp messages prefilled per service, no popups, no carousel, fast (one font file, no jQuery, WebP, lazy-loaded images). |
| **Hard to tell who you're dealing with** | Named General Manager, all real numbers and the email, Instagram link, "also trading as AYESHA Cargo Handling". |
| **Contact info typed by hand on every page** (often out of date) | One Business Info settings page; Block Bindings push the numbers everywhere. |

---

## 7. SEO plan

Site title: **AYESHA Movers & Packers**. Titles ≤ 60 characters, descriptions ≤ 155. The client can edit all of these later (Phase 7 decides whether that's via a small settings field in `ayesha-core` or core features; no SEO plugin without your approval).

| Page | Title tag | Meta description | H1 | Target keywords |
|---|---|---|---|---|
| Home | Movers and Packers in Bahrain, 24 Hours \| AYESHA Movers | House, flat, villa and office shifting across Bahrain, with packing, carpenters and trucks. Cargo to KSA and GCC. Open 24 hours. WhatsApp +973 3444 8236. | Movers and packers in Bahrain, day and night. | movers Bahrain, packers and movers Manama, house shifting Bahrain |
| About Us | About AYESHA Movers & Packers, Bahrain | One team for labour, trucks and carpenters. Door-to-door moves across Bahrain and to KSA, the GCC and worldwide, day or night. | About AYESHA Movers & Packers | movers Bahrain, moving company Bahrain |
| Our Services | House Shifting, Furniture & Cargo Services \| AYESHA | House and office shifting, packing, furniture dismantling and refitting, AC and TV removal, truck hire and container cargo to KSA. | Our services | house shifting Bahrain, furniture dismantling Bahrain, cargo to KSA |
| Contact Us | Contact AYESHA Movers: Call, WhatsApp or Get a Quote | Call or WhatsApp +973 3444 8236, any time, day or night. Or send a detailed quote request for your move in Bahrain or abroad. | Contact us | packers and movers Manama, movers Bahrain contact |

Keyword placement (natural, no stuffing):
- **movers Bahrain / packers and movers Manama:** Home H1 and first paragraph; service-area block ("Manama and every city").
- **house shifting Bahrain:** Services H2 for service 1, Home lead service card.
- **furniture dismantling Bahrain:** Services H2 "Furniture dismantling and refitting", FAQ question.
- **cargo to KSA:** Services H2 "International cargo", service-area stop "Saudi Arabia", FAQ "Can you move my things to Saudi Arabia?".

### JSON-LD (output by `ayesha-core` on every page, values from Business Info settings)

```json
{
  "@context": "https://schema.org",
  "@type": "MovingCompany",
  "@id": "{home_url}#business",
  "name": "AYESHA Movers & Packers",
  "alternateName": "AYESHA Cargo Handling",
  "url": "{home_url}",
  "telephone": "+97334448236",
  "email": "ayeshamoversbh786@gmail.com",
  "image": "{url of photo 9}",
  "employee": {
    "@type": "Person",
    "name": "Mohammad Ayub Khokhear",
    "jobTitle": "General Manager"
  },
  "contactPoint": [
    { "@type": "ContactPoint", "telephone": "+97334448236", "contactType": "customer service" },
    { "@type": "ContactPoint", "telephone": "+97336429850", "contactType": "customer service" },
    { "@type": "ContactPoint", "telephone": "+97377360292", "contactType": "customer service" }
  ],
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
    "opens": "00:00",
    "closes": "23:59"
  },
  "areaServed": [
    { "@type": "Country", "name": "Bahrain" },
    { "@type": "City", "name": "Manama" },
    { "@type": "Country", "name": "Saudi Arabia" },
    { "@type": "Country", "name": "United Arab Emirates" },
    { "@type": "Country", "name": "Kuwait" },
    { "@type": "Country", "name": "Qatar" },
    { "@type": "Country", "name": "Oman" },
    { "@type": "Country", "name": "United Kingdom" },
    { "@type": "Country", "name": "United States" },
    { "@type": "Country", "name": "Canada" }
  ],
  "sameAs": ["https://www.instagram.com/ayesha_movers_packers/"],
  "makesOffer": [ "… one Offer/Service per service, generated from the 6 services …" ]
}
```

- **No `address`** until the client provides one. When the address field in settings is filled in, the plugin adds a `PostalAddress` automatically. Note: Google's local rich results prefer an address, so adding one later will help.
- **No `aggregateRating` or `review`** until there are real reviews.
- **No `foundingDate`** (open question).
- FAQ pages also output `FAQPage` JSON-LD from the Details blocks (Phase 7).

Other SEO basics for Phase 7: one H1 per page, descriptive alt text, `lang="en"`, canonical URLs, XML sitemap (core), Open Graph tags with photo 9, `noindex` stays on until go-live.

---

## 8. Self-review against generic AI-design defaults

I ran the first draft against the skill's list of generic defaults and against "what would I produce for any mover site?".

| Default | First draft | Verdict |
|---|---|---|
| Warm cream background + serif + terracotta | Not used. Background is white with a cool concrete grey. | Clear |
| Near-black + single acid accent | First draft had an `ink` token `#14211F` (a tinted near-black) for body text, with teal only for headings. | **Changed** (see below) |
| Broadsheet hairline rules, zero radius | Services first drafted as a list split by hairline rules. | **Changed** |
| SaaS card kit: identical rounded cards with soft shadows | Services were first 6 identical cards with icons in a 3×2 grid. | **Changed** |
| ALL-CAPS tracked eyebrows above headings | First draft had "OUR SERVICES" eyebrows above each H2. | **Changed** |
| Middle-dot meta strings | Used once in the port list on the mobile wireframe ("Mina Salman · ..."). | **Changed** to a real list |
| "→" appended to buttons/links | Contact rows had a →. | **Changed** to a phone/WhatsApp icon that says what happens |
| Big number + small label + stats + gradient hero | No stats (we don't have honest numbers). The hero *is* a big number, but it's the phone number, which is the call to action, not a vanity metric. No gradient. | Kept on purpose |
| Numbered markers on non-sequences | Only on "How a move works", which is a real sequence. | Clear |
| Fade-and-slide-up on every section | One motion moment only (the chevron strip, once). | Clear |
| Stencil/container font for a logistics brand | Considered and rejected; a condensed grotesque carries the "painted on a truck" feel without costume. | Clear |

### What I changed after review and why

1. **Dropped the near-black ink; all text is cab teal.** A tinted near-black standing in for black is a known tell, and it added nothing: teal on white is 9.6:1, well past AAA for body text. Using the truck's cab colour for every word makes the whole site feel like it's painted in the client's colours, without adding another accent.
2. **Replaced the 6 identical service cards with a lead service + compact rows.** House shifting is what most visitors want; giving it a bigger block with its included list and letting the other 5 be quick rows creates hierarchy and shortens the mobile scroll. Each item got its own prefilled WhatsApp link, which makes the section do the site's main job.
3. **Removed the "OUR SERVICES" style eyebrows.** They repeated the H2 underneath them. Headings now stand alone in sentence case.
4. **Removed hairline rules between services.** Section changes are shown with background (paper vs concrete) and space instead; rules made it look like a newspaper, which has nothing to do with trucks.
5. **Limited the chevron strip to two uses per page.** In the first draft it also edged cards and buttons; used everywhere it becomes wallpaper. Under the hero and on top of the footer, it reads as the edge of a truck body.
6. **Took the floating WhatsApp button off mobile.** The sticky bar already gives WhatsApp in one tap; two green buttons on a phone screen fight each other and cover content.
7. **Replaced the "→" on contact rows and the middle-dot port list** with icons that show the action (phone, WhatsApp) and a real list, so screen readers and search engines read them properly.
