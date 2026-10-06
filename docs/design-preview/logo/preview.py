"""Builds variant-{a,b,c}.html from the live Home page (http://localhost/ayesha-movers/):
only the header and footer Site Title blocks are swapped for the logo, in the same
markup the core Site Logo block prints. index.html shows all three side by side.

Run after build.py:  python preview.py
"""
import os
import re
import urllib.request

HERE = os.path.dirname(os.path.abspath(__file__))
SITE = 'http://localhost/ayesha-movers/'
ALT = 'AYESHA Movers &amp; Packers'

CSS = '''<style id="logo-preview">
/* Proposed sizes: 44px tall on phones, 56px from 960px; footer 56px. */
/* The logo may shrink (keeping its shape) so the header stays on one row on 320px phones. */
.ayesha-header .wp-block-site-logo { margin: 0; line-height: 0; flex: 0 1 auto; min-width: 0; }
.ayesha-header .wp-block-site-logo a { display: inline-block; max-width: 100%; padding-block: 0.375rem; }
.ayesha-header .wp-block-site-logo img { display: block; width: auto; height: auto; max-width: 100%; max-height: 44px; }
@media (max-width: 359.98px) { .ayesha-header__bar { gap: 0.5rem; } }
@media (min-width: 960px) { .ayesha-header .wp-block-site-logo img { height: 56px; max-height: none; } }
.ayesha-footer .wp-block-site-logo { margin: 0; line-height: 0; }
.ayesha-footer .wp-block-site-logo img { display: block; height: 56px; width: auto; }
/* Inside the comparison page's small frames only: the fixed bar and button would cover the header. */
.in-frame :is(.ayesha-sticky-bar, .ayesha-wa-float) { display: none !important; }
</style>
'''


def logo_block(src, w, h, link):
    img = '<img width="%d" height="%d" src="%s" class="custom-logo" alt="%s" decoding="async">' % (w, h, src, ALT)
    if link:
        img = '<a href="%s" class="custom-logo-link" rel="home">%s</a>' % (SITE, img)
    return '<div class="wp-block-site-logo">%s</div>' % img


def svg_width(name):
    s = open(os.path.join(HERE, name), encoding='utf-8').read()
    return int(re.search(r'viewBox="0 0 (\d+) 48"', s).group(1))


def main():
    page = urllib.request.urlopen(SITE).read().decode('utf-8')
    titles = list(re.finditer(r'<(p|div) class="[^"]*wp-block-site-title">.*?</\1>', page, re.S))
    assert len(titles) == 2, 'expected the header and footer Site Title blocks, found %d' % len(titles)
    for v in 'abc':
        hw, fw = svg_width('logo-%s-header.svg' % v), svg_width('logo-%s-footer.svg' % v)
        head = logo_block('logo-%s-header.svg' % v, hw, 48, True)
        foot = logo_block('logo-%s-footer.svg' % v, fw, 48, False)
        out = page[:titles[0].start()] + head + page[titles[0].end():titles[1].start()] + foot + page[titles[1].end():]
        out = out.replace('</head>', CSS + '<link rel="icon" href="favicon-%s.svg" type="image/svg+xml">\n</head>' % v, 1)
        out = out.replace('<title>', '<title>Logo %s preview | ' % v.upper(), 1)
        out = out.replace('</body>', '<script>if(self!==top){document.documentElement.classList.add("in-frame")}if(location.hash==="#footer"){addEventListener("load",()=>document.querySelector("footer").scrollIntoView())}</script>\n</body>', 1)
        open(os.path.join(HERE, 'variant-%s.html' % v), 'w', encoding='utf-8').write(out)
    print('variant-a/b/c.html written')


if __name__ == '__main__':
    main()
