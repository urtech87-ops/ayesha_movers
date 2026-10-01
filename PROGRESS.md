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
| wp-config extras | `WP_ENVIRONMENT_TYPE=local`, `WP_DEBUG` on, log to `wp-content/debug.log`, display off, `DISALLOW_FILE_EDIT` |
| Plugins | WP Mail Logging 1.17.0 (active), WP Mail SMTP 4.10.0 (inactive) |
| Themes | Twenty Twenty-Five 1.5 (active for now, fallback) |
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
| 2. Design plan | phase-2-design | **Plan written (docs/design-plan.md); pushed; waiting for approval** |
| 3. Foundation (theme, plugin, header/footer) | phase-3-foundation | Not started |
| 4. Home page | phase-4-home | Not started |
| 5. Our Services + About Us | phase-5-services-about | Not started |
| 6. Contact Us + quote form + email | phase-6-contact | Not started |
| 7. Full QA, SEO, performance, handover | phase-7-qa | Not started |

## Current phase + next step
- **Current:** Phase 1 approved and merged into `main`. Waiting for the Phase 2 prompt.
- **Next step:** Phase 2 (design plan, no code) on branch `phase-2-design`. Only use photos marked OK in Media status; ID 10 is placeholder only; never use IDs 11 and 13.

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
- 2026-10-01: Phase 2 design direction: palette from client truck photo 9 (box yellow #F2B705, cab teal #0F4D4A for all text, tarmac #3F6F6B, concrete #E8EBE9, paper #FFFFFF, WhatsApp green #25D366 with teal text). One font: Archivo variable (condensed 800 for display, normal width for body), self-hosted. Hero = the phone number painted large on a yellow panel with the truck's chevron tape. Full plan: docs/design-plan.md.
- 2026-10-01: Floating WhatsApp button is desktop-only; mobile uses the sticky Call/WhatsApp bar.
- 2026-10-01: Competitor check: only bahrainmovers.com was reachable; gulfmoversbahrain.com (DNS error) and bhmoversbahrain.com (403) were not.
- 2026-10-01: Outside this session, `phase-1-setup` was renamed to `main` and pushed to origin. `phase-1-setup` was recreated from `main` for the final Phase 1 commit, then fast-forward merged into `main`.

## Open questions for the client
- How are prices worked out (per truck, per hour, per room)? Needed for an honest FAQ answer; not invented in the plan
- Is the man in photo 15 the GM or crew, and are the trucks in photos 9, 14, 15 AYESHA's own? (Affects alt text and captions)
- Founding year / years in business ("decades" vs "Est. 2026")
- Business address or office location, if he wants one shown
- Logo (none known; we'll use a wordmark for now)
- Real customer reviews (Google/Instagram) to replace placeholders
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
- The default sender `wordpress@localhost` is rejected by PHPMailer. Phase 6: `ayesha-core` must set `wp_mail_from` / `wp_mail_from_name` (or WP Mail SMTP must, at go-live). Locally, mail is logged but not sent (no mail server).
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
