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
