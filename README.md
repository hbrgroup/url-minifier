# url-minifier

A Laravel 12 URL shortener with click analytics and QR code generation. It includes a Filament admin panel for managing links, channels, and stats, plus a public redirect endpoint for short links.

## Features
- Short link redirects with slug-based routing.
- Click tracking (IP, user agent, browser, platform, device, country, referrer).
- QR code generation for each short link.
- Filament admin UI for managing links and related data.
- Tailwind + Vite frontend assets.

## Tech Stack
- Laravel 12
- Filament 5
- Vite + Tailwind CSS
- endroid/qr-code
- jenssegers/agent

## Project Structure
- `app/Http/Controllers/LinkController.php`: redirect handling and click logging.
- `app/Services/QrCodeService.php`: QR code generation.
- `app/Services/IPInfoService.php`: IP-based country lookup.
- `app/Models/Link.php`: link model with click relationship.
- `routes/web.php`: public routes for home, redirect, and QR codes.

## Setup
The repository includes a Composer `setup` script that installs PHP and Node dependencies, creates the `.env`, generates the key, runs migrations, and builds frontend assets.

```powershell
composer run setup
```

## Development
A `dev` script runs the Laravel server, queue listener, and Vite dev server together.

```powershell
composer run dev
```

## Usage
- Visit `/` to open the app home (currently redirects to `/_`).
- Short links resolve at `/{slug}`.
- QR codes are available at `/{slug}/qrcode`.

## Testing
```powershell
composer run test
```

## Notes
- Configure IP lookup via `config/ipinfo.php` as needed.
- Filament assets are published via the Composer post-update hook.
