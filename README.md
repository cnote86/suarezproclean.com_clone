# Suarez Cleaning Services LLC — Janitorial Landing Site

A fast, mobile‑friendly landing site for Orange County commercial cleaning and janitorial services. It includes a quote form that sends lead details to WhatsApp via the WhatsApp Cloud API, plus basic on‑page SEO, a sitemap, and robots.txt.

## Features
- Clean, responsive layout (hero, services, social proof, areas served)
- Quote form with client‑side validation
- WhatsApp Cloud API handoff (server‑side PHP endpoint)
- Local Business JSON‑LD schema for SEO
- `robots.txt` and `sitemap.xml`

## Tech Stack
- Static: `HTML`, `CSS`, `JavaScript` (vanilla)
- Backend endpoint: `PHP` (for WhatsApp API)

## Project Structure
- `index.html` — Main landing page
- `contact.html` — Quote request form page
- `styles.css` — Global styles and components
- `main.js` — Footer year, form validation, and submission
- `robots.txt` — Crawler directives
- `sitemap.xml` — Sitemap with key sections
- `images/` — Logos and hero/service images
- `images/send-whatsapp.php` — WhatsApp Cloud API server endpoint

Note: `main.js` posts to `send-whatsapp.php` at the web root. If you keep the PHP file under `images/`, update the fetch URL in `main.js` accordingly (or move the PHP file to the web root).

## Quick Start (Local)
You can open the static pages directly in a browser, but the quote form submission requires a PHP server because it posts to a `.php` endpoint.

Option A — PHP built‑in server (recommended for quick testing):
1) Install PHP 8+.
2) From the project root, run: `php -S localhost:8000`
3) Visit: `http://localhost:8000/index.html` and `http://localhost:8000/contact.html`

Option B — Any local web server that supports PHP (Apache, Nginx, IIS). Ensure the `.php` endpoint is reachable at the path used by `main.js`.

## WhatsApp Cloud API Setup
Server endpoint: `images/send-whatsapp.php`

1) Open `images/send-whatsapp.php` and set your credentials:
   - `$token` — Your permanent access token (Meta for Developers)
   - `$phone_number_id` — WhatsApp Business Phone Number ID (not the phone number)
   - `$owner_phone` — Destination WhatsApp number (E.164 without `+`, e.g., `19495551234`)

2) Important notes:
   - You usually cannot message the same number tied to `$phone_number_id`. Use a different receiving line you control.
   - Keep the token secret; do not commit real credentials. In production, load these from environment or a secure config file.
   - The script accepts JSON (`fetch` with `Content-Type: application/json`) or standard form POST.

3) Match path with frontend:
   - `main.js` posts to `send-whatsapp.php`. If the PHP file stays in `images/`, change the fetch URL to `"images/send-whatsapp.php"`, or move the PHP file to the web root and keep the current fetch path.

## Editing Content
- Business info and JSON‑LD: `index.html` (name, phone, URLs, social links). Update domain if different from `suarezproclean.com`.
- Hero copy, services, areas: `index.html`
- Quote form fields and labels: `contact.html`
- Validation messages and submit behavior: `main.js`
- Styles and layout: `styles.css`

## SEO
- Structured data: Local Business JSON‑LD in `index.html`. Keep name, phone, and URLs accurate.
- `robots.txt`: Allows all, disallows static assets and `images/` from indexing. Update if needed.
- `sitemap.xml`: Update `<loc>` entries and `<lastmod>` when deploying to a different domain.

## Deployment
Deploy to any PHP‑capable host:
- Place all files at the web root (or adjust paths accordingly).
- Ensure HTTPS is enabled.
- Verify `send-whatsapp.php` is reachable at the path used by `main.js`.
- After going live, update `sitemap.xml` URLs (domain + sections) and ensure `robots.txt` references the correct sitemap URL.

## Testing the Form
1) Start your PHP server.
2) Open `contact.html` and submit a realistic test request.
3) You should receive a WhatsApp message on `$owner_phone`. If not, check:
   - Browser console/network tab for the POST request and response
   - Server/PHP error logs
   - WhatsApp Cloud API permissions, token, and phone number ID

## Notes
- Character encoding: Use UTF‑8 for all files to preserve special characters and emojis.
- Images: Optimize and compress large assets for faster page loads.
- Accessibility: Forms have inline errors; ensure labels/aria‑attributes remain intact when editing.

## License
No license specified. All rights reserved by the site owner.
