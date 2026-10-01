# Editing guide: AYESHA Movers & Packers website

This guide is for the person who keeps the website up to date. You don't need any coding knowledge.

## 1. Your phone numbers, WhatsApp, email and hours: change them in one place

All contact details live on one page: **Settings → Business Info** (in the black menu on the left of the dashboard).

| Field | What it changes |
|---|---|
| Main phone number | The Call button in the header, the big number in the footer, and the **Call** button in the bar at the bottom of phone screens |
| Second mobile number, Office number | The numbers listed in the footer (and the Contact page later) |
| WhatsApp number | Every WhatsApp button and link: footer, phone bar, and the round green button on computers. **Digits only**, with the country code, e.g. `97334448236` |
| WhatsApp opening message | The message that is already typed for the visitor when WhatsApp opens. They can change it before sending |
| Email address | The email shown on the site, **and** the sender address of emails the website sends you |
| Opening hours | The hours line in the footer |
| Service areas | One place per line |
| Instagram page | The Instagram link in the footer |
| Business address | **Leave empty** until you want an address on the site. When you fill it in, search engines are told about it too |
| Show reviews section | Leave **off** until the example reviews have been replaced with real customer reviews. While it is off, the reviews section is hidden from visitors |

Click **Save business info**. The change appears everywhere on the site straight away; you don't need to edit any page.

Tips:
- Write phone numbers the way people should read them, e.g. `+973 3444 8236`. The site builds the "tap to call" link for you.
- If a value is wrong (e.g. a WhatsApp number with too few digits), you'll see a red message and the old value is kept.

## 2. Header, footer and menu

Go to **Appearance → Editor**.

- **Patterns → Header** or **Footer**: click the part, then click any text to change it. The Save button is at the top right.
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

- **Group** block → Styles: *Teal panel*, *Yellow panel*, *Grey panel* (each sets its own text colour).
- **Button** block → Styles: *Fill* (teal), *WhatsApp green*, *Yellow*, *Outline*.
- **Paragraph** → Styles: *Big phone number* (the large condensed number style).
- **Spacer** → Styles: *Chevron strip* (the yellow and teal tape). Use it only under the Home hero; the footer already has one.

Free colour pickers are switched off on purpose: white text on WhatsApp green, or yellow text on white, can't be read by many people.

Ready-made sections are under **Patterns → Ayesha Movers** in the block inserter.
