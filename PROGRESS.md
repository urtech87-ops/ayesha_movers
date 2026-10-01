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
| Plugins | **AYESHA Core 0.3.0 (active)**, WP Mail Logging 1.17.0 (active), WP Mail SMTP 4.10.0 (inactive) |
| Themes | **AYESHA Movers 0.3.0 (active)**; Twenty Twenty-Five 1.5 (fallback) |
| Navigation | `wp_navigation` post 4 "Main menu": Home, About Us, Our Services, Contact Us + a bound Call/WhatsApp Buttons block (shown only in the open mobile menu). Used by header and footer. Lives in the database; reference copy in `docs/navigation-main-menu.html` |
| Business Info | Settings → Business Info (option `ayesha_business`) |
| Pages | Home (ID 5, static front page), About Us (6), Our Services (7), Contact Us (8) |
| Git remote | `origin` = https://github.com/urtech87-ops/ayesha_movers.git |

> **Startup note:** MySQL runs on **port 3307**. After a reboot, start **Apache + MySQL from the XAMPP Control Panel**. **Laragon must stay closed**; it grabs ports 80 and 3306 and serves a different docroot.

### Media status
| ID | File | Status | Reason |
|---|---|---|---|
| 9 | yellow-box-truck-residential-building | OK | |
| 10 | movers-carrying-white-sofa | **PLACEHOLDER ONLY** | Looks like a stock photo; replace with a real client photo before go-live (or confirm licence) |
| 11 | white-pickup-truck-cargo-rails | **DO NOT USE** | Shows phone number 0524070463 (not an AYESHA number) |
| 12 | pickup-loaded-with-household-goods | OK | |
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
| 4. Home page | phase-4-home | **In progress** (built on the local site; front-end tests done; editor tests and Lighthouse pending) |
| 5. Our Services + About Us | phase-5-services-about | Not started |
| 6. Contact Us + quote form + email | phase-6-contact | Not started |
| 7. Full QA, SEO, performance, handover | phase-7-qa | Not started |

## Current phase + next step
- **Current:** Phase 4 – Home page, on branch `phase-4-home` (git worktree `.claude/worktrees/phase-4-home-build-e1b2e8`).
- **Done so far (commits 852928b, 10b5ca3):** patterns, plugin 0.4.0, Home page built on the local site (synced patterns CTA band = ID 26, Where we go = ID 27), SEO title/description set, docs/content/ export, editing guide section 5, screenshots in docs/screenshots/phase-4/. Front-end tests passed: hero number (real + long number, 5 widths, recalculates on a settings change), binding change, hero without photo, reviews toggle, WhatsApp links, headings/alt/reduced motion, php -l, debug.log.
- **Local site:** `wp-content/themes/ayesha-movers` and `wp-content/plugins/ayesha-core` in the main checkout are **directory junctions** into the worktree (user-approved 2026-10-01). The original folders were moved, not deleted, to the session scratchpad (`…\scratchpad\main-folders\`). Before merging: remove the junctions with `rmdir` (link only), then merge in the main checkout.
- **Waiting for the user:** (1) sign in to wp-admin in the app's browser pane, so the editor tests can run (creating an admin session from WP-CLI was refused by the safety check); (2) permission to download Lighthouse from npm for the Lighthouse runs.
- **Next step after that:** editor tests (no "invalid content", edit text/button/image in 3 sections + revert, edit the CTA band synced pattern + revert), Lighthouse mobile/desktop, re-export docs/content if the editor re-saved anything, test table, push.
- **Service anchors for Phase 5** (the Home page links to them): `/our-services/#house-shifting`, `#packing`, `#furniture`, `#appliances`, `#trucks`, `#cargo`.
- **Last completed:** Phase 3 (foundation), approved and merged into `main` 2026-10-01 at 81c86cf (fast-forward); smoke test passed (see Test results › Phase 3 › Post-merge smoke test).
- **Phase 4 notes (carried over):**
  - Hero number: fluid size from the hero column's container width (design-plan section 4). Test at 320, 375, 768, 1280 and 1920 px, with the real number and with +966 55 123 4567: no overflow, one line on desktop, two lines on mobile.
  - Hero must look complete with photo 9 removed; photo 9 alt text/caption makes no ownership claim.
  - The chevron strip's one-time slide-in (design plan section 4) belongs to the hero strip; add it in Phase 4 (reduced motion is already handled globally).
  - Reviews pattern: give its outer Group the CSS class `ayesha-reviews`; AYESHA Core removes it from the page while "Show reviews section" is off.
  - Per-service WhatsApp buttons: bind `url` to `whatsapp_url` with `args.message` (see docs/editing-guide.md).

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
- 2026-10-01: Outside this session, `phase-1-setup` was renamed to `main` and pushed to origin. `phase-1-setup` was recreated from `main` for the final Phase 1 commit, then fast-forward merged into `main`.

## Open questions for the client
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
