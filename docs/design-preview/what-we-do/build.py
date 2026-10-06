"""Static preview of three layouts for the Home "What we do" section.

Takes the live Home page (home-src.html, rendered by the current theme) and swaps only the
"What we do" section for each option's markup, built from services.json (the section's exact
words and links, extracted from the live page). Each option's markup is what the section's
blocks would render to (groups, headings, paragraphs, a list, buttons, the existing image), so
every part stays an ordinary editable block when it is built.

Run: python build.py  ->  option-a.html, option-b.html, option-c.html
"""
import json
import re

src = open('home-src.html', encoding='utf-8').read()
d = json.load(open('services.json', encoding='utf-8'))
start = src.index('<section class="wp-block-group alignfull is-style-teal-panel ayesha-section ayesha-services')
end = src.index('</section>', start) + len('</section>')

ANCHOR_ICON = {
    'house-shifting': 'house', 'packing': 'box', 'furniture': 'sofa',
    'appliances': 'tv', 'trucks': 'truck', 'cargo': 'container',
}


def anchor(href):
    return href.split('#')[-1]


def services():
    lead = d['lead']
    yield {'href': lead['href'], 'title': lead['title'], 'text': lead['text'], 'wa': lead['wa'], 'wa_text': 'Ask on WhatsApp', 'lead': True}
    for r in d['rows']:
        yield dict(r, lead=False)


def wa_link(href, text, cls='is-style-text-link'):
    return ('<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">'
            '<div class="wp-block-button ' + cls + '"><a class="wp-block-button__link wp-element-button" href="' + href + '">' + text + '</a></div></div>')


def card(s, cls, extra=''):
    a = anchor(s['href'])
    return ('<div class="wp-block-group wwd-card ' + cls + '" data-icon="' + ANCHOR_ICON[a] + '">'
            '<h3 class="wp-block-heading"><a href="' + s['href'] + '">' + s['title'] + '</a></h3>'
            '<p class="wp-block-paragraph">' + s['text'] + '</p>' + extra
            + wa_link(s['wa'], s['wa_text']) + '</div>')


def checklist():
    return ('<p class="ayesha-service-lead__label wp-block-paragraph">' + d['lead']['label'] + '</p>'
            '<ul class="wp-block-list ayesha-checklist">' + ''.join('<li>' + i + '</li>' for i in d['lead']['items']) + '</ul>')


def all_link(cls):
    return ('<div class="wp-block-buttons wwd-all is-layout-flex wp-block-buttons-is-layout-flex"><div class="wp-block-button ' + cls + '">'
            '<a class="wp-block-button__link wp-element-button" href="' + d['all']['href'] + '">' + d['all']['text'] + '</a></div></div>')


def section(cls, inner):
    return ('<section class="wp-block-group alignfull ayesha-section ayesha-services wwd ' + cls + ' has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" '
            'style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">' + inner + '</section>')


h2 = '<h2 class="wp-block-heading">' + d['h2'] + '</h2>'

# A) Six equal cards, 3 x 2, light grey band. The house-shifting list and photo 12 are not on Home.
opt_a = section('wwd--a', h2 + '<div class="wp-block-group wwd-grid">' + ''.join(card(s, 'wwd-card--a') for s in services()) + '</div>' + all_link('is-style-outline'))

# B) Navy band: house shifting as a wide feature card (text + list beside photo 12), then the five other services in a row.
lead = d['lead']
feature = ('<div class="wp-block-group wwd-feature" data-icon="house"><div class="wp-block-columns wwd-feature__cols is-layout-flex wp-block-columns-is-layout-flex">'
           '<div class="wp-block-column wwd-feature__text is-layout-flow">'
           '<h3 class="wp-block-heading"><a href="' + lead['href'] + '">' + lead['title'] + '</a></h3>'
           '<p class="wp-block-paragraph">' + lead['text'] + '</p>' + checklist()
           + wa_link(lead['wa'], lead['wa_text'], 'is-style-whatsapp') + '</div>'
           '<div class="wp-block-column wwd-feature__photo is-layout-flow">' + d['photo'] + '</div></div></div>')
rows_b = '<div class="wp-block-group wwd-row">' + ''.join(card(s, 'wwd-card--b') for s in services() if not s['lead']) + '</div>'
opt_b = section('is-style-teal-panel wwd--b', h2 + feature + rows_b + all_link('is-style-text-link wwd-all-b'))

# C) Two columns: heading, a short intro (PROPOSED wording, not in the page yet), the "See all services"
#    button and photo 12 on the left; six compact cards, 2 x 3, on the right. The list is not on Home.
intro = '<p class="wp-block-paragraph wwd-intro">Labour, trucks and carpenters from one team, for moves across Bahrain and to the GCC.</p>'
left = ('<div class="wp-block-column wwd-split__left is-layout-flow">' + h2 + intro + all_link('is-style-yellow')
        + d['photo'].replace('ayesha-services__photo', 'ayesha-services__photo wwd-split__photo') + '</div>')
right = '<div class="wp-block-column wwd-split__right is-layout-flow"><div class="wp-block-group wwd-grid2">' + ''.join(card(s, 'wwd-card--c') for s in services()) + '</div></div>'
opt_c = section('wwd--c', '<div class="wp-block-columns wwd-split is-layout-flex wp-block-columns-is-layout-flex">' + left + right + '</div>')

CSS = open('options.css', encoding='utf-8').read()
for name, markup in (('a', opt_a), ('b', opt_b), ('c', opt_c)):
    page = src[:start] + markup + src[end:]
    page = page.replace('</head>', '<style id="wwd-preview">' + CSS + '</style>\n<meta name="robots" content="noindex">\n</head>', 1)
    page = page.replace('<title>', '<title>[Preview ' + name.upper() + '] ', 1)
    open('option-' + name + '.html', 'w', encoding='utf-8').write(page)
    words = re.sub(r'<[^>]+>', ' ', markup)
    print(name, 'built;', 'list' if 'ayesha-checklist' in markup else 'no list', '/', 'photo 12' if 'wp-image-12' in markup else 'no photo')
