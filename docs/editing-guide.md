# Editing guide: AYESHA Movers & Packers website

This guide is for the person who keeps the website up to date. You don't need any coding knowledge.

## 1. Your phone numbers, WhatsApp, email and hours: change them in one place

All contact details live on one page: **Settings → Business Info** (in the black menu on the left of the dashboard).

| Field | What it changes |
|---|---|
| Main phone number | The "Or call" line on every Home banner slide, the big number in the gold band above the footer, every **Call** button, the Call button in the header (the number itself on wide computer screens), the number in the navy bar at the very top (computers), the big number in the footer, and the **Call** button in the bar at the bottom of phone screens. The big number resizes itself to fit, even if the new number is longer |
| Second mobile number, Office number | The numbers listed in the footer and on the Contact Us page |
| WhatsApp number | Every WhatsApp button and link: footer, phone bar, the round green button on computers, the WhatsApp row on Contact Us, and the "Send this on WhatsApp too" button after a quote request. **Digits only**, with the country code, e.g. `97334448236` |
| WhatsApp opening message | The message that is already typed for the visitor when WhatsApp opens. They can change it before sending |
| Email address | The email shown on the site, **where quote requests are emailed to**, and the sender address of every email the website sends (see section 9) |
| Opening hours | The hours line in the top bar, the small line above the heading on the first Home banner slide, the footer, the gold band and the Contact Us page |
| Service areas | One place per line. Kept for later use: the "Where we go" list on the pages is edited in the editor instead (see section 5) |
| Instagram page | The Instagram link in the top bar, the footer, on Contact Us and under the ads gallery |
| Facebook page | The Facebook link in the top bar, the footer and on Contact Us (read out as "AYESHA Movers on Facebook"). **Leave it empty to hide every Facebook link** on the site |
| Business address | **Leave empty** until you want an address on the site. When you fill it in, search engines are told about it too |
| Show reviews section | Leave **off** until the example reviews have been replaced with real customer reviews. While it is off, the reviews section is hidden from visitors |
| Show stats | Leave **off** until you have typed real numbers into the Home stats row (years in business, moves completed, trucks, team members). While it is off, the row is hidden. Even when it is on, a number left empty or 0 never shows. See section 5, "Stats" |
| Show prices | Leave **off** until the Home price cards hold your real prices (or the wording you want). While it is off, the prices section is hidden. See section 5, "Prices" |
| Show contact details on Contact Us | **On**: the opening hours and the rows with the numbers, email, Instagram and Facebook show beside the quote form. Switch it **off** to show only the heading and the form. Nothing is deleted while it is off |
| Page animations | **On** (now): the words on the Home banner come in gently when the page opens, the banner photo zooms in very slowly, the facts under the banner slide slowly sideways, and the sections lower down come in once as people scroll to them (cards rise one by one, the About photo slides in from the side, the area tags pop in, the stats count up). Switch it **off** to show everything still. Visitors who have asked their phone or computer for less motion never see the animations either way. (The banner can still change slides by itself; see section 5) |
| Own share image (optional) | Leave **empty** to use the share image the site builds for you (see section 2, "The share image"). Paste a picture's File URL here to use your own instead |

Click **Save business info**. The change appears everywhere on the site straight away; you don't need to edit any page.

Tips:
- Write phone numbers the way people should read them, e.g. `+973 3444 8236`. The site builds the "tap to call" link for you.
- If a value is wrong (e.g. a WhatsApp number with too few digits), you'll see a red message and the old value is kept.

## 2. Header, footer and menu

Go to **Appearance → Editor**.

- **Patterns → Header** or **Footer**: click the part, then click any text to change it. The Save button is at the top right.
- **Top info bar** (the thin navy bar above the header, on computers only; phones have the Call/WhatsApp bar at the bottom instead): the main number, email and opening hours on the left, Instagram and Facebook on the right. All of them come from Business Info, so change them there; you can delete a line you don't want in the bar (select it in List View → Delete).
- **Navigation**: the menu used in the header and the footer. Add, remove, rename or reorder pages here; both menus update together.
- **The "Our Services" dropdown**: in the Navigation, "Our Services" holds six links, one per service, each pointing at its part of the Our Services page (`…/our-services/#house-shifting`, `#packing`, `#furniture`, `#appliances`, `#trucks`, `#cargo`). Click a link to rename it or change where it goes; use **+** inside "Our Services" to add one. The icon beside each service is added by the design from its `#…` link. On computers the list opens when the mouse is over "Our Services" or with the small arrow beside it (keyboard: Enter, Space or Arrow Down; Esc closes it); in the phone menu the arrow opens the services as a sub-list. The footer menu shows the pages only.
- **"Free quote" button** (gold, right of the header): a normal button that links to the quote form (`/contact-us/#quote`). Click it to change its words. It shows on computers and tablets; on phones the banner's "Get a free quote" button and the bottom bar do that job. The **Call** button beside it shows the main number on wide computer screens, a phone icon on phones and tablets, and is hidden on narrower computer screens where the menu needs the room.
- **Mobile Call and WhatsApp bar**: the bar at the bottom of phone screens. You can change the button words ("Call", "WhatsApp"). The links come from Business Info.
- **Footer "Call or WhatsApp" row**: when your WhatsApp number is the same as your main number, the footer shows one row, "Call or WhatsApp". If you set a different WhatsApp number in Business Info, the footer shows "Main number" and "WhatsApp" as two rows instead. In the editor you see all three rows (their names say when each one shows); the website picks the right one by itself.
- **Call and WhatsApp in the phone menu**: the Main menu (Navigation) ends with two buttons, Call and WhatsApp. They appear only when the menu is opened on a phone, at the bottom of the screen. You can change their words; the links come from Business Info.

Made a mistake? In the Editor, open the part, click the three dots (⋮) at the top right and choose **Reset** to go back to the original design.

### The logo, the browser-tab icon and the share image

The logo has two versions: **navy** for the white header and **white and gold** for the navy footer. Both are in **Media → Library** ("AYESHA Movers & Packers logo (header, navy)" and "… (footer, white and gold)"). The original drawings (SVG) and large copies are kept with the website's files, in `docs/brand/`, if a designer ever needs them.

**Header logo** (the Site Logo block):
1. **Appearance → Editor → Patterns → Header**. Click the logo.
2. In the toolbar above it, click **Replace → Open Media Library**, pick or upload the new logo and click **Select**.
3. Click **Save** (WordPress may ask you to confirm the "Site" change: click **Save** again).
- The logo always keeps the header's height (44 pixels on phones, 56 on computers) and becomes as wide as its shape needs, so use a **wide logo with little empty space around it**, at least 3 times bigger than shown (e.g. 740 × 170 pixels). A PNG with a see-through background works best.
- The logo is a link to the Home page. Its description for screen readers comes from the picture's **Alternative Text** in Media → Library; keep it "AYESHA Movers & Packers".
- To put the original back: same steps, pick "AYESHA Movers & Packers logo (header, navy)".

**Footer logo** (an Image block, because the footer needs the white version):
1. **Appearance → Editor → Patterns → Footer**. Click the logo.
2. **Replace → Open Media Library**, pick the new logo, **Select**, then **Save**.
- Use a version that reads on navy (white or light colours). Fill in its Alternative Text in the sidebar ("AYESHA Movers & Packers").
- To put the original back: pick "AYESHA Movers & Packers logo (footer, white and gold)", or use **Reset** on the Footer (⋮ menu). Reset also undoes any other change you made to the footer.

**Browser-tab icon** (also used when someone saves the site to their phone's home screen):
1. Go to **Settings → General** and find **Site Icon**.
2. Click **Change site icon**, pick or upload a **square** picture of at least **512 × 512 pixels**, crop if asked, and click **Save Changes**.
- The current icon is "AYESHA Movers & Packers site icon" (the carton on a navy square). In the header's Site Logo block, leave **"Use as site icon"** off: the icon is the carton alone, not the whole logo. When you choose another icon, the site also stops using the sharp SVG copy of the old one by itself.
- Browsers keep old icons for a while: if the tab still shows the old one, close and reopen the tab.

**The share image** (the picture shown when someone shares a link to the site on WhatsApp, Facebook and similar apps): the logo on navy with your main phone number in gold.
- It **rebuilds itself when you change the main phone number** in Settings → Business Info and click **Save business info**.
- To rebuild it by hand (e.g. after the website moves to a new server): **Settings → Business Info**, scroll to **Share image** at the bottom, click **Rebuild share image**. The picture there shows the current share image and the number it was built with.
- To use your own picture instead: upload it in **Media → Library** (1200 × 630 pixels is best), open it, click **Copy URL to clipboard**, paste it into **Own share image** on Business Info and save. Empty the field to go back to the built image.
- WhatsApp and Facebook remember a link's picture for a few days. A new number shows in new shares sooner because the picture has a new file name, but links shared before keep the old picture.

## 3. Showing a phone number, WhatsApp link or email inside a page

Some blocks are **connected** to Business Info. In the editor they show the real number, the sidebar lists them under **Attributes** (e.g. "content: Office number, tap to call"), and you can't type in them. That is deliberate: change the value in Settings → Business Info instead.

### Connect a block yourself

Works with **Paragraph**, **Heading**, **List item** and **Button** blocks.

1. Add the block (for example a Paragraph) and type anything as a placeholder.
2. With the block selected, look in the right-hand sidebar for the **Attributes** panel (click the **+** next to it if it's closed).
3. Pick the part of the block to connect:
   - Paragraph / Heading / List item: **content**
   - Button: **text** (the words on the button) and/or **url** (where it goes)
4. Choose **Business Info**, then the field you want.

Which field to pick:

| You want | Paragraph, heading or list item (content) | Button words (text) | Button link (url) |
|---|---|---|---|
| A number people can tap to call | "Main phone, tap to call (for text)" | "Main phone number" | "Main phone: call link (for buttons)" |
| The big number (two lines on phones, one on wide screens) | "Main phone, tap to call, as the big number (hero and gold band)". Give the paragraph the style *Big phone number* | | |
| A WhatsApp chat | "WhatsApp number, tap to chat (for text)" | "WhatsApp number", or type your own words | "WhatsApp: chat link (for buttons)" |
| Your email | "Email address, tap to write (for text)" | "Email address" | "Email: send link (for buttons)" |
| Opening hours | "Opening hours" | "Opening hours" | |
| Instagram | "Instagram name, tap to open (for text)" | "Instagram name" | "Instagram: page link (for buttons)" |
| Copyright line with this year | "Copyright line with the current year" | | |

Rule of thumb: **"(for buttons)" fields go in a button's url; "(for text)" fields go in paragraphs.**

To disconnect a block, open the same Attributes panel and choose **Reset**/**Disconnect**. The block becomes normal text again.

### Buttons with a ready-made message for one service (code editor)

A WhatsApp button can open with its own message, e.g. "Hi, I'd like a price for furniture dismantling". This needs one small change in the code editor (⋮ → **Code editor**). Find the button and add `"message"` next to the key:

```
"url":{"source":"ayesha/business","args":{"key":"whatsapp_url","message":"Hi, I'd like a price for furniture dismantling."}}
```

The service buttons we build in the next phases already have this.

### Shortcodes (backup method)

If a block can't be connected, add a **Shortcode** block and type one of these:

| Shortcode | Shows |
|---|---|
| `[ayesha_phone]` | Main number, tap to call |
| `[ayesha_phone which="secondary"]` / `which="office"` | Second mobile / office number |
| `[ayesha_phone link="no"]` | Number as plain text |
| `[ayesha_whatsapp_link]` | WhatsApp number, tap to chat |
| `[ayesha_whatsapp_link text="Chat on WhatsApp" message="Hi, I need a truck for a day."]` | Your own words and message |
| `[ayesha_email]` | Email, tap to write |
| `[ayesha_hours]` | Opening hours |

## 4. Colours and styles

To keep every text readable (WCAG AA), the editor offers only safe combinations:

The site's colours are **navy** and **gold**, and its font is **Roboto**.

- **Group** block → Styles: *Navy panel*, *Gold panel*, *Light grey panel* (each sets its own text colour). On a navy panel, buttons turn gold by themselves.
- **Button** block → Styles: *Fill* (navy), *WhatsApp green*, *Gold*, *Outline*, *Text link* (an underlined link with the WhatsApp or phone icon, used for "Ask on WhatsApp" under each service).
- **Paragraph** → Styles: *Big phone number* (the large bold number style).
- **Spacer** → Styles: *Gold line* (a thin gold rule). The footer has one; the Home banner has its own gold edge built in.

**Capital letters:** section headings (and the menu) show in capitals on the site, but type them normally, e.g. "Where we go". The capitals are added by the design, so the words stay easy to edit and read in the editor.

**Icons** next to the services (house, box, sofa, TV, truck, container) and next to "Why people book us" are added by the design: you don't add or pick them. Each service's icon follows its link (e.g. a service that links to `#trucks` gets the truck).

Free colour pickers are switched off on purpose: white text on WhatsApp green, or gold text on white, can't be read by many people.

Ready-made sections are under **Patterns → Ayesha Movers** in the block inserter.

## 5. The Home page

Open **Pages → Home**. Every section is made of normal blocks: click any text, button or photo to change it, then click **Save**. Open **List View** (the icon with three lines at the top left) to see the sections by name, top to bottom: Banner (with its slides), Trust strip, Stats (hidden), About us, What we do, How a move works, Prices (hidden), Why people book us, We cover all of Bahrain, Reviews (hidden), Questions, CTA band.

Each section is also in the block inserter: **+** → **Patterns** → **Ayesha Movers** (for example "Home: about us (photo and text)"), so you can put a fresh copy back if you delete one by mistake.

**Notes only you can see:** the hidden sections start with a short note on a pale yellow background ("Only you see this note, in the editor…"). Visitors never see these notes, even after the section is switched on.

### Sections shared with other pages (synced patterns)

Two sections are **synced patterns**: the gold **CTA band** ("Moving soon? Message us now.") and **Where we go**. They have a purple outline in the editor. Change one once and it changes on every page that uses it. **Where we go** is on About Us; on Home it was replaced by "We cover all of Bahrain" (see below).

- To edit: click the section, then **Edit original** in the toolbar. Make the change and save. Or open **Appearance → Editor → Patterns → Ayesha Movers**.
- Don't choose **Detach** unless you want this page to have its own separate copy. A detached copy no longer follows the shared one.
- To add one to another page: **+** (block inserter) → **Patterns** → **Ayesha Movers** → "Call to action band" or "Where we go".

### The banner (top of the page)

The banner is a **Banner slider** block with up to **3 slides**. In the editor the slides are shown one under the other ("Slide 1", "Slide 2", …), each on navy with its photo on top, so you can click anything in them. On the site they take turns in one banner.

**Each slide** has: the **photo**, a **small line** above the heading (after the gold tape mark), the **heading**, **one line of text**, the buttons **Get a free quote** (gold, goes to the quote form) and **WhatsApp us**, and the line **Or call +973 …**.
- **Change words:** click and type. On slide 1 the small line is your opening hours from Business Info (change them there). The number in "Or call" comes from Business Info → Main phone number; you can change the words "Or call".
- **Change a photo:** click the photo → **Replace** → pick or upload it. Use a **wide photo at least 1920 pixels wide**; WordPress makes the smaller copies itself. In the sidebar keep the size **Banner (full width)** and write **Alternative text** that only describes what is in the picture (don't say the people or trucks are yours unless they are). The navy shade over the photo is part of the design and keeps the white words readable on any photo, so you don't need to darken photos yourself. The people in a photo stand best on the **right**, because the words cover the left side on computers.
- **The current photos are stock photos** (from Pexels, free to use; details in `docs/image-credits.md`). Replace them with your own photos of your team and trucks when you have them.
- **Headings:** slide 1's heading is the page's main heading (**H1**); the other slides use **H2** and look the same. Keep it that way (a page should have one H1).
- **WhatsApp us** opens the chat with a message: slide 1 the general one from Business Info, slide 2 asks about truck hire, slide 3 about packing. To change a message, see "Services: Ask on WhatsApp links" below (code editor, `"message"`).
- **Add, remove or reorder slides:** open **List View**, select a "Slide" inside "Banner", then ⋮ → **Duplicate** / **Delete**, or drag it. At most 3. With only 1 slide it becomes a still banner (no arrows or dots).
- **Settings** (click "Banner" in List View, then the sidebar): **Change slides by themselves** (on now), **Time per slide** (5 seconds now; or 6, 6.5 or 7) and the name read out by screen readers ("Highlights"). The slides stop changing while the mouse is over the banner or a button in it has keyboard focus, and visitors can press **Pause** beside the dots. For visitors who ask their device for less motion they never change by themselves.
- **Animations** (the words coming in, the slow photo zoom, the fade-in of sections lower down): switch them off in Settings → Business Info → **Page animations**.

### Trust strip (the navy strip under the banner)

Six short facts with an icon: Open 24 hours, Labour included in the price, Carpenters on our team, Dyna and 6-wheel trucks, Bahrain and the GCC, Free quote. It is one **List** block: click a fact to change its words. Only write facts that are true.
- On computers the facts slide slowly sideways. They stop while the mouse is over the strip or a keyboard user is on its button, and visitors can press the small **Pause** button at the left end of the strip (it becomes **Play**). The button only appears while the strip moves, and the next page starts moving again. On phones they stand still in two columns. With **Page animations** off (Business Info), or for visitors who ask for less motion, they stand still in one row.
- **The icon** comes from the item's CSS class: select the fact, then in the sidebar **Advanced → Additional CSS class(es)**: `ayesha-trust--clock`, `ayesha-trust--tag`, `ayesha-trust--tools`, `ayesha-trust--truck`, `ayesha-trust--pin` or `ayesha-trust--quote`. A new fact (press Enter at the end of one) copies the class of the one before; change it if you want a different icon.

### Stats (hidden until you switch on "Show stats")

A row of four numbers with a label: **Years in business**, **Moves completed**, **Trucks**, **Team members**. The numbers are empty on purpose: the site never shows a number we can't prove. When you have real numbers:
1. Click the empty line above each label (it says "Type the real number") and type the number, e.g. `25` or `1,200+`. Leave a number empty if you don't want it shown: that stat is then hidden (a 0 is hidden too).
2. Change a label if you like (click and type), or delete a whole stat in List View.
3. Switch on **Show stats** in Settings → Business Info.
When people scroll to the row, each number counts up from 1 to your number (only with Page animations on). Don't type a founding year or years in business until you're sure of them.

### About us (photo and text)

The photo on the left, a small white badge on its corner, and on the right the heading, two paragraphs (from the About Us page) and the buttons **About us** (goes to the About Us page) and **Call us** (calls the main number from Business Info).
- **The badge** shows your opening hours from Business Info (change them there). If you later want it to say something else (for example how many years you have been moving homes), click the badge, open **Attributes** in the sidebar and remove the connection to "Opening hours", then type your words.
- **Change the photo:** click it → **Replace**. The current photo is a stock photo (see `docs/image-credits.md`); replace it with your own team or truck when you have one. The design crops it a little at the sides (5 × 4 shape), so keep the people near the middle.

### What we do: the six service cards

"What we do" shows the six services as cards with a photo on top (three across on computers, one under the other on phones). Each card is a group named "Service: …" in List View with: the photo, the title (it links to that service on the Our Services page), one paragraph, and two links at the foot: **Read more** (goes to the same place as the title) and **Ask on WhatsApp**. The icon on the gold tab at the top of the card is added by the design and follows the title's link (e.g. a title that links to `…/our-services/#trucks` gets the truck), so keep each link pointing at its service.

- **Change a text:** click it and type.
- **Change a photo:** click it → **Replace**. All six are stock photos for now (see `docs/image-credits.md`). Any photo works: the design crops it to the same 3 × 2 shape. Write alternative text that only says what is in the picture.
- **Read more:** if you change a card's title link, change the **Read more** link the same way (click it → link icon). Screen readers hear "Read more about [service]"; the extra words are hidden on screen.
- **Add or remove a service:** select a card in List View → ⋮ → **Duplicate** (or **Delete**). Point the new title's link at the right anchor on Our Services.
- **See all services** under the cards is a normal button.

### How a move works (four steps)

Four cards numbered 1 to 4 (the numbers count themselves, so if you move a card it is renumbered). Each card has a title, one paragraph and, on two of them, a small badge: **Free** (getting your price) and **Labour included** (packing and loading). The badge is a normal paragraph: click to change it, or delete it. Only use true facts. Under the cards: **WhatsApp us** and **Call now** (both from Business Info).
- **The icon** above each title comes from the card's CSS class (Advanced): `ayesha-step--chat`, `ayesha-step--tag`, `ayesha-step--box`, `ayesha-step--house`.

### Prices (hidden until you switch on "Show prices")

Four price cards: **Studio or 1-bedroom flat**, **2–3 bedroom flat**, **Villa**, **Office**. Each card says **Price on request**, has a tick list (now only "Labour included") and a **Get a free quote** button (goes to the quote form). When you're ready:
1. In each card, replace "Price on request" with your price or starting price (for example "From BHD …"), or keep "Price on request". **Never type BHD 0.**
2. Add what each price includes to its tick list (press Enter after "Labour included" for a new line), only true things.
3. Switch on **Show prices** in Settings → Business Info.

### Why people book us (six cards)

Six reasons in cards numbered 01–06 (the numbers count themselves and are decoration only). Click a title or sentence to change it. The icon in the gold ring comes from the card's CSS class (Advanced): `ayesha-why-card--tag`, `--team`, `--clock`, `--door`, `--shield` or `--pin` (e.g. `ayesha-why-card--clock`). Only the client's own facts: no "licensed", "insured", ratings or response times unless they are true and you can show them.

### We cover all of Bahrain (area tags)

A navy band with the places as strips of tape. It is one **List** block: click a place to change it, press Enter at the end of one to add a place, or delete one. The last strip, **Saudi Arabia and the GCC**, is solid gold because it has the CSS class `ayesha-areas__more` (Advanced); give that class to one place only. Don't add counts like "18+ areas".

### Services: "Ask on WhatsApp" links

Each service has a WhatsApp link that opens the chat with the service already typed, e.g. "Hi AYESHA Movers & Packers, I'd like a price for packing and unpacking." To change that message, use the code editor (⋮ → **Code editor**), find the service's `"message":"…"` and edit the words between the quotes (see section 3). The service titles link to the matching part of the Our Services page.

### Questions (FAQ)

One centred column of questions. Each question is a **Details** block: the question is the first line and the answer goes inside (visitors click the question, or the gold arrow, to open it). To add one, select a question, then ⋮ → **Duplicate**, and change both texts. Only answer with facts you are sure of. There is no pricing question yet, on purpose.

### Reviews (hidden until you switch on "Show reviews section")

The **What customers say** section holds three placeholder cards (with a dashed edge, so they can't be mistaken for real ones). Visitors can't see it while **Show reviews section** is off in Settings → Business Info. When you have real reviews:
1. Replace each card with a real review: the customer's own words, their name and area, the month and year, and a link to the original on Google or Instagram. Delete any card you don't have a real review for.
2. Delete the line "Placeholder: replace with a real customer review" at the top of each card: a card without it gets a solid edge instead of the dashed one.
3. You can leave the note that starts "Placeholder section."; it only shows in the editor. Or delete it.
4. Switch on **Show reviews section** in Business Info.

Never write a review yourself or change a customer's words. Don't add a star rating or "4.9 / 5" line unless it comes from your real reviews.

### Title and description in Google (Search engines)

Each page has its own title and description for search results. Open the page, click **Page** in the right-hand sidebar, and scroll to the **Search engines** panel:

- **Title in search results**: about 60 characters at most.
- **Description in search results**: about 155 characters. The counter under each box tells you when it's too long.

Click **Save**. If you leave them empty, WordPress uses the page name and Google picks the description itself. The Home description mentions the WhatsApp number as text: if the number changes, update the description too.

## 6. The Our Services page

Open **Pages → Our Services**. In **List View** you'll see: Page heading, Services (with "Jump to a service" and one group per service), the ads gallery ("Seen on our Facebook and Instagram"), Questions and the CTA band.

### Change a service

Each service is one group named "Service: …". Click any text to change it:

- **The heading** (e.g. "Packing and unpacking"). Keep its **HTML anchor**: with the heading selected, open **Advanced** in the right-hand sidebar. The anchor (`house-shifting`, `packing`, `furniture`, `appliances`, `trucks`, `cargo`) is what the Home page's service links and the "Jump to a service" list point to. If you change an anchor, change the matching links too.
- **The paragraph** under the heading.
- **What's included**: a list. Click at the end of an item and press Enter to add one; select an item and press Delete/Backspace to remove one. Only list things the team really does.
- **Ask about this on WhatsApp**: the link comes from Business Info and the chat opens with the service already typed. To change that message, use the code editor (⋮ → **Code editor**), find `"message":"…"` in that button and edit the words between the quotes (see section 3).
- **The truck photo** (Truck hire): click it, then **Replace**. Keep the alt text a plain description of what is in the photo, and don't say the truck is yours until that's confirmed. (The photo was cropped in the Media Library so the brand name on the cab doesn't show; **Edit image → Restore image** there brings back the full photo.)

Each service shows as a white card like the ones on Home (three across on wide screens, two on tablets, one on phones), with its icon in a gold circle at the top and the WhatsApp button at the foot of the card.

### The "Jump to a service" list

A row of small cards with icons under the page heading (2 across on phones, 6 on computers). Each item is a link to a heading's anchor, e.g. `#packing`. If you add a service, copy a service group (⋮ → **Duplicate**), give its heading a new anchor, and add a link to it here.

### The ads gallery: "Seen on our Facebook & Instagram"

A **Gallery** block with your ads, and a button to your Instagram page (its words and link come from Business Info). **The section is hidden on the site until the gallery has at least one picture.**

- **Add or change an ad:** click the gallery → **Add** (or click a picture → **Replace**) → upload the ad. Use a WebP or JPG about 800px wide.
- **Alt text** (in the right-hand sidebar when a picture is selected): an ad is a picture of text, so type **all the words in the ad** as its alt text (for people who can't see the picture, and for Google).
- The pictures are not cropped, so the text in each ad stays whole. Don't put an ad in a service card: the small text can't be read on a phone.

### Questions

As on the Home page: each question is a **Details** block. Answer only with facts you're sure of.

## 7. The About Us page

Open **Pages → About Us**. In **List View**: Page heading (with the photo), Who runs it + How we work, Where we go and the CTA band.

- **Heading, lead paragraph, "Who runs it", "How we work"**: click any text to change it. Each of the four "How we work" points is a small heading with a paragraph under it.
- **The photo** is in the page twice, on purpose: "Photo (computers only)" beside the heading, and "Photo (phones and tablets only)" after "How we work". Each screen shows one of them and never downloads the other. On phones the photo comes after "How we work" so the man in it isn't taken for the General Manager. To change the photo, replace **both** copies (click each, **Replace**, size *Photo (560px)*), and keep the same alt text on both. Keep the alt text a plain description of the scene: don't name the man or say whose truck it is until that's confirmed. No caption.
- **Where we go** and the gold **CTA band** are the shared (synced) sections: edit them with **Edit original** (see section 5). A change shows on every page that uses them.
- Please don't add a founding year or "years of experience" until the client has confirmed one.

Both pages use the template **Page with sections (heading in the page)**: the big heading at the top is a normal Heading block in the page, not the page's title. Renaming the page doesn't change this heading (and doesn't rename the menu item either: that is in **Appearance → Editor → Navigation**). The title and description in Google are set in the **Search engines** panel, as for Home (section 5).

## 8. The Contact Us page

Open **Pages → Contact Us**. In **List View**: **Page heading** (the navy band with "Contact us" and the opening hours), then **Contact** → Columns → the left column (**Contact rows**, shown as one white card) and the right column (**Request a free quote**, one line of text, and the **Quote request form**). On computers the two cards sit side by side and slightly overlap the navy band; the rows card stays on screen while the form scrolls. On phones the rows card comes first and the form follows.

- **Heading and the line under "Request a free quote"**: click and type.
- **Show or hide the hours and contact rows:** Settings → Business Info → **Show contact details on Contact Us** (on now). While it's off, visitors see only the heading and the form, but the hours and rows stay in the page and you can still edit them here (the editor always shows them).
- **"Call or WhatsApp" row:** when your WhatsApp number is the same as the main number, the card shows one green row "Call or WhatsApp" (tap it for WhatsApp; the small "or call" link under the number rings it) and no separate "Mobile" row for that number. If you set a different WhatsApp number in Business Info, it shows a "WhatsApp" row and a "Mobile" row instead, by itself. In the editor you see all three rows; their names say when each one shows (same rule as the footer).
- **Opening hours and the contact rows** come from **Settings → Business Info** (see section 1): you can't type in the numbers, email or Instagram name here. Each row has a small label above the value ("WhatsApp", "Mobile", "Office", …); click a label to change its words. The whole row is the link, and its icon (phone, WhatsApp, envelope, Instagram) is chosen by the site from the kind of link.
- To remove a row, select its group in List View (e.g. "Office") and delete it. To add one, duplicate a row (⋮ → **Duplicate**) and connect the copy's value to another Business Info field (section 3: Attributes → content).
- **Keep the heading "Request a free quote" and its HTML anchor `quote`** (Advanced in the sidebar): the Home page's "Send a detailed quote request" link points to `/contact-us/#quote`.
- There is no address and no map, on purpose. When an address is confirmed, it can be added here as a paragraph connected to Business Info → Business address.
- The page's title and description in Google are in the **Search engines** panel, as for Home (section 5).

### The quote form

**Short form or full form.** Click the form; at the top of the sidebar, **Form parts → Show parts 2 and 3** switches between:
- **Off (now):** only part 1, "About you": name, phone, email, best way to reply (if asked, see below), and the optional box **Tell us about your move** (up to 1,000 characters). No step numbers are shown. The move and service questions are not asked, not required, and don't appear in the emails, in Enquiries or in the WhatsApp message.
- **On:** the full form with the three numbered parts (About you, Your move, What you need). The "Tell us about your move" box stays in part 1.

**Ask "Best way to reply"** (in the same Form parts panel) shows or hides the WhatsApp / Phone call / Email choice. It's **off** on Contact Us now: you decide how to reply, and the emails and Enquiries don't show the line.

Click **Save** after switching. The words of parts 2 and 3 stay in their panels while they're switched off.

Click the form and look in the right-hand sidebar (Block tab). The panels **Part 1: About you**, **Part 2: Your move**, **Part 3: What you need** and **Button and messages** hold every word on the form: the three part headings, each label and help line, the button ("Send quote request"), the line under the button, and the message shown after sending ("Quote request sent" and the "Send this on WhatsApp too" button). Type, then click **Save**. If you empty a field, the original words come back (except the line under the button, which can be left empty to hide it).

The choices in the lists (types of move, property sizes, floors, services, trucks) and the error messages are fixed, so every request reaches you in the same format. Ask the developer if a choice should change.

The preview in the editor is the real form, so you can't type in it there. Try it on the live page instead (and mark your test enquiry as Done afterwards).

## 9. Quote requests (Enquiries)

Every quote request is **saved** in WordPress and **emailed** to you.

### Where they go

- **Email**: to the address in Settings → Business Info → Email address. The subject reads like "New quote request AYM-261003-001: Flat, Juffair to Riffa". At the top there are two buttons: **Call** (rings the customer) and **WhatsApp the customer** (opens a chat with them). Below is every answer they gave. If they gave an email address, just press **Reply** to write back to them.
- **The customer** gets a short confirmation with the same reference number and your WhatsApp and phone links, if they gave an email address.
- **After sending**, the customer sees "Quote request sent", their reference number and a **Send this on WhatsApp too** button. It opens WhatsApp to your number with their reference, name, move and date already typed, so they can send photos of their things straight away.
- Until Gmail is connected at go-live (see `docs/email-setup.md`), emails are only recorded on the local site (WP Mail Logging) and not delivered. The saved enquiries are always there.

### Reading them in WordPress

Click **Enquiries** in the dashboard menu. The number beside it is how many are **New**.

- The list shows when each request came in, its reference number (AYM, the date as year-month-day, then the number of the day), the name, phone (tap to call), type of move, from → to, the preferred date and the status.
- Click a reference (or **Open**) to see the whole request, with links to call, WhatsApp or email the customer.
- **Search** (top right) finds a reference number. **All statuses** (above the list) shows only New, Contacted or Done requests.

### Status: New, Contacted, Done

Every request starts as **New**. Keep them up to date so you can see at a glance who still needs an answer:

- **One request**: open it, pick **Contacted** or **Done** in the **Status** box on the right, then click **Update**.
- **Several at once**: tick them in the list, choose **Mark as Contacted** (or New / Done) in **Bulk actions**, then click **Apply**.

Requests are private: visitors and search engines can never see them. Only people who can edit posts on the site (editors and administrators) can open them. Don't delete real requests unless you need to; **Move to Trash** keeps them for 30 days in case of a mistake.

### Spam protection (nothing to do)

The form blocks most spam on its own: an invisible field that only robots fill in, a check that the form wasn't sent within 3 seconds of opening, a limit of 5 requests per hour from one connection, and a security code. A real person who hits one of these sees a plain message telling them what to do, including a WhatsApp link.
