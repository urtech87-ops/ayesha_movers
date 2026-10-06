"""Builds the Phase 7b logo options (A, B, C) as SVG files with the text as outlines.

Text: the site's own Roboto (theme fonts, 400/500/700), converted to paths with
fontTools, kerned with Roboto's GPOS pair kerning. No font is needed to view the files.
Marks: original carton drawings on a 48 x 48 grid.

Run from this folder:  python build.py
Writes: logo-{a,b,c}-header.svg, logo-{a,b,c}-footer.svg, mark-{a,b,c}.svg,
        mark-{a,b,c}-footer.svg, favicon-{a,b,c}.svg, and variant-*.html + index.html (see preview.py).
"""
import math
import os
from fontTools.ttLib import TTFont
from fontTools.pens.svgPathPen import SVGPathPen
from fontTools.pens.transformPen import TransformPen

HERE = os.path.dirname(os.path.abspath(__file__))
FONTS = os.path.join(HERE, '..', '..', '..', 'wp-content', 'themes', 'ayesha-movers', 'assets', 'fonts')

NAVY = '#0C1239'
GOLD = '#F2B705'
WHITE = '#FFFFFF'
SOFT = '#C5C9DA'   # the site's soft text on navy
DEEP_GOLD = '#8A6500'  # gold darkened until it passes AA on white (header text only)


# ---------------------------------------------------------------- contrast
def _lum(hex_):
    c = [int(hex_[i:i + 2], 16) / 255 for i in (1, 3, 5)]
    c = [v / 12.92 if v <= 0.03928 else ((v + 0.055) / 1.055) ** 2.4 for v in c]
    return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2]


def contrast(a, b):
    la, lb = sorted((_lum(a), _lum(b)), reverse=True)
    return (la + 0.05) / (lb + 0.05)


# ---------------------------------------------------------------- fonts
class Face:
    def __init__(self, weight):
        self.font = TTFont(os.path.join(FONTS, 'roboto-latin-%d.woff2' % weight))
        self.upem = self.font['head'].unitsPerEm
        self.cmap = self.font.getBestCmap()
        self.glyphs = self.font.getGlyphSet()
        self.hmtx = self.font['hmtx']
        self._kern = self._kern_lookups()

    def _kern_lookups(self):
        if 'GPOS' not in self.font:
            return []
        gpos = self.font['GPOS'].table
        idx = set()
        for fr in gpos.FeatureList.FeatureRecord:
            if fr.FeatureTag == 'kern':
                idx.update(fr.Feature.LookupListIndex)
        subs = []
        for i in sorted(idx):
            lk = gpos.LookupList.Lookup[i]
            for st in lk.SubTable:
                if lk.LookupType == 9:
                    st = st.ExtSubTable
                if hasattr(st, 'PairSet') or hasattr(st, 'Class1Record'):
                    subs.append(st)
        return subs

    def kern(self, left, right):
        for st in self._kern:
            cov = st.Coverage.glyphs
            if left not in cov:
                continue
            if st.Format == 1:
                ps = st.PairSet[cov.index(left)]
                for rec in ps.PairValueRecord:
                    if rec.SecondGlyph == right:
                        return getattr(rec.Value1, 'XAdvance', 0) or 0
            elif st.Format == 2:
                c1 = st.ClassDef1.classDefs.get(left, 0)
                c2 = st.ClassDef2.classDefs.get(right, 0)
                v = st.Class1Record[c1].Class2Record[c2].Value1
                adv = getattr(v, 'XAdvance', 0) if v else 0
                if adv:
                    return adv
        return 0

    def run(self, text, size, x, y, tracking=0.0):
        """Returns (path data, advance) for text set at size px with its baseline at y."""
        s = size / self.upem
        d = []
        pen_x = 0.0
        prev = None
        for ch in text:
            g = self.cmap[ord(ch)]
            if prev:
                pen_x += self.kern(prev, g)
            pen = SVGPathPen(self.glyphs, ntos=lambda v: ('%.2f' % v).rstrip('0').rstrip('.'))
            tp = TransformPen(pen, (s, 0, 0, -s, x + pen_x * s, y))
            self.glyphs[g].draw(tp)
            d.append(pen.getCommands())
            pen_x += self.hmtx[g][0] + tracking * self.upem
            prev = g
        return ''.join(d), pen_x * s - tracking * size


ROBOTO = {w: Face(w) for w in (400, 500, 700)}


def cap_height(size):
    return ROBOTO[700].font['OS/2'].sCapHeight / ROBOTO[700].upem * size


# ---------------------------------------------------------------- geometry helpers
def pts(ps):
    return ' '.join('%.2f,%.2f' % p for p in ps)


def lerp(a, b, t):
    return (a[0] + (b[0] - a[0]) * t, a[1] + (b[1] - a[1]) * t)


def add(a, b, k=1.0):
    return (a[0] + b[0] * k, a[1] + b[1] * k)


def unit(a, b):
    dx, dy = b[0] - a[0], b[1] - a[1]
    n = math.hypot(dx, dy)
    return (dx / n, dy / n)


# ---------------------------------------------------------------- marks (48 x 48)
# Every mark is drawn in two colours (body, accent) and its gaps are real cut-outs
# (an SVG mask), so the same drawing works on white, on navy and on a browser tab.

def mark_a(uid, body, accent):
    """One carton in a three-quarter view, gold tape along the lid seam and down the front."""
    tl, tf, tr, tb = (3, 14.5), (26, 21.5), (45, 12.5), (22, 6)       # lid: left, front, right, back
    bl, bf, br = (3, 36.5), (26, 44.5), (45, 34)                        # bottom corners
    w = 3.4                                                              # half the tape width
    # The seam runs across the lid from the middle of the front-left edge to the middle of the back-right edge.
    m1, m2 = lerp(tl, tf, 0.5), lerp(tb, tr, 0.5)
    u = unit(tl, tf)
    tape_top = [add(m1, u, -w), add(m2, u, -w), add(m2, u, w), add(m1, u, w)]
    drop = 12.5                                                          # tape runs down the front face
    tape_front = [add(m1, u, -w), add(m1, u, w), add(add(m1, u, w), (0, drop)), add(add(m1, u, -w), (0, drop))]
    cuts = ''.join('<polyline points="%s"/>' % pts(line) for line in ([tl, tf, tr], [tf, bf]))
    return (
        '<mask id="%s" maskUnits="userSpaceOnUse" x="0" y="0" width="48" height="48">'
        '<rect width="48" height="48" fill="#fff"/>'
        '<g fill="none" stroke="#000" stroke-width="1.7" stroke-linejoin="round">%s</g></mask>'
        '<g mask="url(#%s)">'
        '<polygon fill="%s" points="%s"/>'
        '<polygon fill="%s" points="%s"/><polygon fill="%s" points="%s"/>'
        '</g>'
    ) % (uid, cuts, uid, body, pts([tl, tb, tr, br, bf, bl]),
         accent, pts(tape_top), accent, pts(tape_front))


def mark_b(uid, body, accent, top_body=None, top_tape=None):
    """Three cartons stacked: two below, one on top; each has its tape strip over the lid seam."""
    top_body = top_body or accent
    top_tape = top_tape  # None = the tape on the top carton is a cut-out
    boxes = [((1, 26.5, 22.5, 20), body, accent), ((24.5, 26.5, 22.5, 20), body, accent),
             ((12.75, 4.5, 22.5, 20), top_body, top_tape)]
    out, mask_cuts = [], []
    for (x, y, w, h), fill, tape in boxes:
        out.append('<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" rx="1.5" fill="%s"/>' % (x, y, w, h, fill))
        tx, tw, th = x + w / 2 - 2.6, 5.2, h * 0.48
        if tape:
            out.append('<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" fill="%s"/>' % (tx, y, tw, th, tape))
        else:
            mask_cuts.append('<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f"/>' % (tx, y - 1, tw, th + 1))
    if not mask_cuts:
        return ''.join(out)
    return ('<mask id="%s" maskUnits="userSpaceOnUse" x="0" y="0" width="48" height="48">'
            '<rect width="48" height="48" fill="#fff"/><g fill="#000">%s</g></mask>'
            '<g mask="url(#%s)">%s</g>') % (uid, ''.join(mask_cuts), uid, ''.join(out))


def mark_c(uid, body, accent):
    """An open carton whose two lid flaps lean together into a roof: the box is also a home."""
    # Body with a hand-hole slot cut out.
    body_rect = (5, 25, 38, 21)
    slot = (17, 30.5, 14, 4.6)
    # Flaps: slabs hinged at the top corners of the box, rising to a gap at the peak.
    t = 5.2   # slab thickness
    left_out, left_peak = (5, 23.2), (22.3, 7.6)
    right_out, right_peak = (43, 23.2), (25.7, 7.6)
    def slab(a, b):
        ux, uy = unit(a, b)
        nx, ny = uy, -ux          # normal pointing up/outward for the left slab
        if a[0] > b[0]:
            nx, ny = -nx, -ny
        return [a, b, (b[0] + nx * t, b[1] + ny * t), (a[0] + nx * t, a[1] + ny * t)]
    left, right = slab(left_out, left_peak), slab(right_out, right_peak)
    return (
        '<mask id="%s" maskUnits="userSpaceOnUse" x="0" y="0" width="48" height="48">'
        '<rect width="48" height="48" fill="#fff"/>'
        '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" rx="2.1" fill="#000"/></mask>'
        '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" rx="1.5" fill="%s" mask="url(#%s)"/>'
        '<polygon fill="%s" points="%s"/><polygon fill="%s" points="%s"/>'
    ) % ((uid,) + slot + body_rect + (body, uid, accent, pts(left), accent, pts(right)))


MARKS = {'a': mark_a, 'b': mark_b, 'c': mark_c}


# ---------------------------------------------------------------- type treatments
# Line 1 "AYESHA Movers", line 2 "& Packers". The block is 48 units tall, like the mark:
# line 1's capitals start at the mark's top, line 2 sits on the mark's bottom.

def type_block(variant, x, ink, line2_ink, strip=None):
    """Returns (svg, width). ink = colour of line 1; line2_ink = colour of line 2."""
    if variant == 'a':
        size1, size2 = 20.5, 20.5
        base1, base2 = 5.5 + cap_height(size1), 44.5
        d1, w1 = ROBOTO[700].run('AYESHA', size1, x, base1, 0.02)
        sp = size1 * 0.26
        d2, w2 = ROBOTO[500].run('Movers', size1, x + w1 + sp, base1)
        d3, w3 = ROBOTO[400].run('& Packers', size2, x, base2)
        width = max(w1 + sp + w2, w3)
        return ('<path fill="%s" d="%s%s"/><path fill="%s" d="%s"/>' % (ink, d1, d2, line2_ink, d3)), width
    if variant == 'b':
        size1, size2 = 21, 19
        base1, base2 = 5 + cap_height(size1), 44.5
        d1, w1 = ROBOTO[700].run('AYESHA', size1, x, base1, 0.01)
        sp = size1 * 0.25
        d2, w2 = ROBOTO[400].run('Movers', size1, x + w1 + sp, base1)
        d3, w3 = ROBOTO[500].run('& Packers', size2, x, base2, 0.02)
        width = max(w1 + sp + w2, w3)
        return ('<path fill="%s" d="%s%s"/><path fill="%s" d="%s"/>' % (ink, d1, d2, line2_ink, d3)), width
    # c: line 2 printed on a strip of gold packing tape.
    size1, size2 = 21.5, 15.5
    base1 = 4 + cap_height(size1)
    d1, w1 = ROBOTO[700].run('AYESHA', size1, x, base1, 0.015)
    sp = size1 * 0.25
    d2, w2 = ROBOTO[400].run('Movers', size1, x + w1 + sp, base1)
    pad = 5.5
    strip_y, strip_h = 26.5, 21.5
    base2 = strip_y + strip_h / 2 + cap_height(size2) / 2
    d3, w3 = ROBOTO[500].run('& Packers', size2, x + pad, base2, 0.03)
    line1 = w1 + sp + w2
    sw = w3 + pad * 2
    width = max(line1, sw)
    # Torn-tape ends: a small zigzag on the right end.
    sx = x
    ex = x + sw
    zig = []
    steps = 4
    for i in range(steps + 1):
        yy = strip_y + strip_h * i / steps
        zig.append((ex + (1.6 if i % 2 else 0), yy))
    poly = [(sx, strip_y)] + zig + [(sx, strip_y + strip_h)]
    return ('<path fill="%s" d="%s%s"/><polygon fill="%s" points="%s"/><path fill="%s" d="%s"/>'
            % (ink, d1, d2, strip, pts(poly), line2_ink, d3)), max(width, sw + 1.6)


# ---------------------------------------------------------------- colour sets
def colours(variant, place):
    """place: header (navy text on white) or footer (white/gold on navy)."""
    if variant == 'a':
        if place == 'header':
            return dict(body=NAVY, accent=GOLD, ink=NAVY, line2=NAVY)
        return dict(body=WHITE, accent=GOLD, ink=WHITE, line2=WHITE)
    if variant == 'b':
        if place == 'header':
            return dict(body=NAVY, accent=GOLD, top_body=GOLD, top_tape=None, ink=NAVY, line2=DEEP_GOLD)
        return dict(body=WHITE, accent=GOLD, top_body=GOLD, top_tape=None, ink=WHITE, line2=GOLD)
    if place == 'header':
        return dict(body=NAVY, accent=GOLD, ink=NAVY, line2=NAVY, strip=GOLD)
    return dict(body=WHITE, accent=GOLD, ink=WHITE, line2=NAVY, strip=GOLD)


def mark_svg(variant, uid, c):
    if variant == 'b':
        return mark_b(uid, c['body'], c['accent'], c.get('top_body'), c.get('top_tape'))
    return MARKS[variant](uid, c['body'], c['accent'])


TITLE = 'AYESHA Movers &amp; Packers'


def logo(variant, place):
    c = colours(variant, place)
    gap = 9
    uid = 'am-%s-%s' % (variant, place)
    text, tw = type_block(variant, 48 + gap, c['ink'], c['line2'], c.get('strip'))
    w = math.ceil(48 + gap + tw + 0.5)
    svg = ('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d 48" width="%d" height="48" role="img" aria-labelledby="%s-t">'
           '<title id="%s-t">%s</title>%s%s</svg>\n') % (w, w, uid, uid, TITLE, mark_svg(variant, uid + '-m', c), text)
    return svg, w


def mark_only(variant, place):
    c = colours(variant, place)
    uid = 'am-%s-mark-%s' % (variant, place)
    return ('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48" role="img" aria-labelledby="%s-t">'
            '<title id="%s-t">%s</title>%s</svg>\n') % (uid, uid, TITLE, mark_svg(variant, uid + '-m', c))


def favicon(variant):
    """The mark on a navy tile (white body, gold tape), so it reads on light and dark browser tabs."""
    c = colours(variant, 'footer')
    uid = 'am-%s-fav' % variant
    # Mark scaled into the tile with a small margin.
    return ('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48" height="48">'
            '<rect width="48" height="48" rx="9" fill="%s"/>'
            '<g transform="translate(5.5 5.5) scale(0.7708)">%s</g></svg>\n') % (NAVY, mark_svg(variant, uid + '-m', c))


def main():
    report = []
    for v in 'abc':
        for place in ('header', 'footer'):
            svg, w = logo(v, place)
            open(os.path.join(HERE, 'logo-%s-%s.svg' % (v, place)), 'w', encoding='utf-8').write(svg)
            report.append('logo-%s-%s.svg  viewBox 0 0 %d 48  (%.0f x 44 px on phones, %.0f x 56 px on computers)'
                          % (v, place, w, w * 44 / 48, w * 56 / 48))
        open(os.path.join(HERE, 'mark-%s.svg' % v), 'w', encoding='utf-8').write(mark_only(v, 'header'))
        open(os.path.join(HERE, 'mark-%s-footer.svg' % v), 'w', encoding='utf-8').write(mark_only(v, 'footer'))
        open(os.path.join(HERE, 'favicon-%s.svg' % v), 'w', encoding='utf-8').write(favicon(v))
    pairs = [('navy on white', NAVY, WHITE), ('white on navy', WHITE, NAVY), ('gold on navy', GOLD, NAVY),
             ('navy on gold', NAVY, GOLD), ('deep gold on white (B header line 2)', DEEP_GOLD, WHITE),
             ('gold on white (not used for text)', GOLD, WHITE)]
    for name, a, b in pairs:
        report.append('contrast %-40s %.2f:1' % (name, contrast(a, b)))
    print('\n'.join(report))


if __name__ == '__main__':
    main()
