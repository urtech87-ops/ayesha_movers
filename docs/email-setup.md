# Email setup: sending the website's emails through Gmail

The quote form sends two emails: the request to the business (to the address in **Settings → Business Info → Email address**) and, when the customer gives an email address, a short confirmation to the customer. Both are sent **from** the Business Info address, `ayeshamoversbh786@gmail.com`.

**On the local site** no email leaves the computer. WP Mail Logging records every email instead (**WP Mail Logging** in the dashboard menu), and each one shows the error "Could not instantiate mail function". That is expected.

**On the live site** the emails must go out through Gmail itself. A web server sending as a gmail.com address is rejected or sent to spam (Gmail's DMARC rule), so WordPress has to log in to Gmail and send through it. The free plugin **WP Mail SMTP** (already installed, inactive) does this with a Gmail **App Password**.

Do this once, at go-live, on the live site.

## 1. Create the App Password (in the Google account)

You need to be signed in to the Google account `ayeshamoversbh786@gmail.com`.

1. Go to <https://myaccount.google.com/security>.
2. Turn on **2-Step Verification** if it isn't on yet (Google only offers App Passwords when it is on). Follow Google's steps; it usually asks for a phone number.
3. Go to <https://myaccount.google.com/apppasswords> (or search "App passwords" in the account's search box).
4. Type a name, e.g. `AYESHA website`, and click **Create**.
5. Google shows a 16-letter password in four groups (like `abcd efgh ijkl mnop`). **Copy it now**: Google shows it only once.
   - Keep it like any password: don't email it, don't put it in a chat, don't save it in a document in the website files or on GitHub.
   - If it is ever lost or leaked, delete it on the same page and make a new one. Nothing else in the Google account changes.

## 2. Connect WP Mail SMTP (in WordPress)

There are two ways. **Way A is safer** (the password is kept in a server file, not in the database); use way B if you can't edit server files.

### Way A (recommended): password in wp-config.php

1. In the hosting file manager (or SFTP), open `wp-config.php` in the website's main folder.
2. Above the line `/* That's all, stop editing! Happy publishing. */`, add these lines. Replace the password with the App Password **without spaces**:

   ```php
   define( 'WPMS_ON', true );
   define( 'WPMS_MAILER', 'smtp' );
   define( 'WPMS_SMTP_HOST', 'smtp.gmail.com' );
   define( 'WPMS_SMTP_PORT', 587 );
   define( 'WPMS_SSL', 'tls' );
   define( 'WPMS_SMTP_AUTH', true );
   define( 'WPMS_SMTP_AUTOTLS', true );
   define( 'WPMS_SMTP_USER', 'ayeshamoversbh786@gmail.com' );
   define( 'WPMS_SMTP_PASS', 'paste-the-16-letters-here' );
   define( 'WPMS_MAIL_FROM', 'ayeshamoversbh786@gmail.com' );
   define( 'WPMS_MAIL_FROM_FORCE', true );
   define( 'WPMS_MAIL_FROM_NAME', 'AYESHA Movers & Packers' );
   define( 'WPMS_MAIL_FROM_NAME_FORCE', true );
   ```

3. Save the file.
4. In WordPress, go to **Plugins** and **Activate** WP Mail SMTP. If its setup wizard opens, close it (**Go back to the Dashboard**): the settings come from the file.
5. Open **WP Mail SMTP → Settings**. The fields are greyed out and say they are set in wp-config.php. Check they show *Other SMTP*, `smtp.gmail.com`, port 587, TLS.

`wp-config.php` is never put on GitHub (the project's `.gitignore` keeps it out). Never paste the password into any other file.

### Way B: password in the settings screen

1. **Plugins → Activate** WP Mail SMTP. Skip the setup wizard.
2. **WP Mail SMTP → Settings**:
   - **From Email**: `ayeshamoversbh786@gmail.com`, tick **Force From Email**.
   - **From Name**: `AYESHA Movers & Packers`, tick **Force From Name**.
   - **Mailer**: choose **Other SMTP**. (Not "Google / Gmail": that one needs a Google Cloud project instead of an App Password.)
   - **SMTP Host**: `smtp.gmail.com`
   - **Encryption**: **TLS**
   - **SMTP Port**: `587`
   - **Auto TLS**: on
   - **Authentication**: on
   - **SMTP Username**: `ayeshamoversbh786@gmail.com`
   - **SMTP Password**: the App Password, without spaces
3. Click **Save Settings**.

## 3. Send a test email

1. **WP Mail SMTP → Tools → Email Test**.
2. **Send To**: an address you can check that is **not** the Gmail account itself (e.g. your own phone's email), so you can see it arrive from the outside. Leave **HTML** on.
3. Click **Send Email**. You should see "Success!". Check the inbox (and the spam folder the first time).
4. If it fails, the screen explains why. The usual causes:
   - *Username and Password not accepted*: the App Password was mistyped (no spaces), was deleted, or 2-Step Verification was turned off (which deletes all App Passwords). Make a new one.
   - *Could not connect to SMTP host*: the hosting company blocks port 587. Ask them to open it, or try port 465 with encryption **SSL**.

Then test the real form:

1. Open the live **Contact Us** page, fill in the quote form with your own details (and your own email address), and send it.
2. Check that the request arrives at `ayeshamoversbh786@gmail.com`, with the subject "New quote request AYM-…", and that the confirmation arrives in your own inbox.
3. In the request email, tap **Call …** and **WhatsApp the customer** on a phone: both should open with your number. Reply to the email: the reply should go to the email address you typed in the form.
4. In WordPress, open **Enquiries**: the request is there with the same reference. Set its status to **Done** (or move it to the Trash) so it doesn't look like a real customer.

## 4. Other go-live checks for the form

- **WP Mail Logging** is for the local site only. On the live site, deactivate and delete it (**Plugins**), unless you decide to keep a log; then remember it stores a copy of every customer's details.
- **Page caching**: if the host or a caching plugin caches pages, exclude the Contact Us page (`/contact-us/`) from the cache. The form contains a security code that expires after a day; a cached copy older than that makes every request fail with "The form was open for a long time and expired".
- **Behind Cloudflare or another proxy**: the "5 requests per hour" limit counts by visitor IP address. Behind a proxy every visitor can appear with the proxy's address, so the limit would be shared by everyone. If the site is put behind a proxy, ask for the limit to be switched to the visitor's real IP header first.
- Gmail allows roughly 500 emails a day from a normal account: far more than the form will need.
