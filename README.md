# Suarez Cleaning Services LLC — Orange County Janitorial Landing Site

A fast, mobile-friendly landing site for Orange County commercial cleaning and janitorial services. It includes a quote form with client-side validation that submits to a PHP handler which emails the request and sends an SMS/MMS via carrier gateway. Basic on-page SEO and a sitemap are included.

## Features
- Responsive layout: hero, services, why-us, and areas served
- Quote form with inline validation and consent checkbox
- Server handler (`send-form.php`) using PHP `mail()` to inbox + SMS/MMS gateway
- Local Business JSON-LD schema for SEO
- `sitemap.xml` with key sections (home, contact, anchors)
- Dynamic year in footer via JavaScript

## Tech Stack
- Static: `HTML`, `CSS`, `JavaScript` (vanilla)
- Backend endpoint: `PHP` (uses `mail()`)

## Project Structure
- `index.html` — Main landing page with hero, services, schema.org JSON-LD
- `contact.html` — Quote request form (validated and submitted via JS)
- `thankyou.html` — Thank-you page (not currently auto-redirected by JS)
- `styles.css` — Global styles and layout components
- `main.js` — Dynamic footer year, form validation, and submission via `fetch`
- `send-form.php` — PHP handler that emails and texts the submission
- `sitemap.xml` — Sitemap with canonical routes and sections
- `images/` — Logos, service images, and icons
- `favicon.ico` — Root favicon

Note: There is no `robots.txt` in the repo. Add one if you want to control crawler behavior or link the sitemap location.

## Quick Start (Local)
Static pages can be opened directly, but the form requires a PHP server because it posts to `send-form.php`.

Option A — PHP built-in server (quickest):
1. Install PHP 8+.
2. From the project root, run: `php -S localhost:8000`
3. Visit: `http://localhost:8000/index.html` and `http://localhost:8000/contact.html`

Option B — Any local web server with PHP (Apache, Nginx, IIS). Ensure `send-form.php` is reachable at the path used by `main.js` (`/send-form.php`).

## Form Handler Configuration (`send-form.php`)
Open `send-form.php` and update these values:
- `$your_email` — Destination inbox for full submission details
- `$sms_gateway` — Carrier email-to-SMS address for quick text alerts
- `$mms_gateway` — Carrier email-to-MMS address (optional, for longer messages)

What it sends:
- Subject: `New Contact Form Submission from {name}`
- Body: Name, company, phone, email, city, service, size, preferred time, message
- Headers: `From` uses the server host; `Reply-To` uses the submitter email

Delivery notes:
- PHP `mail()` depends on your hosting’s mail setup. For production reliability, configure real SMTP and SPF/DKIM on your domain or switch to a transactional service (e.g., SMTP relay) if delivery is inconsistent.
- Test both inbox and SMS/MMS delivery paths; some carriers throttle or filter long messages.

## Frontend Form Behavior (`main.js`)
- Validates: name, phone, email, city, service, and consent
- Submits as `application/x-www-form-urlencoded` to `send-form.php`
- Shows a sending status and success/error messages; resets the form on success
- Does not redirect to `thankyou.html` by default (status text is shown inline)

To redirect after success, add in `main.js` after success: `window.location.href = 'thankyou.html'`.

## SEO & Metadata
- Local Business JSON-LD in `index.html`: update `name`, `telephone`, `url`, `image`, and `sameAs` as needed
- `sitemap.xml`: update `<loc>` URLs and `<lastmod>` when deploying to a new domain
- Icons/manifest: `index.html` references root icons and `/site.webmanifest`. Ensure these files exist at the referenced paths or update the links to the actual locations (some icons live under `images/`).
- Consider adding `robots.txt` at the web root with a reference to the sitemap, e.g.: `Sitemap: https://your-domain.com/sitemap.xml`

## Editing Content
- Business info and JSON-LD: `index.html`
- Hero copy, services, areas: `index.html`
- Quote form fields and labels: `contact.html`
- Validation messages and submit behavior: `main.js`
- Styles and layout: `styles.css`

## Deployment
Deploy to any PHP-capable host:
- Upload all files to the web root (or adjust paths accordingly)
- Verify `send-form.php` is reachable at `/send-form.php`
- Ensure outgoing mail is configured (PHP `mail()` or SMTP)
- Update `sitemap.xml` domain and test crawlability
- Serve over HTTPS; verify canonical links and schema data

## Testing the Form
1. Start your PHP server.
2. Open `contact.html`, complete all required fields, and submit.
3. Verify:
   - Browser Network tab shows a `200 OK` from `send-form.php`
   - Email received at `$your_email`
   - SMS/MMS received at the configured carrier addresses
4. If delivery fails, check PHP/server logs and mail configuration.

## Notes
- Encoding: Save files as UTF-8 to avoid garbled symbols in text content.
- Images: Optimize/compress large images for faster page loads.
- Accessibility: Labels, inline errors, and focus handling are present; preserve ARIA and label ties when editing.

## License
No license specified. All rights reserved by the site owner.

