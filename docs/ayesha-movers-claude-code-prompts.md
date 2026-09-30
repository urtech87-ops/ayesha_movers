# AYESHA Movers & Packers: Claude Code prompt pack

Paste these into Claude Code (desktop), opened on `D:\xampp\htdocs\ayesha-movers`, one at a time.
Every prompt ends with a STOP. Review the test report, then paste the next one.
If a chat runs out of context, start a new Claude Code chat and paste **Prompt R (Resume)**.

Decisions already locked:
- WordPress on XAMPP (Windows). MySQL user `root`, blank password.
- Custom block theme. Every section on every page is editable in the WordPress editor.
- English only.
- Enquiry form emails `ayeshamoversbh786@gmail.com` and stores each enquiry in the admin.
- WhatsApp is click-to-chat only (buttons and a form hand-off), with no automatic API.
- Pages: Home, About Us, Our Services, Contact Us.

---

## Prompt 1: Project setup, CLAUDE.md, PROGRESS.md

```
Before doing anything, read everything that already exists in this folder (D:\xampp\htdocs\ayesha-movers) and tell me what you found. Do not overwrite or delete any existing file without asking me.

We are building a WordPress website for a client. You will do it in phases. Each phase ends with a test pass and a STOP where you wait for my approval.

STEP 1: Create CLAUDE.md in the project root with this brief. It is your permanent memory for this project, so read it at the start of every session.

--- CLAUDE.md content ---
# AYESHA Movers & Packers website

## Client facts (verified from client sources; never invent other facts)
- Business: AYESHA Movers & Packers, also trading as "AYESHA Cargo Handling"
- General Manager: Mohammad Ayub Khokhear
- Mobile + WhatsApp: +973 3444 8236 (primary), +973 3642 9850
- Office: +973 7736 0292
- Email: ayeshamoversbh786@gmail.com
- Instagram: https://www.instagram.com/ayesha_movers_packers/
- No street address is published. Leave the address field empty in settings and don't show an address on the site until the client provides one.
- Coverage: all of Bahrain (Manama and every city), Mina Salman port, Khalifa Bin Salman Port, airport cargo; Saudi Arabia (KSA); all GCC countries; international moves to UK, USA, Canada and worldwide.
- Available 24 hours, day and night.
- Services:
  1. House, villa, flat and office shifting: packing, loading, unloading, "all house internal settings", removal of packing debris
  2. Packing & unpacking: international-standard packing, special packing for fragile items and crockery
  3. Furniture: professional carpenters for dismantling and re-fixing furniture, curtains, and new furniture setups for offices and houses
  4. Appliance removal & fixing: split units / air conditioners, LCD and LED TVs, curtains and blinds
  5. Transportation: Dyna trucks and 6-wheel trucks, rented for 8 hours or a full day; transport to Mina Salman, Khalifa port, the airport, and courier depots (DHL, Aramex, GLS)
  6. International cargo: 20ft and 40ft container loading and unloading, customs documentation, moves to KSA/GCC/UK/USA/Canada
- Selling points: lowest rates with labour included, door-to-door, responsible service, labour + trucks + carpenters all from one team.
- The client says "we have been moving homes for decades". The current site also says "Est. 2026", which conflicts. Do NOT print a founding year or "years of experience" number anywhere; it is an open question for the client.
- Reviews: there are no real customer reviews yet. NEVER write fake testimonials. The reviews section must ship as clearly marked placeholders that the client replaces with real reviews, and it must be hidden on the front end until it has real content.

## Stack decisions (locked)
- XAMPP on Windows: PHP at D:\xampp\php\php.exe, MySQL at D:\xampp\mysql\bin\mysql.exe, user root, blank password
- Database name: ayesha_movers. Local URL: http://localhost/ayesha-movers
- Latest stable WordPress, installed with WP-CLI (keep wp-cli.phar in the project root and run it through XAMPP's php.exe)
- Custom BLOCK theme: wp-content/themes/ayesha-movers (theme.json, templates, template parts, block patterns)
- Site plugin: wp-content/plugins/ayesha-core. All business logic lives here, not in the theme:
  - "Business Info" settings page (phones, WhatsApp number, email, hours text, service areas, social links, WhatsApp default message)
  - A Block Bindings source (register_block_bindings_source) so paragraphs/headings/buttons in the editor can show these settings live. Changing a phone number once updates it everywhere.
  - The enquiry/quote form, as a custom block, with email delivery and saved submissions
- English only; no Arabic/RTL
- WhatsApp is click-to-chat only (https://wa.me/97334448236?text=...). No WhatsApp API.
- Pages: Home, About Us, Our Services, Contact Us

## Hard rules
- EVERY visible block on every page must be editable by the client in the WordPress editor: text, images, buttons, lists, service cards. No hardcoded content in PHP templates except structural wrappers. Page content lives in the page itself (block markup), or in template parts/synced patterns for shared parts.
- No page builders (no Elementor). No paid plugins.
- Allowed third-party plugins: WP Mail SMTP (email delivery), WP Mail Logging (local email testing only). Ask before adding any others.
- Use the frontend-design skill for all visual decisions.
- Security: nonces, sanitize input, escape output, honeypot + rate-limit on the form, and capability checks on admin pages.
- Performance: no jQuery on the front end, self-host fonts, lazy-load images, WebP where possible.
- Git: one branch per phase (phase-1-setup, phase-2-design, ...). Commit as you go. NEVER merge into main without my explicit approval; stop and wait.
- Update PROGRESS.md at the end of every phase AND whenever you stop mid-phase.
--- end CLAUDE.md ---

STEP 2: Create PROGRESS.md in the project root with these sections: Project summary (3 lines), Environment (paths, URLs, DB name, admin username; never the password), Phase checklist (Phases 1-7 with status), Current phase + next step, Decisions log (dated), Open questions for the client, Known issues, Test results per phase. Pre-fill Open questions with:
  - Founding year / years in business ("decades" vs "Est. 2026")
  - Business address or office location, if he wants one shown
  - Logo (none known; we'll use a wordmark for now)
  - Real customer reviews (Google/Instagram) to replace placeholders
  - Gmail App Password for WP Mail SMTP (needed at go-live)
  - Final domain name and hosting

STEP 3: Set up WordPress:
  a. Check that XAMPP Apache and MySQL are running (e.g. try connecting with mysql.exe -u root). If they are not running, tell me and STOP.
  b. Create database ayesha_movers (utf8mb4_unicode_ci).
  c. Download wp-cli.phar into the project root. Download and install the latest stable WordPress into this folder (the site root is this folder, not a subfolder). Site title "AYESHA Movers & Packers", admin user "ayesha_admin", admin email ayeshamoversbh786@gmail.com. Generate a strong random admin password and print it to me once in the chat; do not write it to any file.
  d. Settings: timezone Asia/Bahrain, permalinks /%postname%/, discourage search engines ON (local build), delete default posts/pages/comments, delete Akismet and Hello Dolly, delete unused default themes except the latest Twenty* theme (keep one as a fallback).
  e. Install and activate WP Mail Logging (for local email testing). Install WP Mail SMTP but leave it inactive for now.
  f. Create empty pages: Home, About Us, Our Services, Contact Us. Set Home as the static front page.
  g. Download the client's existing photos into the Media Library, with sensible alt text and titles:
     https://ayeshamoverspackingksa.pages.dev/img/1.jpg to /img/7.jpg (7 images)
     https://www.expatriates.com/img/60361653.1.jpg to 60361653.8.jpg (8 images)
     Save them to wp-content/uploads through WP-CLI (wp media import). If a download fails, log it in PROGRESS.md and continue.
STEP 4: git init in the project root. Create a .gitignore that EXCLUDES WordPress core (wp-admin, wp-includes, root wp-*.php, index.php, license, readme, xmlrpc), wp-config.php, wp-cli.phar, uploads, and third-party plugins/themes. It must INCLUDE CLAUDE.md, PROGRESS.md, docs/, wp-content/themes/ayesha-movers, and wp-content/plugins/ayesha-core. Commit on main as "Initial setup", then create branch phase-1-setup for any further Phase 1 work.

STEP 5: TEST PHASE 1 and write the results into PROGRESS.md:
  - http://localhost/ayesha-movers returns HTTP 200
  - /wp-admin login page returns 200
  - wp core version and wp core verify-checksums pass
  - All 4 pages exist, and Home is the front page
  - The media library count matches the images that downloaded successfully
  - A test email through wp_mail() appears in WP Mail Logging
  Show me the results as a table: Test | Expected | Actual | Pass/Fail.

STOP. Wait for my approval. Do not start Phase 2.
```

---

## Prompt 2: Design plan (no code)

```
Read CLAUDE.md and PROGRESS.md first, and look at the current state of the repository. Then create and switch to branch phase-2-design.

Phase 2 is a DESIGN PLAN ONLY. Write no theme code yet.

Use the frontend-design skill. Also look at all the client images in the Media Library. The subject is a Bahrain-based moving, packing and cargo-handling crew: trucks, containers, carpenters, crates, port runs, 24-hour service. The audience is expats and families in Bahrain/KSA who are moving house, plus small offices. Most arrive from a phone, often from a classified ad or Instagram. The site's primary job is to get them to WhatsApp or call in one tap, or to send a detailed quote request.

Write docs/design-plan.md containing:
1. Colour tokens: 4-6 named hex values, with contrast ratios for text/background pairs (must pass WCAG AA).
2. Type: 1-2 typefaces (self-hosted, Google Fonts licence OK), with roles and a type scale.
3. Layout concept for each of the 4 pages, as ASCII wireframes (mobile first, then desktop), with alignment guidance.
4. The hero concept for Home: what the single memorable element is and why it fits a moving/cargo business.
5. Component list: header, sticky mobile call/WhatsApp bar, floating WhatsApp button, service card, process steps, service-area block, FAQ (details/summary), reviews (placeholder, hidden until real), CTA band, footer, quote form.
6. Improvements over typical Bahrain mover sites. Competitors (gulfmoversbahrain.com, bahrainmovers.com, bhmoversbahrain.com) mostly have generic "No.1 company" copy, no detailed quote form, fake-looking reviews, and weak mobile UX. List how we beat each of those.
7. SEO plan: page titles, meta descriptions, H1 per page, MovingCompany/LocalBusiness JSON-LD (no address until provided), target keywords (movers Bahrain, packers and movers Manama, house shifting Bahrain, furniture dismantling Bahrain, cargo to KSA).

Then do the skill's self-review: check the plan against the generic AI-design defaults the skill lists, revise anything that reads as a template, and add a short "What I changed after review and why" section.

Update PROGRESS.md and commit on phase-2-design.

STOP. Show me a summary of the palette, fonts and hero concept, and wait for my approval or changes. Do not merge and do not start Phase 3.
```

---

## Prompt 3: Foundation (theme, site plugin, header/footer)

```
Read CLAUDE.md, PROGRESS.md and docs/design-plan.md first, and read all existing code in wp-content/themes/ayesha-movers and wp-content/plugins/ayesha-core if present. Confirm Phase 2 was approved and merged (check git log on main). If it wasn't, STOP and tell me.

Create branch phase-3-foundation.

Build:
A. Theme wp-content/themes/ayesha-movers (block theme):
   - style.css header, theme.json (v3) with all colour/type/spacing tokens from the design plan, self-hosted fonts in assets/fonts, and editor styles that match the front end
   - templates: index.html, front-page.html, page.html, 404.html
   - parts: header.html and footer.html, fully editable in Site Editor. The header has a logo/wordmark (site title block), navigation (Home, Our Services, About Us, Contact Us), and Call + WhatsApp buttons.
   - Sticky mobile bottom bar (Call | WhatsApp), and a floating WhatsApp button on desktop
   - Register a pattern category "Ayesha Movers" for the section patterns we'll add in later phases
   - No jQuery. Keep any JS tiny, vanilla and deferred.
B. Plugin wp-content/plugins/ayesha-core:
   - Settings > Business Info page (Settings API, capability manage_options, sanitized): primary phone, second phone, office phone, WhatsApp number (digits only), email, WhatsApp default message, hours text (default "Open 24 hours, 7 days"), service areas (one per line), Instagram URL, address (empty by default). Pre-fill it with the facts from CLAUDE.md.
   - Block Bindings source "ayesha/business" so core/paragraph, core/heading and core/button can display these values and build tel:/wa.me/mailto: links. Document in docs/editing-guide.md how to use it.
   - Shortcodes as a fallback: [ayesha_phone], [ayesha_whatsapp_link], [ayesha_email], [ayesha_hours]
   - Header/footer/sticky-bar phone and WhatsApp links must come from these settings, not hardcoded.
   - JSON-LD MovingCompany schema output in wp_head, built from the settings
C. Test content: activate the theme and plugin.

TEST PHASE 3 (write the results into PROGRESS.md as a table: Test | Expected | Actual | Pass/Fail):
  - php -l on every PHP file; no PHP notices/warnings with WP_DEBUG on (check debug.log)
  - Change the primary phone in Business Info, and confirm it changes in the header, sticky bar and footer, then change it back
  - The header, footer and nav are editable in Site Editor, and the saved changes appear on the front end
  - Screenshots of the header/footer at 375px, 768px and 1440px (use Playwright if available; if not, tell me and give me a manual checklist). Check there is no horizontal scroll, keyboard focus is visible, and tel:/wa.me links are correct.
  - The JSON-LD validates as JSON and contains no address field
Commit.

STOP. Show me the test table and screenshots, and wait for approval. Do not merge.
```

---

## Prompt 4: Home page

```
Read CLAUDE.md, PROGRESS.md, docs/design-plan.md and all existing theme/plugin code first. Confirm Phase 3 is merged into main; if not, STOP.

Create branch phase-4-home.

Build the Home page following the design plan. Every section is a registered block pattern (category "Ayesha Movers") AND is inserted into the Home page content as real, editable blocks (not a pattern reference that locks content). Sections:
  1. Hero: the memorable element from the design plan, headline + short line about lowest-rate, door-to-door moving in Bahrain, KSA and GCC, with Call and WhatsApp buttons (bound to Business Info)
  2. Quick trust strip: 24 hours / labour + trucks + carpenters in one team / lowest rates / Bahrain, KSA & GCC (no invented numbers)
  3. Services overview: 6 service cards (from CLAUDE.md) linking to the matching anchor on Our Services
  4. How a move works: a real sequence (WhatsApp photos or call > quote > pack & dismantle > load & transport > unload, re-fix & clear debris). Numbering is OK here because it is a sequence.
  5. Service areas: Bahrain cities, ports, airport, KSA, GCC, international
  6. Recent work gallery: the client's photos (core gallery block, lightbox on)
  7. Reviews: placeholder pattern, hidden on the front end until real reviews are added (explain in docs/editing-guide.md how to show it)
  8. FAQ: 5-6 questions answered ONLY with facts from CLAUDE.md (e.g. do you dismantle furniture, can I rent a truck for 8 hours, do you move to KSA, do you work at night, do you remove AC units)
  9. Final CTA band: Get a free quote on WhatsApp / Call now
Write copy in plain, specific English from the customer's point of view. No "No.1 company" claims and no invented stats.
Set the SEO title + meta description from the design plan (via ayesha-core, a filter on document title + a meta tag; no SEO plugin).

TEST PHASE 4 (write results into PROGRESS.md as a table):
  - Every section editable: open Home in the block editor, change one text, one image and one button in 3 different sections, save, and verify on the front end, then revert
  - Responsive screenshots at 375 / 768 / 1440; no horizontal scroll; images have alt text
  - Lighthouse (npx lighthouse if available, mobile) Performance / Accessibility / Best Practices / SEO scores recorded; aim for 90+ on each and list anything below
  - All tel:/wa.me/anchor links work; no 404s in the network log
  - The reviews section is not visible on the front end
Commit.

STOP. Show results and screenshots; wait for approval. Do not merge.
```

---

## Prompt 5: Our Services + About Us

```
Read CLAUDE.md, PROGRESS.md, docs/design-plan.md and all existing theme/plugin code first. Confirm Phase 4 is merged; if not, STOP.

Create branch phase-5-services-about.

OUR SERVICES page (all editable blocks, reusing the Phase 4 patterns where they fit):
  - Page intro + CTA
  - One section per service (6), each with an id anchor matching the Home cards: what's included (bullets from CLAUDE.md only), a relevant client photo, and a "Get a quote for this" WhatsApp button whose prefilled text names the service (e.g. "Hi, I need a quote for Furniture dismantling & re-fixing")
  - Truck rental detail: Dyna / 6-wheel, 8-hour or daily
  - Service areas block + final CTA

ABOUT US page:
  - Who we are: the client's own description, rewritten cleanly (professional movers, packers, transportation, removal & shifting; stress-free moving; customer satisfaction; lowest rates in Bahrain and KSA). Do not add a founding year.
  - General Manager block: name + role, with a photo placeholder that is hidden until an image is added
  - Why choose us: international-standard packing, responsible service, low prices with labour, day & night service, 20ft/40ft container handling
  - How we work (reuse the process pattern), service areas, CTA
Set the SEO title/meta for both pages.

TEST PHASE 5 (results into PROGRESS.md as a table):
  - Anchors from the Home service cards land on the correct Services sections
  - Each service's WhatsApp button opens wa.me with the correct number and a correctly URL-encoded prefilled message
  - Editing test on both pages (text, image, button, list item)
  - Responsive screenshots 375/768/1440; Lighthouse mobile scores; heading order (one H1 per page, no skipped levels)
  - No content in either page that isn't backed by CLAUDE.md facts. Show me a list of every factual claim and its source line.
Commit.

STOP. Show results; wait for approval. Do not merge.
```

---

## Prompt 6: Contact Us + quote form + email

```
Read CLAUDE.md, PROGRESS.md, docs/design-plan.md and all existing theme/plugin code first. Confirm Phase 5 is merged; if not, STOP.

Create branch phase-6-contact.

A. Quote form: a custom block "ayesha/quote-form" in ayesha-core (server-rendered):
   Fields: name*, phone/WhatsApp*, email (optional), move type* (House / Villa / Flat / Office / Furniture only / Appliances / Truck rental / International cargo), moving from*, moving to*, preferred date, property size (Studio / 1BR / 2BR / 3BR / 4BR+ / Villa / Office), services needed (checkboxes: packing, dismantling & re-fixing, AC/TV removal & fixing, debris removal, container loading), message.
   - The form labels and the button text are editable in the block sidebar
   - Security: nonce, honeypot, per-IP rate limit (transient; 5 per hour), and server-side validation with clear error messages next to each field
   - On success:
     1. Save it as a private custom post type "Enquiries" (admin menu with a list table showing date, name, phone, move type, from > to, and a status column: New / Contacted / Done)
     2. Email the Business Info email address with a clean HTML summary; Reply-To is the customer's email if given; subject "New moving enquiry: {move type}, {from} to {to}"
     3. Send the customer a confirmation email if they gave an email address
     4. Show a success message plus a "Also send this on WhatsApp" button that opens wa.me with the full enquiry pre-filled (click-to-WhatsApp, no API)
   - Works without JavaScript (normal POST + redirect); JS only improves it
B. CONTACT US page (editable blocks): intro, contact cards (phones, WhatsApp, office, email, hours, all bound to Business Info), quote form, service areas, FAQ subset. No map or address until the client provides one.
C. WP Mail SMTP: write docs/email-setup.md explaining how to connect Gmail with an App Password at go-live. Locally we keep WP Mail Logging.

TEST PHASE 6 (results into PROGRESS.md as a table):
  - Valid submission: the enquiry is saved, the admin email appears in WP Mail Logging with correct fields, the customer confirmation email is logged, and the WhatsApp hand-off link is correct and encoded
  - Each required field empty; invalid phone; invalid email; honeypot filled; 6th submission within an hour blocked; bad nonce rejected
  - XSS attempt in the message (<script>) is escaped in the admin list, the email and the WhatsApp text
  - The form works with JS disabled
  - Keyboard-only completion of the form; labels are associated; errors are announced (aria-describedby / role=alert)
  - Responsive screenshots 375/768/1440
Commit.

STOP. Show results; wait for approval. Do not merge.
```

---

## Prompt 7: Full QA, SEO, performance, handover

```
Read CLAUDE.md, PROGRESS.md, all docs/ and all theme/plugin code first. Confirm Phase 6 is merged; if not, STOP.

Create branch phase-7-qa.

1. Full regression across all 4 pages: links (no 404s), all tel:/wa.me/mailto links, nav on mobile (menu open/close, focus trap, Esc closes), sticky bar, floating WhatsApp button not covering content or the form submit button.
2. Responsive at 320, 375, 414, 768, 1024, 1440, 1920. Save screenshots in docs/qa/screenshots.
3. Lighthouse mobile + desktop on all 4 pages into docs/qa/lighthouse. Fix anything under 90 where possible and list what remains.
4. Accessibility: axe-core (via Playwright) on all pages; fix all serious/critical findings; check colour contrast against the design tokens.
5. SEO: unique titles/meta, one H1 per page, image alt text, JSON-LD valid, sitemap at /wp-sitemap.xml, robots, Open Graph tags (image = best client photo). Note: "Discourage search engines" must be switched OFF at go-live; add this to the go-live checklist, don't change it now.
6. Security: WP_DEBUG off for the final check; no PHP warnings; the form security tests from Phase 6 re-run; the ayesha-core settings page is admin-only; no directory listing in the theme/plugin (index.php guards); a file edit block recommendation for wp-config at go-live.
7. Client handover:
   - docs/editing-guide.md: a plain-English guide for the client (with screenshots): change phone/WhatsApp/email, edit any text/image/button on a page, add a service card, replace gallery photos, show the reviews section and add real reviews, read and mark enquiries, where emails go
   - docs/go-live-checklist.md: domain, hosting, migration (e.g. All-in-One WP Migration or manual DB export with search-replace via WP-CLI), SMTP setup, indexing ON, SSL, backups, Search Console, and the open client questions
8. Write the final QA report to docs/qa/qa-report.md AND as an Excel workbook docs/qa/qa-report.xlsx (sheets: Summary, Test Cases with ID/Area/Steps/Expected/Actual/Status/Severity, Defects, Lighthouse Scores, Open Questions).
Update PROGRESS.md (all phases complete, remaining issues, next steps). Commit.

STOP. Show me the summary and the Excel report; wait for approval before merging.
```

---

## Prompt R: Resume in a new chat (use any time context runs out)

```
Read CLAUDE.md and PROGRESS.md first, then run git status and git log --oneline --all -15 and read the code on the current branch. Summarise: current phase, what is done, what is left in this phase, any uncommitted work, and the last test results. Do not change anything yet. STOP and wait for me to confirm before continuing the current phase from where it stopped.
```

---

## Prompt F: Fix feedback within a phase

```
Read CLAUDE.md, PROGRESS.md and the code on the current branch first. Apply only these changes:
<paste your feedback list here>
Do not touch anything else. Re-run the affected tests from this phase, update PROGRESS.md (Decisions log + Test results), and commit. STOP and show me the before/after.
```
