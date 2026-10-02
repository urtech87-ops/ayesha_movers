# Page content backup

Page content lives in the WordPress database, not in the theme. These files are a copy of it, so the pages can be rebuilt on the live site (or after a database mishap) without retyping anything.

| File | What it is |
|---|---|
| `home.html` | The Home page's block markup, exactly as saved in the editor |
| `about.html` | The About Us page's block markup |
| `services.html` | The Our Services page's block markup |
| `home-seo.json`, `about-seo.json`, `services-seo.json` | Each page's search-engine title and description (the "Search engines" panel) |
| `patterns/ayesha-cta-band.html` | Synced pattern "CTA band" (the yellow band above the footer) |
| `patterns/ayesha-where-we-go.html` | Synced pattern "Where we go" (the service-area route) |
| `patterns/manifest.json` | Each synced pattern's title and its ID on the local site (used to fix the references in the pages on import) |
| `content.php` | The WP-CLI script that builds, exports and imports all of the above (was `home-content.php` until Phase 5) |

About Us and Our Services use the page template **Page with sections (heading in the page)** (`page-sections`): their H1 is a Heading block in the page instead of the page title. The script sets it on build and import.

## Rebuild on another site

The AYESHA Movers theme and AYESHA Core plugin must be active, the photos imported into the Media Library, and the pages About Us and Our Services must exist with the addresses `about-us` and `our-services` (Home is the static front page). From the WordPress root:

```bash
php wp-cli.phar eval-file path/to/docs/content/content.php import
```

The script:
1. creates or updates the two synced patterns (found by their slugs `ayesha-cta-band` and `ayesha-where-we-go`, so running it twice doesn't make duplicates);
2. replaces `http://localhost/ayesha-movers` with the new site's address;
3. points each page's `<!-- wp:block {"ref":…} /-->` references at the new pattern IDs;
4. saves Home, About Us and Our Services, their template and their search-engine title and description.

Afterwards, open each page in the editor once. If a photo is missing, it has a different Media Library ID on the new site: pick it again (size *Photo (560px)*). The pages use photo 9 and 12 (Home), 15 (About Us) and 14, cropped (Our Services).

## Update this backup

After changing a page or a synced pattern in the editor, run on the local site:

```bash
php wp-cli.phar eval-file .claude/worktrees/<worktree>/docs/content/content.php export
```

(or the same path in the main checkout), then commit the changed files.

## First build

`content.php build home|about|services` makes one page from the theme's patterns (`wp-content/themes/ayesha-movers/patterns/`) and **overwrites** that page. Home was built once in Phase 4 and About Us and Our Services once in Phase 5; don't run it on a page the client has already edited.
