# Editing guide: AYESHA Movers & Packers website

This guide is for the person who keeps the website up to date. You don't need any coding knowledge.

## 1. Your phone numbers, WhatsApp, email and hours: change them in one place

All contact details live on one page: **Settings → Business Info** (in the black menu on the left of the dashboard).

| Field | What it changes |
|---|---|
| Main phone number | The big number at the top of the Home page and in the gold band above the footer, every **Call** button, the gold Call button in the header, the number in the navy bar at the very top (computers), the big number in the footer, and the **Call** button in the bar at the bottom of phone screens. The big number resizes itself to fit, even if the new number is longer |
| Second mobile number, Office number | The numbers listed in the footer and on the Contact Us page |
| WhatsApp number | Every WhatsApp button and link: footer, phone bar, the round green button on computers, the WhatsApp row on Contact Us, and the "Send this on WhatsApp too" button after a quote request. **Digits only**, with the country code, e.g. `97334448236` |
| WhatsApp opening message | The message that is already typed for the visitor when WhatsApp opens. They can change it before sending |
| Email address | The email shown on the site, **where quote requests are emailed to**, and the sender address of every email the website sends (see section 9) |
| Opening hours | The hours line in the top bar, the footer, the gold band and the Contact Us page |
| Service areas | One place per line. Kept for later use: the "Where we go" list on the pages is edited in the editor instead (see section 5) |
| Instagram page | The Instagram link in the top bar, the footer, on Contact Us and under the ads gallery |
| Facebook page | The Facebook link in the top bar, the footer and on Contact Us (read out as "AYESHA Movers on Facebook"). **Leave it empty to hide every Facebook link** on the site |
| Business address | **Leave empty** until you want an address on the site. When you fill it in, search engines are told about it too |
| Show reviews section | Leave **off** until the example reviews have been replaced with real customer reviews. While it is off, the reviews section is hidden from visitors |
| Show contact details on Contact Us | **On**: the opening hours and the rows with the numbers, email, Instagram and Facebook show beside the quote form. Switch it **off** to show only the heading and the form. Nothing is deleted while it is off |

Click **Save business info**. The change appears everywhere on the site straight away; you don't need to edit any page.

Tips:
- Write phone numbers the way people should read them, e.g. `+973 3444 8236`. The site builds the "tap to call" link for you.
- If a value is wrong (e.g. a WhatsApp number with too few digits), you'll see a red message and the old value is kept.

## 2. Header, footer and menu

Go to **Appearance → Editor**.

- **Patterns → Header** or **Footer**: click the part, then click any text to change it. The Save button is at the top right.
- **Top info bar** (the thin navy bar above the header, on computers only): the main number, email, opening hours and Instagram. All four come from Business Info, so change them there; you can delete a line you don't want in the bar (select it in List View → Delete).
- **Navigation**: the menu used in the header and the footer. Add, remove, rename or reorder pages here; both menus update together.
- **Mobile Call and WhatsApp bar**: the bar at the bottom of phone screens. You can change the button words ("Call", "WhatsApp"). The links come from Business Info.
- **Footer "Call or WhatsApp" row**: when your WhatsApp number is the same as your main number, the footer shows one row, "Call or WhatsApp". If you set a different WhatsApp number in Business Info, the footer shows "Main number" and "WhatsApp" as two rows instead. In the editor you see all three rows (their names say when each one shows); the website picks the right one by itself.
- **Call and WhatsApp in the phone menu**: the Main menu (Navigation) ends with two buttons, Call and WhatsApp. They appear only when the menu is opened on a phone, at the bottom of the screen. You can change their words; the links come from Business Info.

Made a mistake? In the Editor, open the part, click the three dots (⋮) at the top right and choose **Reset** to go back to the original design.

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
- **Spacer** → Styles: *Gold line* (a thin gold rule). It's used under the Home hero; the footer already has one.

**Capital letters:** section headings (and the menu) show in capitals on the site, but type them normally, e.g. "Where we go". The capitals are added by the design, so the words stay easy to edit and read in the editor.

**Icons** next to the services (house, box, sofa, TV, truck, container) and next to "Why people book us" are added by the design: you don't add or pick them. Each service's icon follows its link (e.g. a service that links to `#trucks` gets the truck).

Free colour pickers are switched off on purpose: white text on WhatsApp green, or gold text on white, can't be read by many people.

Ready-made sections are under **Patterns → Ayesha Movers** in the block inserter.

## 5. The Home page

Open **Pages → Home**. Every section is made of normal blocks: click any text, button or photo to change it, then click **Save**. Open **List View** (the icon with three lines at the top left) to see the sections by name: Hero, What we do, How a move works, Where we go, Reviews, Why people book us + Questions, CTA band.

### Sections shared with other pages (synced patterns)

Two sections are **synced patterns**: the gold **CTA band** ("Moving soon? Message us now.") and **Where we go**. They have a purple outline in the editor. Change one once and it changes on every page that uses it.

- To edit: click the section, then **Edit original** in the toolbar. Make the change and save. Or open **Appearance → Editor → Patterns → Ayesha Movers**.
- Don't choose **Detach** unless you want this page to have its own separate copy. A detached copy no longer follows the shared one.
- To add one to another page: **+** (block inserter) → **Patterns** → **Ayesha Movers** → "Call to action band" or "Where we go".

### The hero (top of the page)

- **The big number** comes from Settings → Business Info → Main phone number. You can't type in it. It is two lines on phones ("+973" / "3444 8236") and one line on wide screens, and it resizes itself to fit.
- **WhatsApp us** and **Call** take their links from Business Info. You can change the button words.
- **The photo is optional.** To remove it, click the photo and delete it (the ⋮ menu → **Delete**). The text then fills the whole width; nothing else needs to change. To add a photo back, click the empty photo column → **+** → **Image** and pick a photo from the Media Library, size *Photo (560px)*. Don't write a caption or alt text that says the truck is yours until that is confirmed.

### What we do: the six service cards

"What we do" shows the six services as equal cards (three across on computers, one under the other on phones). Each card is a group named "Service: …" in List View with three blocks: the title (it links to that service on the Our Services page), one paragraph and the "Ask on WhatsApp" link. The icon in the gold circle is added by the design and follows the title's link (e.g. a title that links to `…/our-services/#trucks` gets the truck), so keep each link pointing at its service.

- **Change a text:** click it and type.
- **Add or remove a service:** select a card in List View → ⋮ → **Duplicate** (or **Delete**). Point the new title's link at the right anchor on Our Services.
- **See all services** under the cards is a normal button.
- The longer "Every move includes" list and the pickup photo are no longer on Home (since Phase 7): the list is on the Our Services page; the photo is still in the Media Library.

### Services: "Ask on WhatsApp" links

Each service has a WhatsApp link that opens the chat with the service already typed, e.g. "Hi AYESHA Movers & Packers, I'd like a price for packing and unpacking." To change that message, use the code editor (⋮ → **Code editor**), find the service's `"message":"…"` and edit the words between the quotes (see section 3). The service titles link to the matching part of the Our Services page.

### Questions (FAQ)

Each question is a **Details** block: the question is the first line and the answer goes inside. To add one, select a question, then ⋮ → **Duplicate**, and change both texts. Only answer with facts you are sure of. There is no pricing question yet, on purpose.

### Reviews

The **What customers say** section holds three placeholder cards. Visitors can't see it while **Show reviews section** is off in Settings → Business Info. When you have real reviews:
1. Replace each card with a real review: the customer's own words, their name and area, the month and year, and a link to the original on Google or Instagram. Delete any card you don't have a real review for.
2. Delete the grey line that starts "Placeholder section."
3. Switch on **Show reviews section** in Business Info.

Never write a review yourself or change a customer's words.

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

Open **Pages → Contact Us**. In **List View**: Contact → Columns → the left column (heading, opening hours, **Contact rows**) and the right column (**Request a free quote**, one line of text, and the **Quote request form**). On computers the left column stays on screen while the form scrolls; on phones it comes first and the form follows.

- **Heading and the line under "Request a free quote"**: click and type.
- **Show or hide the hours and contact rows:** Settings → Business Info → **Show contact details on Contact Us** (on now). While it's off, visitors see only the heading and the form, but the hours and rows stay in the page and you can still edit them here (the editor always shows them).
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
