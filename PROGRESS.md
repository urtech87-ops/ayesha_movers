# PROGRESS: AYESHA Movers & Packers website

## Project summary
WordPress site for AYESHA Movers & Packers (Bahrain), a moving, packing and cargo-handling business.
Custom block theme (`ayesha-movers`) plus site plugin (`ayesha-core`); 4 pages: Home, About Us, Our Services, Contact Us.
Main goal: get mobile visitors to WhatsApp or call in one tap, or to send a detailed quote request.

## Environment
| Item | Value |
|---|---|
| Project root / site root | `D:\xampp\htdocs\ayesha-movers` |
| WordPress | 7.1.2 (en_US) |
| PHP | `D:\xampp\php\php.exe` (8.2.12) |
| Web server | XAMPP Apache (`D:\xampp\apache`), port 80 |
| MySQL | XAMPP MariaDB 10.4.32, **port 3307** (set in `D:\xampp\mysql\bin\my.ini`); client `D:\xampp\mysql\bin\mysql.exe -u root -h 127.0.0.1 -P 3307` |
| DB | `ayesha_movers` (utf8mb4 / utf8mb4_unicode_ci), user `root`, blank password, `DB_HOST` = `127.0.0.1:3307` |
| Local URL | http://localhost/ayesha-movers |
| Admin URL | http://localhost/ayesha-movers/wp-admin |
| Admin username | `ayesha_admin` (password given in chat once; never stored in any file) |
| WP-CLI | `D:\xampp\php\php.exe wp-cli.phar <command>` from the project root |
| wp-config extras | `WP_ENVIRONMENT_TYPE=local`, `WP_DEBUG` on, log to `wp-content/debug.log`, display off, `DISALLOW_FILE_EDIT`, **`AUTOMATIC_UPDATER_DISABLED` = true** (no automatic core/plugin/theme/translation updates, so WordPress stays at 7.1.2 for the whole project). wp-config.php is not in git: re-add these by hand on any new install |
| Plugins | **AYESHA Core 0.4.0 (active)**, WP Mail Logging 1.17.0 (active), WP Mail SMTP 4.10.0 (inactive) |
| Themes | **AYESHA Movers 0.4.0 (active)**; Twenty Twenty-Five 1.5 (fallback) |
| Navigation | `wp_navigation` post 4 "Main menu": Home, About Us, Our Services, Contact Us + a bound Call/WhatsApp Buttons block (shown only in the open mobile menu). Used by header and footer. Lives in the database; reference copy in `docs/navigation-main-menu.html` |
| Business Info | Settings → Business Info (option `ayesha_business`) |
| Pages | Home (ID 5, static front page), About Us (6), Our Services (7), Contact Us (8) |
| Synced patterns | "CTA band" (`wp_block` 26, slug `ayesha-cta-band`), "Where we go" (27, `ayesha-where-we-go`); category Ayesha Movers. Backup + rebuild script in `docs/content/` |
| Git remote | `origin` = https://github.com/urtech87-ops/ayesha_movers.git |

> **Startup note:** MySQL runs on **port 3307**. After a reboot, start **Apache + MySQL from the XAMPP Control Panel**. **Laragon must stay closed**; it grabs ports 80 and 3306 and serves a different docroot.

### Media status
| ID | File | Status | Reason |
|---|---|---|---|
| 9 | yellow-box-truck-residential-building | OK | Home hero (560px WebP copy). No caption; alt describes only what's visible |
| 10 | movers-carrying-white-sofa | **PLACEHOLDER ONLY** | Looks like a stock photo; replace with a real client photo before go-live (or confirm licence) |
| 11 | white-pickup-truck-cargo-rails | **DO NOT USE** | Shows phone number 0524070463 (not an AYESHA number) |
| 12 | pickup-loaded-with-household-goods | OK | Home, under the house-shifting card, from 960px only (366 x 480, natural size, WebP, lazy-loaded). Alt describes only what's visible |
| 13 | crew-loading-wrapped-furniture | **DO NOT USE** | Appears AI-generated |
| 14 | red-curtain-side-truck | OK | |
| 15 | large-box-truck-with-driver | OK | |

In the Media Library, the tags are prefixed to the title (`[DO NOT USE]`, `[PLACEHOLDER ONLY]`) and the reason is in the Description field.

## Phase checklist
| Phase | Branch | Status |
|---|---|---|
| 1. Setup (WP, CLAUDE.md, PROGRESS.md, git) | phase-1-setup | **Done; approved and merged into main 2026-10-01** |
| 2. Design plan | phase-2-design | **Done; approved and merged into main 2026-10-01** |
| 3. Foundation (theme, plugin, header/footer) | phase-3-foundation | **Done; approved and merged into main 2026-10-01 (merge commit 81c86cf)** |
| 4. Home page | phase-4-home | **Done; approved and merged into main 2026-10-02 (fast-forward to f2105b4)** |
| 5. Our Services + About Us | phase-5-services-about | Not started |
| 6. Contact Us + quote form + email | phase-6-contact | Not started |
| 7. Full QA, SEO, performance, handover | phase-7-qa | Not started |

## Current phase + next step
- **Current:** Phase 5 – Our Services + About Us (not started). Branch `phase-5-services-about`.
- **Next step:** wait for the Phase 5 prompt; build it following `docs/design-plan.md` (sections 3.2 and 3.3).
- **Last completed:** Phase 4 (Home page), approved and merged into `main` 2026-10-02 at f2105b4 (fast-forward); smoke test passed (see Test results › Phase 4 › Post-merge smoke test).
- **Phase 5 notes (carried over from Phase 4):**
  - The Home page links to these anchors on Our Services; each service H2 there must have the matching id: `#house-shifting`, `#packing`, `#furniture`, `#appliances`, `#trucks`, `#cargo`. The quote form (Phase 6) needs `id="quote"` on Contact Us.
  - Reuse the synced patterns "CTA band" (`wp_block` 26) and "Where we go" (27): insert them from **Patterns → Ayesha Movers** (the theme patterns insert the synced copy), never as plain copies.
  - Use the theme helpers in `inc/pattern-parts.php` (`ayesha_theme_section_markup()`, the Questions markup) for new sections; per-service WhatsApp buttons bind `url` to `whatsapp_url` with `args.message` (see docs/editing-guide.md).
  - Photos: 12 and 14 for Our Services, 15 for About Us (no ownership claims in alt text). Resized copies are WebP automatically; only the first image on a page loads eagerly.
  - After building a page, back its content up in `docs/content/` (extend `home-content.php` or add a script) and set its title/description in the Search engines panel (design plan section 7).
  - If Phase 5 is built in a worktree again, use the same junction method and remove the junctions with `rmdir` (link only) before merging.

## Decisions log
- 2026-10-01: Project brief written to CLAUDE.md.
- 2026-10-01: Prompt pack moved to `docs/ayesha-movers-claude-code-prompts.md` (user request).
- 2026-10-01: Laragon (which held ports 80/3306) stopped by the user; XAMPP Apache + MySQL used as per CLAUDE.md.
- 2026-10-01: XAMPP MySQL runs on port 3307 (pre-existing my.ini setting), so `DB_HOST` is `127.0.0.1:3307`.
- 2026-10-01: `extension=mongodb` commented out in `D:\xampp\php\php.ini` (backup: `php.ini.bak`) to stop PHP startup warnings (user approved).
- 2026-10-01: **Repaired XAMPP MariaDB system tables.** mysqld hung at startup with `Fatal error: Can't open and lock privilege tables: Incorrect file format 'db'`. `mysql/db.MAI/.MAD` were overwritten with garbage (file dates Feb 2025). Full data dir backed up to `D:\xampp\mysql\data.bak-20261001` first. Restored `mysql.db` and `mysql.proxies_priv` from XAMPP's pristine `D:\xampp\mysql\backup\mysql\`; repaired `mysql.global_priv` and `mysql.roles_mapping` with `aria_chk -r` (rows kept). All system tables then check clean; user databases untouched. Any custom per-database grants that were in the old `mysql.db` are lost (the file was unreadable).
- 2026-10-01: WordPress core downloaded as the official 7.1.2 zip (SHA1 verified) and extracted manually, because `wp core download` failed on Windows (long temp path + no `tar` on PATH).
- 2026-10-01: `.htaccess` written by hand (standard WP block, `RewriteBase /ayesha-movers/`) because WP-CLI can't write it on this setup. Not in git.
- 2026-10-01: `.gitignore` uses a whitelist: ignore all, un-ignore `.gitignore`, `CLAUDE.md`, `PROGRESS.md`, `docs/`, `wp-content/themes/ayesha-movers/`, `wp-content/plugins/ayesha-core/`.
- 2026-10-01: Media alt text describes what is visible and makes no claim that a pictured vehicle belongs to AYESHA (see Open questions).
- 2026-10-01: User approved Phase 1. Media IDs 11 and 13 tagged DO NOT USE; ID 10 tagged PLACEHOLDER ONLY (see Environment > Media status).
- 2026-10-01: CLAUDE.md gained the GitHub remote/push rule; committed on `main` (8e90417) and pushed to origin.
- 2026-10-01: Phase 2 design direction: palette from truck photo 9 (box yellow #F2B705, cab teal #0F4D4A for all text, tarmac #3F6F6B, concrete #E8EBE9, paper #FFFFFF, WhatsApp green #25D366 with teal text). One font: Archivo variable (condensed 800 for display, normal width for body), self-hosted. Hero = the phone number painted large on a yellow panel with the truck's chevron tape. Full plan: docs/design-plan.md.
- 2026-10-01: Floating WhatsApp button is desktop-only; mobile uses the sticky Call/WhatsApp bar.
- 2026-10-01: Competitor check: only bahrainmovers.com was reachable; gulfmoversbahrain.com (DNS error) and bhmoversbahrain.com (403) were not.
- 2026-10-01: The quote form fields in design-plan.md section 5 supersede the field list in Prompt 6.
- 2026-10-01: Design plan amended after review: photo 9 makes no ownership claim and the hero must work without it; hero number size is fluid from its container (with a viewport/long-number test list); `availableLanguage` removed from JSON-LD; Archivo size check added to Phase 3 notes. Plan approved; `phase-2-design` merged into `main`.
- 2026-10-01: Phase 3: where the Phase 3 prompt and the design plan differed, the plan won: nav order Home / About Us / Our Services / Contact Us; floating WhatsApp button rendered by the plugin; sticky bar hides while a form field has focus; menu collapses below 960 px (core default is 600); footer has © year and the main number in yellow display type; hours default "Open 24 hours, every day"; JSON-LD `areaServed`, `makesOffer`, `employee` and `image` (photo 9) are fixed as in plan section 7, while phones, email, Instagram and address come from settings.
- 2026-10-01: Archivo: Latin subset with all axes was 84,924 B (82.9 KB, under the 100 KB limit). Axes were still limited to the ranges we use (wght 400–800, wdth 62–100) → **55,956 B (54.6 KB)**. Source: google/fonts `ofl/archivo/Archivo[wdth,wght].ttf`, subset with fonttools (installed with pip). Fallback faces: Arial, size-adjusted per role (98.58 % body, 95.32 % UI, 77.78 % headings, 69.21 % display).
- 2026-10-01: AA by construction: theme.json switches off the colour pickers; colour comes only from block styles (Teal / Yellow / Grey panel; Fill / WhatsApp green / Yellow / Outline buttons). Chat-green is not in the palette (custom token only), so white-on-green and yellow-on-white can't be chosen.
- 2026-10-01: Phase 3 review fixes: (1) footer shows one "Call or WhatsApp" row when the WhatsApp number equals the main number (AYESHA Core hides blocks with class `ayesha-if-whatsapp-same` / `ayesha-if-whatsapp-differs`), contact rows are label-beside-value, 44 px tap targets, page links in 2 columns on mobile; (2) 32 px gap between the last nav item and the Call button from 960 px; (3) Call/WhatsApp buttons (sticky-bar styles, bound to Business Info) inside the open mobile menu, stored in the Main menu itself so they stay editable; (4) JSON-LD `image` removed until the client confirms photo 9 (filter `ayesha_core_schema_image_id` now defaults to 0).
- 2026-10-01: Theme CSS/JS are versioned with the file's modified time (`ayesha_theme_asset_version()`), because a fixed `?ver=` let the browser keep an old stylesheet during testing.
- 2026-10-01: Phase 3 is developed in a git worktree. To let the local site run it, `wp-content/themes/ayesha-movers` and `wp-content/plugins/ayesha-core` in the main checkout are **directory junctions** pointing into the worktree.
- 2026-10-01: User approved Phase 3. The two dev junctions were removed (`rmdir`, link only), `phase-3-foundation` was fast-forward merged into `main` (81c86cf) in the main checkout, so the theme and plugin are now real folders from git; `main` and `phase-3-foundation` pushed to origin (both 81c86cf).
- 2026-10-01: WordPress automatic updates turned off for this local build: `define( 'AUTOMATIC_UPDATER_DISABLED', true );` in wp-config.php (not committed), so the WordPress version (7.1.2) doesn't change mid-project. Reason: an automatic update check ran during the Phase 3 smoke test (it changed nothing; still 7.1.2). Updates are now applied only on purpose; revisit at go-live (Phase 7) for the production host.
- 2026-10-01: Phase 4: where the prompt and design plan differed, the plan won (user approved each): "Why people book us" and "Questions" side by side on desktop, so Reviews sits just before them; photo 9 under the chevron strip on phones; "See all services" link; "Manama" in the hero line; SEO panel added now instead of Phase 7.
- 2026-10-01: Phase 4 copy decisions (user approved): no caption under photo 9; "Truck hire: 8 hours or a full day" (not "by the hour"); price point worded "Low rates, with labour included", never "lowest" or "cheapest".
- 2026-10-01: Hero number sizing: AYESHA Core writes the number's width in em (from Archivo's measured glyph widths) into CSS custom properties; CSS divides the paragraph's container width by it (max 128px hero, 96px CTA band; two lines below 420px of column width). Recalculates on every settings change (user approved).
- 2026-10-01: Search-engine title and description: post meta `_ayesha_seo_title` / `_ayesha_seo_description` on pages and posts, edited in the page editor's "Search engines" panel (AYESHA Core); no SEO plugin.
- 2026-10-01: Resized images are saved as WebP (AYESHA Core, `image_editor_output_format`); theme size `ayesha-photo` = 560px wide. Photo 9 regenerated.
- 2026-10-01: Shared sections: the theme's CTA band and Where we go patterns insert the synced pattern (found by slug) when it exists, otherwise the plain blocks.
- 2026-10-01: Footer contact label column widened 4rem to 4.75rem ("Instagram" broke onto two lines at 1280px; Phase 3 issue found in Phase 4).
- 2026-10-01: Lighthouse 12.8.2 run with `npx` (user approved the download). The first run hit "Fatal process out of memory" (about 1 GB of RAM free); each report was then run on its own from the npx cache.
- 2026-10-01: Phase 4 review fixes (user request): photo 12 under the lead service card (one Image block; on phones it sits between the card and the rows); "Where we go" changed to heading + intro on top and the route across the full width (auto-sized grid columns, one row for any number of stops); FAQ "Can I hire a truck for 8 hours or a full day?". Mobile gutters were already equal (16px both sides): the 2px right gutter in the earlier screenshots came from the desktop browser's 15px scrollbar, so mobile screenshots are now taken with real phone emulation (no scrollbar).
- 2026-10-01: WebP regeneration makes WordPress use the WebP copy as each photo's main file (photos 9 and 12); the uploaded JPEG is kept and recorded as `original_image`.
- 2026-10-01: Photo 12 is shown from 960px only (user request); hidden on phones and tablets. AYESHA Core now lets only the first content image skip lazy loading (`wp_omit_loading_attr_threshold` = 1, WordPress default 3), because WordPress had loaded photo 12 eagerly, and an eager image is downloaded even when hidden. The hero photo keeps `fetchpriority="high"`.
- 2026-10-01: "What we do" stacks the lead card and the five rows in one column below 960px (as on phones), so the photo-less two-column layout between 782 and 959px never appears (WordPress puts columns side by side from 782px).
- 2026-10-02: The user's earlier E1–E3 report ("E1 pass, E2 failed, E3 pass") was a template pasted by mistake; those results were discarded. The user then approved a temporary local admin session created with WP-CLI. Claude ran E1–E3 in the block editor and destroyed the session afterwards (0 sessions left). The session cookie was written to a git-ignored file (no password involved), but the Playwright tool echoed it into the conversation log; it can't be reused, because the session was destroyed on the server.
- 2026-10-02: User approved Phase 4. The two dev junctions were removed (`rmdir`, links only), the theme and plugin folders restored from git in the main checkout, `phase-4-home` fast-forward merged into `main` (f2105b4), `main` pushed. The copies of the Phase 3 folders that were moved aside at the start of Phase 4 are still in the Phase 4 session's scratch folder; they are identical to git and no longer needed.
- 2026-10-02: Apache and MySQL were found stopped (after a restart); the user restarted them from the XAMPP Control Panel.
- 2026-10-01: Outside this session, `phase-1-setup` was renamed to `main` and pushed to origin. `phase-1-setup` was recreated from `main` for the final Phase 1 commit, then fast-forward merged into `main`.

## Open questions for the client
- Price point: the site says "Low rates, with labour included" (his own selling point, no superlative). Confirm the wording
- How are prices worked out (per truck, per hour, per room)? Needed for an honest FAQ answer; not invented in the plan
- Is the man in photo 15 the GM or crew, and are the trucks in photos 9, 14, 15 AYESHA's own? (Affects alt text and captions; until confirmed, no caption or alt text claims ownership)
- Which languages does your team speak? (Needed before adding `availableLanguage` to the JSON-LD or mentioning languages on the site)
- Founding year / years in business ("decades" vs "Est. 2026")
- Business address or office location, if he wants one shown
- Logo (none known; we'll use a wordmark for now)
- Real customer reviews (Google/Instagram) to replace placeholders
- **Photo 9 in the search-engine data:** the JSON-LD `image` (photo 9, the yellow truck) was removed in Phase 3. **Re-add it after the client confirms the truck is his**: change the default of the `ayesha_core_schema_image_id` filter in `wp-content/plugins/ayesha-core/includes/schema.php` from 0 to 9 (or to the ID of a photo he confirms).
- Gmail App Password for WP Mail SMTP (needed at go-live)
- Final domain name and hosting
- **Photos (from ayeshamoverspackingksa.pages.dev):** which are really AYESHA's own trucks/crew? Concerns:
  - `movers-carrying-white-sofa` (ID 10): **PLACEHOLDER ONLY**, looks like a stock photo.
  - `crew-loading-wrapped-furniture` (ID 13): **DO NOT USE**, looks AI-generated (uniform crew, garbled shop signs).
  - `white-pickup-truck-cargo-rails` (ID 11): **DO NOT USE**, shows phone number 0524070463 (looks like a UAE number), not a client number.
  - Can the client send his own real photos of jobs, trucks and crew?
- **Photos from the expatriates.com ad (60361653.1–8.jpg):** couldn't be downloaded automatically (see Known issues). The client or the user can send them directly.

## Known issues
- **expatriates.com images not imported (8 of 15).** All 8 URLs (`https://www.expatriates.com/img/60361653.1.jpg` … `.8.jpg`) returned HTTP 403 with a Cloudflare "Just a moment… Enable JavaScript and cookies" bot challenge. Not bypassed on purpose. Workaround: save them manually from a browser into a folder (e.g. `D:\ayesha-photos`) and I'll import them with `wp media import`.
- XAMPP MySQL is currently running as a process started by Claude (`mysqld --standalone`), not from the XAMPP Control Panel, so the panel may show it as stopped. To restart normally: end `mysqld.exe` in Task Manager, then click Start in the XAMPP panel.
- ~~The default sender `wordpress@localhost` is rejected by PHPMailer.~~ Fixed in Phase 3: AYESHA Core sends as "AYESHA Movers & Packers <Business Info email>". Locally, mail is logged but not sent (no mail server: "Could not instantiate mail function"). At go-live, WP Mail SMTP with the Gmail App Password must send through Gmail, because a gmail.com From address sent from any other server fails DMARC.
- WP Mail Logging stores only the headers passed to `wp_mail()`, not the From set by filters, so the From address does not appear in its log; Phase 3 verified it with a `phpmailer_init` capture instead.
- ~~**Dev junctions (Phase 3):**~~ Resolved 2026-10-01: the junctions were removed before the merge; the main checkout now has the real theme and plugin folders from git. If a later phase is built in a worktree again, the same rule applies: remove junctions with `rmdir` (never a recursive delete) before merging.
- `wp db query` / `wp db check` fail because `mysql` isn't on the PATH. Use `wp eval` with `$wpdb`, or call `D:\xampp\mysql\bin\mysql.exe -P 3307` directly.
- In Git Bash, run `export MSYS_NO_PATHCONV=1` before WP-CLI commands (see Phase 1 test notes).
- `wp rewrite structure --hard` can't regenerate `.htaccess` on this setup. If permalinks change, edit `.htaccess` by hand.

## Test results per phase
### Phase 1 (run 2026-10-01)
| # | Test | Expected | Actual | Pass/Fail |
|---|---|---|---|---|
| 1 | `GET http://localhost/ayesha-movers/` | HTTP 200 | 200 (no-slash URL 301s to slash URL) | Pass |
| 2 | `/wp-admin` login page | 200 | `/wp-admin/` → 302 to `wp-login.php` → 200 | Pass |
| 3a | `wp core version` | Latest stable | 7.1.2 (matches api.wordpress.org latest) | Pass |
| 3b | `wp core verify-checksums` | Success | "WordPress installation verifies against checksums" (only warning: `wp-cli.phar` is not core, expected) | Pass |
| 4a | 4 pages exist, published | Home, About Us, Our Services, Contact Us | IDs 5, 6, 7, 8, all publish; each URL returns 200 | Pass |
| 4b | Home is the static front page | `show_on_front=page`, `page_on_front=5` | page / 5 | Pass |
| 5 | Media count = successful downloads | 7 (7 of 15 downloaded; 8 blocked by Cloudflare) | 7 attachments, 7 originals in `uploads/2026/10` | Pass (8 images outstanding, see Known issues) |
| 6 | Test `wp_mail()` appears in WP Mail Logging | Row in `wp_wpml_mails` | Row #1 logged: to ayeshamoversbh786@gmail.com, "Phase 1 test email". `wp_mail()` returned false: no local mail server and "Invalid address (From): wordpress@localhost" (expected locally) | Pass |
| 7 | Settings | tz Asia/Bahrain, `/%postname%/`, discourage search ON, 0 posts/comments | All as expected; test post at `/permalink-test/` returned 200, then deleted | Pass (after fix, see below) |
| 8 | Plugins/themes | Akismet + Hello Dolly gone; WP Mail Logging active; WP Mail SMTP inactive; only Twenty Twenty-Five | As expected | Pass |

Fix found during testing: the permalink structure had been saved as `/C:/Program Files/Git/%postname%/` because Git Bash converts `/…/` arguments into Windows paths. Re-set with `MSYS_NO_PATHCONV=1`; no other options were affected. **Always `export MSYS_NO_PATHCONV=1` before WP-CLI commands in Git Bash.**

### Phase 3 (run 2026-10-01)
Screenshots: `docs/screenshots/phase-3/` (`<width>-top.png` and `<width>-bottom.png` at 320, 375, 768, 1280, 1920 px; `375-focus.png`, `1280-focus.png`, `375-menu-open.png`, `site-editor-binding.png`). The pages have no content yet, so the tests used About Us (header, page title, footer, sticky bar).

| # | Test | Expected | Actual | Pass/Fail |
|---|---|---|---|---|
| 1 | `php -l` on every PHP file (2 theme, 8 plugin) | No syntax errors | No syntax errors in all 10 | Pass |
| 2 | WP_DEBUG on: render `/`, `/about-us/`, `/our-services/`, `/contact-us/`, a 404, search, the Site Editor and the Business Info page; read `wp-content/debug.log` | No notices/warnings from theme or plugin | 0 lines from theme or plugin; final log empty. (Warnings from my own temporary test script in test 4 were not from the site; that script was fixed and deleted) | Pass |
| 3 | Change main phone to +973 1111 2222 and WhatsApp to "+973 1111-2222" (through the plugin's sanitizer), then change back | Header, sticky bar, floating button and footer all update; WhatsApp saved as digits | (First run, before review fix 1.) Header `tel:+97311112222` "+973 1111 2222"; footer same + `wa.me/97311112222`; sticky bar `tel:` and `wa.me` updated; floating button `wa.me/97311112222`; saved as `97311112222`. After restoring, all four show +973 3444 8236 again | Pass |
| 4 | Header, footer and nav editable in the Site Editor; saved changes appear on the front end | Saves succeed; front end shows them | Saved through the Site Editor's REST endpoints as `ayesha_admin`: header part (HTTP 200, source=custom), footer part (200), Main menu (200). Front end showed all 3 edits (the nav label in both header and footer menus). Parts reset to the theme files and menu restored; front end back to original | Pass |
| 5 | Editor shows live Business Info values | Bound blocks show the setting, not the static fallback | Office number temporarily set to +973 7000 0001: the Site Editor showed +973 7000 0001; sidebar "Attributes → content: Office number, tap to call (for text)"; 22 fields offered; no console errors. Restored | Pass |
| 6 | No horizontal scroll at 320 / 375 / 768 / 1280 / 1920 | scrollWidth ≤ clientWidth | None at all 5 widths | Pass |
| 7 | Sticky bar only < 960 px; floating button only ≥ 960 px | Bar at 320/375/768; button at 1280/1920 | Bar visible at 320/375/768, hidden at 1280/1920. Button hidden at 320/375/768, visible at 1280/1920 | Pass |
| 8 | Sticky bar never covers the footer (scrolled to the bottom) | Footer bottom ≤ bar top | 320: 584 ≤ 584; 375: 756 ≤ 756; 768: 968 ≤ 968 (body bottom padding = bar height + safe-area inset) | Pass |
| 9 | Keyboard focus visible on every link and button | A ring on every focusable | Tabbed through all (15–18 per width): every one matched `:focus-visible` with a solid outline (3 CSS px; reads 2.4 px on this 1.25× screen). Teal on light backgrounds, yellow in the teal footer; inset rings in the sticky bar | Pass |
| 10 | tel: / wa.me links correct | E.164 `tel:`; `wa.me/<digits>?text=…` | Re-run after fixes: header `tel:+97334448236`; footer `tel:+97334448236` (Call or WhatsApp), `tel:+97336429850`, `tel:+97377360292`, `mailto:ayeshamoversbh786@gmail.com`; bar and open menu `tel:+97334448236` + `https://wa.me/97334448236?text=Hi%20AYESHA…`; floating button wa.me. (The footer has no separate wa.me link while WhatsApp = main number; see test 21) | Pass |
| 11 | Font file size | ≤ 100 KB | `archivo-latin-var.woff2` = 55,956 B = **54.6 KB** (wght 400–800, wdth 62–100; with all axes it would be 82.9 KB) | Pass |
| 12 | No layout shift when the font loads | No visible shift | Font held back 3 s, then swapped in: CLS 0 at 375 px and 0.0006 at 1280 px; no height changed; widths changed by 9 px at most (nav and Call button at 1280) | Pass |
| 13 | JSON-LD is valid JSON with no address and (fix 4) no image | Parses; no `image`, `address`, `availableLanguage`, `aggregateRating`, `review`, `foundingDate` | Re-run after fix 4: one block, parses; 14 keys, none of those; `telephone` +97334448236, 3 contactPoints, 6 offers, 10 areaServed. Before: `image` = photo 9 URL (`before/fix4-jsonld.json`); after: no image (`after/fix4-jsonld.json`) | Pass |
| 14 | JSON-LD follows the settings | Values change with settings; PostalAddress only when an address is filled in | Phone and email changes appeared in `telephone`, `contactPoint`, `email`. Address set → `PostalAddress` appeared; cleared → gone. Restored | Pass |
| 15 | Test wp_mail shows the new From address | "AYESHA Movers & Packers" <ayeshamoversbh786@gmail.com> | WP Mail Logging rows #2 and #3. PHPMailer From = `AYESHA Movers & Packers <ayeshamoversbh786@gmail.com>` (captured on `phpmailer_init`). The error is now "Could not instantiate mail function" (no local mail server), no longer "Invalid address (From): wordpress@localhost". WP Mail Logging does not display the From (see Known issues) | Pass (From verified by capture, not visible in the WP Mail Logging screen) |
| 16 | Business Info page: capability, nonce, fields | `manage_options` only; nonce; 11 fields pre-filled from CLAUDE.md | 11 fields; `_wpnonce` present; `manage_options` false for visitors; defaults = CLAUDE.md facts, address empty, reviews off | Pass |
| 17 | Shortcodes | Correct output | `[ayesha_phone]` → tel link; `which="office" link="no"` → +973 7736 0292; `[ayesha_whatsapp_link text=… message=…]` → custom wa.me link; `[ayesha_email]`; `[ayesha_hours]` | Pass |
| 18 | Reviews switch | Blocks with class `ayesha-reviews` hidden while it's off | Off → no output; on → shown; set back to off | Pass |
| 19 | No jQuery on the front end; JS tiny and deferred | No jQuery | No jQuery; the only theme script is `sticky-bar.js` (16 lines, `defer`, in the footer) | Pass |
| 20 | Mobile menu | Menu button below 960 px; teal, left-aligned overlay | Menu button at 375 and 800 px; overlay teal on white, left-aligned (`375-menu-open.png`) | Pass |
| 21 | Fix 1: footer rows | One "Call or WhatsApp" row when WhatsApp = main number; separate "Main number" + "WhatsApp" rows when they differ | Same numbers: labels Call or WhatsApp / Mobile / Office / Email / Instagram. WhatsApp set to 97336429850: labels Main number / Mobile / Office / WhatsApp / Email / Instagram with `wa.me/97336429850`. Restored to the same number | Pass |
| 22 | Fix 1: footer height and tap targets | About a third shorter at 375 px; every footer link ≥ 44 px | Footer height 375 px: 1011 → **587 px (−42 %)**; 320 px: 1037 → 635 (−39 %); 1280 px: 647 → 437 (−32 %). Smallest footer link: 44 px at 320, 375 and 1280. No horizontal scroll. Email stays on one line beside its label at 375 (15 px on mobile) | Pass |
| 23 | Fix 2: desktop header gap | ≥ 24 px between the last nav item and the Call button from 960 px | 32 px at 960, 1280 and 1920 px; header stays one row; before: 8 px | Pass |
| 24 | Fix 3: Call/WhatsApp in the open mobile menu | Buttons at the bottom of the open menu, sticky-bar styles, bound to Business Info; nothing covered; hidden elsewhere | At 320, 375 and 800 px the open menu has Call (`tel:+97334448236`, white on teal) and WhatsApp (`wa.me/97334448236?text=…`, teal on chat-green), each 56 px tall at the bottom of the screen; last menu link ends at 284 px, far above the buttons. Keyboard: Tab reaches Call (yellow ring) and WhatsApp (teal ring). Hidden in the desktop menu and the footer menu. Values come from Business Info (prefilled message proves the binding) | Pass |
| 25 | After fixes: full layout re-run (320–1920) | Tests 6–9 still pass | No horizontal scroll; sticky bar < 960 only; floating button ≥ 960 only; footer never covered (584/584, 756/756, 968/968); every focusable has a ring; debug.log empty | Pass |

#### Post-merge smoke test (main folder, after merging at 81c86cf)
| # | Test | Expected | Actual | Pass/Fail |
|---|---|---|---|---|
| S1 | Directory links removed; real folders in place | `themes/ayesha-movers` and `plugins/ayesha-core` are real folders from git | Both real folders (no junction); main checkout on `main`, clean | Pass |
| S2 | Home page | HTTP 200 | 200 (About Us, Our Services, Contact Us also 200) | Pass |
| S3 | Theme active | `ayesha-movers` | `ayesha-movers`, loaded from `wp-content/themes/ayesha-movers` | Pass |
| S4 | Plugin active | `ayesha-core` active | `ayesha-core` 0.3.0 active | Pass |
| S5 | Business Info in header/footer | Settings values shown | Header Call button "+973 3444 8236" → `tel:+97334448236`; footer "Call or WhatsApp" +973 3444 8236, Mobile +973 3642 9850, Office +973 7736 0292, Email, Instagram, hours, © 2026; sticky bar and floating button → `wa.me/97334448236` | Pass |
| S6 | Mobile menu (375 px) | Shows Call/WhatsApp | Home, About Us, Our Services, Contact Us, then Call (`tel:+97334448236`) and WhatsApp (`wa.me/97334448236`) | Pass |
| S7 | debug.log | No new entries | No PHP notices or warnings. 2 informational lines from WordPress core ("Automatic updates starting…" / "…complete", 10:58 UTC), from a background update check; nothing changed (still 7.1.2). Automatic updates were then disabled (see Decisions log) | Pass (with note) |
| S8 | Pushes | `origin/main` and `origin/phase-3-foundation` = merge commit | Both 81c86cf, same as local `main` | Pass |

Before/after for the review fixes: `docs/screenshots/phase-3/compare-fix1-footer-375.png`, `compare-fix1-footer-320.png`, `compare-fix1-footer-1280.png`, `compare-fix2-header-1280.png`, `compare-fix3-menu-375.png`; originals in `before/` and `after/`, JSON-LD in `before/fix4-jsonld.json` and `after/fix4-jsonld.json`.

### Phase 4 (run 2026-10-01)
Screenshots: `docs/screenshots/phase-4/` (`full-<width>.png` at 320, 375, 414, 768 (taken with phone/tablet emulation: no scrollbar, touch) and 1280, 1920 (desktop browser); before/after of the review fixes: `compare-375.png`, `compare-1280.png`, originals in `before/`; `hero-long-number-*.png`, `hero-no-photo-*.png`, `reviews-on-*.png`). In the full-page shots the sticky bar and floating button are pinned to the page bottom (where they sit once scrolled to the end) instead of mid-page. Lighthouse reports: `docs/lighthouse/phase-4/`.

| # | Test | Expected | Actual | Pass/Fail |
|---|---|---|---|---|
| E1 | Editor: every block on Home opens without "invalid content"; change one text, one button and one image in 3 different sections, save, check front end, revert | No warnings; edits show, then revert | Run by Claude 2026-10-02 in the block editor (temporary local admin session, user-approved). Home: 120 blocks, **0 invalid, 0 "invalid content" warnings**; synced patterns CTA band (10 blocks) and Where we go (10 blocks) opened on their own: 0 invalid, 0 warnings. Edits, saved with the editor: Hero image photo 9 → photo 14; What we do lead button text → "Get a price on WhatsApp (E1 test)" (its bound WhatsApp link stayed); Why people book us H2 → "Why people book us (E1 test)". Front end showed all 3. Reverted and saved: all 120 blocks and every attribute identical to before (the editor re-saves in its own format: attribute order, blank lines); front end back to the original. Only console warning: WordPress core's own global-styles stylesheet in the editor iframe (not theme/plugin) | Pass |
| E2 | Edit the CTA band synced pattern once; change shows everywhere it is used; revert | Shows on Home (and any page using it) | Opened the synced pattern itself ("Edit original", wp_block 26), changed the H2 to "Moving soon? Message us now. (E2 test)", saved. Home showed the new heading. Home is the only page using it so far, so the theme's "Call to action band" pattern (what any other page inserts) was also rendered: it is `<!-- wp:block {"ref":26} /-->` and showed the new heading too. Reverted and saved: Home shows "Moving soon? Message us now." again; pattern 26 has the same blocks, attributes and text as the docs/content backup, and is still synced | Pass |
| E3 | Search engines panel in the page editor saves the title/description | Saved values in `<title>` and meta description | The "Search engines" panel in the Page sidebar showed the saved values with counters "55 of about 60 characters." / "153 of about 155 characters." Typed a test title and description into the panel's fields and saved: the front end's `<title>` and meta description changed to them. Typed the originals back and saved: front end exactly as design plan 7 again | Pass |
| 1 | Hero number, real number (+973 3444 8236), 320 / 375 / 768 / 1280 / 1920 | No clipping, no h-scroll; 2 lines at 320/375; 1 line at 1280/1920; whole number tappable | Hero: 72 / 72 / 125 / 104 / 104 px; lines 2 / 2 / 1 / 1 / 1; width used 255/273, 255/328, 682/714, 565/592, 565/592; no clipping; no h-scroll. CTA band: 64 / 64 / 96 / 96 / 96 px, same line pattern. A hit test at the left, middle and right of both parts lands on the `tel:+97334448236` link at every width | Pass |
| 2 | Hero number, long number (+966 55 123 4567), same widths | Same as 1 | Hero: 62 / 72 / 114 / 94 / 94 px; lines 2 / 2 / 1 / 1 / 1; width used 254/273, 294/328, 681/714, 565/592, 565/592; no clipping; no h-scroll; whole number tappable. Screenshots `hero-long-number-375.png`, `-1280.png` | Pass |
| 3 | Number size recalculates when Business Info changes | New widths without editing anything else | Changing only the setting changed the link's custom properties from `--ayesha-number-w1:5.48;--ayesha-number-w2:3.56` to `6.03;4.11`; sizes in test 2 follow | Pass |
| 4 | Business Info binding: change the main number | Hero, CTA band, header, footer, sticky bar update | Main number set to +966 55 123 4567: hero number + Call, CTA number + Call, header button, footer main number, sticky-bar Call and mobile-menu Call all showed it / `tel:+966551234567`. Settings restored from a JSON backup, then compared: identical | Pass |
| 5 | Hero without photo 9 (Image block deleted), 375 and 1280 | Looks complete; no gap | 375: photo column hidden; yellow panel, number, buttons and strip intact. 1280 (after a fix): photo column hidden, text column takes 1200 px, number grows to 128 px on one line. Screenshots `hero-no-photo-*.png`. Page restored; content identical to the backup | Pass (after fix: a desktop rule kept the empty column 46 % wide) |
| 6 | Reviews hidden with the toggle off; visible with it on; off again | Off: not in the HTML; on: 3 placeholder cards | Off: 0 matches for `ayesha-reviews` / "Placeholder". On: 3 cards "Placeholder: replace with a real customer review" (`reviews-on-375.png`, `-1280.png`). Off again: 0 matches; settings identical to the backup | Pass |
| 7 | Service WhatsApp links | Right number; URL-encoded text naming the service | 8 links in the page content, all `wa.me/97334448236`: hero + CTA band ("...a price for my move."), lead card ("...house, villa, flat or office shifting."), packing and unpacking, furniture dismantling and refitting, AC, TV or curtain removal and fitting, truck hire (Dyna or 6-wheel truck), international cargo (20ft or 40ft container). Encoded with `rawurlencode` (`%20`, `%26` for &, `%27` for ', `%28`/`%29` for brackets); each decodes back to the exact message | Pass |
| 8 | Anchors to Our Services / Contact Us | Each goes to the right place | Links: `/our-services/#house-shifting`, `#packing`, `#furniture`, `#appliances`, `#trucks`, `#cargo`; "See all services" to `/our-services/`; "Send a detailed quote request" to `/contact-us/#quote`. Both pages return 200; **the target ids don't exist yet** (Our Services is built in Phase 5, the quote form in Phase 6) | Pass (anchors pending Phases 5/6) |
| 9 | Responsive full-page screenshots | 5 widths, no h-scroll | Re-taken after the review fixes and after hiding photo 12 below 960px: `full-320/375/414/768/1280/1920.png`; page heights 6829 / 6285 / 6131 / 5862 / 4293 / 4293 px; no horizontal scroll at any width | Pass |
| 10 | Heading order | One H1, no skipped levels | Outline 1 2 3 3 3 3 3 3 2 2 2 3 3 3 3 2 2: one H1, no skips | Pass |
| 11 | Images have alt text | All | 1 image (photo 9, 560px WebP, `fetchpriority=high`, width/height set), alt "Large yellow box truck with a green cab parked outside a residential apartment building" (no ownership claim). Photos 10, 11, 13 not used | Pass |
| 12 | SEO title and meta description (design plan 7) | Exact text | `<title>` "Movers and Packers in Bahrain, 24 Hours &#124; AYESHA Movers" (55 chars); description exactly as the plan (153 chars; corrected 2026-10-02, it was miscounted as 154) | Pass |
| 13 | Animation | Chevron strip slides in once (400 ms); none with reduced motion | Normal: `ayesha-strip-in 0.4s`. With `prefers-reduced-motion: reduce`: animation `none`, transform `none` | Pass |
| 14 | Lighthouse mobile, as-is (noindex) | 90+ each | Performance **93**, Accessibility **100**, Best Practices **100**, SEO **66**. SEO is below 90 only because of "Page is blocked from indexing" (noindex on purpose until go-live). LCP 2.9 s (the H1 text; about 0.8 s is local server time with WP_DEBUG on and no page cache, the rest is render-blocking CSS under simulated slow 4G), CLS 0, TBT 70 ms | Pass (SEO 66 explained) |
| 15 | Lighthouse desktop, as-is (noindex) | 90+ each | 100 / 100 / 100 / **66** (same single SEO audit). LCP 0.6 s, CLS 0, TBT 0 | Pass (SEO 66 explained) |
| 16 | Lighthouse with "Discourage search engines" temporarily OFF | SEO 90+ | Mobile 93 / 100 / 100 / **100**; desktop 100 / 100 / 100 / **100**. Setting OFF at 12:58:46 UTC, **back ON at 12:59:23 UTC**: `blog_public` = 0 and the page sends `noindex, nofollow` again | Pass |
| 17 | `php -l` | No syntax errors | 24 PHP files (theme, plugin, docs/content script): no errors | Pass |
| 18 | debug.log | Nothing new | No lines from Phase 4. The file holds only 3 older entries (10:58 and 12:10 UTC, before this session started) | Pass |
| 19 | Mobile hero order (design plan 3.1) | Yellow text panel, strip, then photo on white | As expected at 320/375/768; desktop: photo on the yellow, bottom edge on the strip | Pass |
| 20 | Desktop hero row | Quote link beside the buttons | After a fix: link and buttons share the same vertical middle (582.5 px) | Pass (after fix) |
| 21 | Review fix 1: mobile gutters with real phone emulation (no scrollbar, touch, iPhone user agent) at 320 / 375 / 414 | Equal 16px gutter on both sides; nothing touches an edge | Checked every heading, paragraph, list item, button, card, FAQ box, image and column in the page content. Narrowest gutter left / right: 320: 16.0 / 16.0; 375: 16.0 / 16.0; 414: 16.3 / 16.3 (the theme's fluid side padding starts to grow above 375). No element touches either edge (the hero's yellow background is full-bleed by design; its content is inset 16px). Cause of the reported 2px: the earlier 375px screenshots were taken in a desktop browser whose 15px scrollbar takes space from the right. Measured against `visualViewport.width`, because the emulator reports 375.2 / 414.4 px even for a blank page | Pass (no CSS change needed; screenshots re-taken) |
| 22 | Review fix 2: photo 12 under the lead card | Desktop: fills the gap on the left (on phones: see test 27) | 1280: photo at 366 x 480 (natural size, WebP), alt "Small white pickup truck loaded with boxes and furniture outside a building"; the left column's content now ends about 30 px below the last row's link (before: the card ended about 480 px above it). 375: one `wp-image-12` on the page, between the lead card and the rows | Pass |
| 23 | Review fix 3: Where we go, route across the full width | Each label at most 2 lines at 1280 | Heading and intro on top, route below across 1200px. Lines per label at 1280 and 1920: Your door 1, Manama and every city in Bahrain 2, Mina Salman, Khalifa Bin Salman Port and the airport 2 (was 6), Saudi Arabia 1, All GCC countries 1, UK, USA, Canada and worldwide 1. (At 960px the long label takes 3 lines) | Pass |
| 24 | Review fix 4: FAQ truck question | Question and answer match | "Can I hire a truck for 8 hours or a full day?" / "Yes. You can hire a Dyna or a 6-wheel truck for 8 hours or for a full day. We also do runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots such as DHL, Aramex and GLS." | Pass |
| 25 | Hero number tests re-run after the CSS changes | Same results as tests 1-2 | Real and long number at 320 / 375 / 768 / 1280 / 1920: identical font sizes, line counts and widths to tests 1 and 2; no clipping, no h-scroll, whole number tappable. Business Info restored and identical to the backup | Pass |
| 26 | Patterns still valid block markup after the fixes | Parse and serialize back unchanged | what-we-do, where-we-go, questions, why-and-questions: round-trip identical (the lead card's `&` now stored as `\u0026`, as the editor saves it) | Pass |
| 27 | Photo 12 hidden below 960px and not downloaded there | Phones/tablets: hidden, never requested; desktop: shown | Page scrolled from top to bottom while recording image requests. 375 (phone emulation): figure `display: none`, photo 12 never requested; the only image request was photo 9. 768 (tablet emulation): same. 1280: shown, requested and loaded. HTML: hero photo `fetchpriority="high"` without `loading`, photo 12 `loading="lazy"` (before the fix WordPress gave it no `loading` attribute, so it was eager). `full-375.png` re-taken: page 476px shorter. `docs/content/home.html` re-exported: unchanged (CSS and plugin change only, no content change) | Pass |
| 28 | "What we do" below 960px: one column | Stacked at 782, 860 and 959px; side by side with the photo from 960px | 781 / 782 / 860 / 959px: lead card above the rows, both full width (727 / 728 / 804 / 902px), photo hidden, no horizontal scroll. 960 and 1280px: side by side with the photo (unchanged). Screenshot `what-we-do-860.png` | Pass |

#### Post-merge smoke test (main folder, after merging at f2105b4, 2026-10-02)
| # | Test | Expected | Actual | Pass/Fail |
|---|---|---|---|---|
| S1 | Junctions removed; real folders from git | `themes/ayesha-movers` and `plugins/ayesha-core` are real folders | Both junctions removed with `rmdir` (links only; the worktree's files untouched), folders restored with `git checkout`, then merged: no junctions left, versions 0.4.0 / 0.4.0, main checkout clean | Pass |
| S2 | Home page | HTTP 200 | 200 (About Us, Our Services, Contact Us also 200); title "Movers and Packers in Bahrain, 24 Hours \| AYESHA Movers" | Pass |
| S3 | Hero number from Business Info | +973 3444 8236, tap to call | `tel:+97334448236` "+973 3444 8236"; hero buttons `wa.me/97334448236` and `tel:+97334448236` | Pass |
| S4 | CTA band from Business Info | Heading, number, hours, buttons | "Moving soon? Message us now."; number `tel:+97334448236` "+973 3444 8236"; "Open 24 hours, every day"; Call and WhatsApp buttons with the same number | Pass |
| S5 | Footer from Business Info | All numbers, email, Instagram, hours | `tel:+97334448236`, `tel:+97336429850`, `tel:+97377360292`, `mailto:ayeshamoversbh786@gmail.com`, Instagram link; "Open 24 hours, every day"; "Also trading as AYESHA Cargo Handling". Sticky bar `tel:` + `wa.me` | Pass |
| S6 | Theme and plugin active from the main folder | ayesha-movers active, ayesha-core active | Theme `ayesha-movers` from `wp-content/themes/ayesha-movers`; `ayesha-core` active 0.4.0 | Pass |
| S7 | Reviews hidden | Not on the page | Not in the HTML (toggle off) | Pass |
| S8 | debug.log | No new entries | 3432 bytes / 25 lines before and after the merge and smoke test. (The last 2 entries, 17:00 UTC 2026-10-02, are "connection refused" warnings from WP-CLI while MySQL was stopped, before the user restarted XAMPP) | Pass |
| S9 | Pushes | `origin/main` = `origin/phase-4-home` = merge commit | Both f2105b4 before this PROGRESS commit | Pass |

