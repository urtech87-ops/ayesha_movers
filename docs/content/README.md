# Page content backup

Page content lives in the WordPress database, not in the theme. These files are a copy of it, so the pages can be rebuilt on the live site (or after a database mishap) without retyping anything.

| File | What it is |
|---|---|
| `home.html` | The Home page's block markup, exactly as saved in the editor |
| `home-seo.json` | The Home page's search-engine title and description (the "Search engines" panel) |
| `patterns/ayesha-cta-band.html` | Synced pattern "CTA band" (the yellow band above the footer) |
| `patterns/ayesha-where-we-go.html` | Synced pattern "Where we go" (the service-area route) |
| `patterns/manifest.json` | Each synced pattern's title and its ID on the local site (used to fix the references in `home.html` on import) |
| `home-content.php` | The WP-CLI script that builds, exports and imports all of the above |

## Rebuild on another site

The AYESHA Movers theme and AYESHA Core plugin must be active, and the photos imported into the Media Library. From the WordPress root:

```bash
php wp-cli.phar eval-file path/to/docs/content/home-content.php import
```

The script:
1. creates or updates the two synced patterns (found by their slugs `ayesha-cta-band` and `ayesha-where-we-go`, so running it twice doesn't make duplicates);
2. replaces `http://localhost/ayesha-movers` with the new site's address;
3. points the Home page's `<!-- wp:block {"ref":…} /-->` references at the new pattern IDs;
4. saves the Home page (the static front page) and its search-engine title and description.

Afterwards, open the Home page in the editor once. If the hero photo is missing, the photo has a different Media Library ID on the new site: pick it again (size *Photo (560px)*).

## Update this backup

After changing the Home page or a synced pattern in the editor, run on the local site:

```bash
php wp-cli.phar eval-file .claude/worktrees/<worktree>/docs/content/home-content.php export
```

(or the same path in the main checkout), then commit the changed files.

## First build

`home-content.php build` makes the Home page from the theme's patterns (`wp-content/themes/ayesha-movers/patterns/`) and **overwrites** the Home page. It was used once in Phase 4; don't run it on a site where the client has already edited the page.
