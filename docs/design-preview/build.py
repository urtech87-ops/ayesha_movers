"""Builds the static redesign preview from the real rendered pages (src/*.html).
Only the stylesheet changes, plus: the new top info bar (Header part), the Part A
service-area wording (proposed), and the new gallery section on Our Services."""
import re, html

TOPBAR = '''<div class="wp-block-group alignfull ayesha-topbar has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
<div class="wp-block-group ayesha-topbar__inner is-layout-flex wp-block-group-is-layout-flex">
<p class="ayesha-topbar__item wp-block-paragraph"><a href="tel:+97334448236">+973 3444 8236</a></p>
<p class="ayesha-topbar__item wp-block-paragraph"><a href="mailto:ayeshamoversbh786@gmail.com">ayeshamoversbh786@gmail.com</a></p>
<p class="ayesha-topbar__item ayesha-topbar__item--hours wp-block-paragraph">Open 24 hours, every day</p>
<p class="ayesha-topbar__item wp-block-paragraph"><a href="https://www.instagram.com/ayesha_movers_packers/" target="_blank" rel="noopener">@ayesha_movers_packers</a></p>
</div>
</div>
'''

def placeholder(n):
    svg = ("<svg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'>"
           "<rect width='400' height='400' fill='%23E1E4EB'/>"
           "<text x='200' y='190' font-family='Arial' font-size='22' text-anchor='middle' fill='%234A5068'>Client ad " + str(n) + "</text>"
           "<text x='200' y='222' font-family='Arial' font-size='15' text-anchor='middle' fill='%234A5068'>docs/client-ads/ad-" + str(n) + ".jpg</text></svg>")
    return ('<figure class="wp-block-image size-large"><img loading="lazy" decoding="async" width="400" height="400" '
            'src="data:image/svg+xml;utf8,' + svg + '" alt="Placeholder for client ad ' + str(n) + ': the alt text will be the full text of the ad"/></figure>')

GALLERY = ('''<section class="wp-block-group alignfull ayesha-section ayesha-gallery has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<h2 class="wp-block-heading">Seen on our Facebook &amp; Instagram</h2>
<figure class="wp-block-gallery has-nested-images columns-3 ayesha-gallery__grid is-layout-flex wp-block-gallery-is-layout-flex">'''
 + placeholder(1) + placeholder(2) + placeholder(3) +
 '''</figure>
<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="https://www.instagram.com/ayesha_movers_packers/" target="_blank" rel="noopener">@ayesha_movers_packers</a></div>
</div>
</section>
''')

# Part A (proposed wording), applied to the rendered text.
PART_A = [
 ("From your door to anywhere in Bahrain, and on to Saudi Arabia, the rest of the GCC and the world. Day or night.",
  "From your door to anywhere in Bahrain, and on to Saudi Arabia and the rest of the GCC. Day or night."),
 ("<li>All GCC countries</li>\n<li>UK, USA, Canada and worldwide</li>", "<li>UAE, Kuwait, Qatar and Oman</li>"),
 (">Container cargo abroad<", ">Container cargo to the GCC<"),
 ("with customs documents, to Saudi Arabia, the GCC, the UK, the USA and Canada.", "with customs documents, to Saudi Arabia, the UAE, Kuwait, Qatar and Oman."),
 ("to Saudi Arabia and the other GCC countries, and to the UK, the USA, Canada and worldwide. We also handle the customs documents.",
  "to Saudi Arabia and the other GCC countries: the UAE, Kuwait, Qatar and Oman. We also handle the customs documents."),
 ("prepare the customs documents for moves abroad.", "prepare the customs documents for moves to Saudi Arabia and the rest of the GCC."),
 ("Everything for a move in Bahrain or abroad:", "Everything for a move in Bahrain or to the GCC:"),
 ('<a href="#cargo">International cargo</a>', '<a href="#cargo">GCC cargo</a>'),
 ('class="wp-block-heading">International cargo</h2>', 'class="wp-block-heading">GCC cargo</h2>'),
 ("for moves to Saudi Arabia, the GCC, the UK, the USA and Canada.", "for moves to Saudi Arabia, the UAE, Kuwait, Qatar and Oman."),
 ("<li>Moves to Saudi Arabia (KSA) and all GCC countries</li>\n<li>Moves to the UK, the USA, Canada and worldwide</li>", "<li>Moves to Saudi Arabia (KSA) and all GCC countries</li>"),
 ("Yes. We move to Saudi Arabia and all GCC countries, and to the UK, the USA, Canada and worldwide.", "Yes, within the GCC: Saudi Arabia, the UAE, Kuwait, Qatar and Oman."),
 ("price%20for%20international%20cargo", "price%20for%20GCC%20cargo"),
]

def build(name, out):
    s = open('src/' + name, encoding='utf-8').read()
    s = re.sub(r'<link rel="preload" href="[^"]+archivo[^"]+"[^>]*>\n', '', s)
    s = re.sub(r"<link rel='stylesheet' id='ayesha-theme-css'[^>]+>\n", '', s)
    s = s.replace("<link rel='stylesheet' id='ayesha-core-frontend-css'",
                  '<link rel="preload" href="fonts/roboto-latin-400.woff2" as="font" type="font/woff2" crossorigin>\n'
                  "<link rel='stylesheet' id='ayesha-core-frontend-css'", 1)
    s = s.replace('</head>', '<link rel="stylesheet" href="theme-new.css">\n<meta name="robots" content="noindex">\n</head>', 1)
    s = s.replace('<header class="wp-block-template-part">', '<header class="wp-block-template-part">\n' + TOPBAR, 1)
    for a, b in PART_A:
        s = s.replace(a, b)
    s = re.sub(r'<li>All GCC countries</li>\s*<li>UK, USA, Canada and worldwide</li>', '<li>UAE, Kuwait, Qatar and Oman</li>', s)
    s = re.sub(r'\s*<li>Moves to the UK, the USA, Canada and worldwide</li>', '', s)
    if name == 'services.html':
        marker = '<section class="wp-block-group alignfull is-style-concrete-panel ayesha-section ayesha-questions'
        assert marker in s
        s = s.replace(marker, GALLERY + marker.replace('ayesha-questions', 'ayesha-questions ayesha-svc-faq'), 1)
    body = s[s.index('<body'):]
    left = [w for w in ('the UK', 'worldwide', 'the world', 'abroad', 'International cargo', 'Canada') if w in body]
    open(out, 'w', encoding='utf-8').write(s)
    print(out, 'leftover coverage words:', left)

build('home.html', 'home.html')
build('services.html', 'services.html')
