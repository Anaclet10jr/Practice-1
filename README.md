# Rwanda Realty — Phase 1

Laravel property marketplace for Rwanda.  
**Phase 1** covers: Authentication (3 roles), Property CRUD, Admin moderation, Agent approval flow.

---

## Quick Start

### 1. Install dependencies
```bash
composer install
npm install && npm run build   # or: npm run dev
```

### 2. Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME="Rwanda Realty"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rwanda_realty
DB_USERNAME=root
DB_PASSWORD=

# Storage (run: php artisan storage:link)
FILESYSTEM_DISK=public
```

### 3. Database
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 4. Run
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Seeded Accounts

| Role   | Email                     | Password  |
|--------|---------------------------|-----------|
| Admin  | admin@rwandarealty.rw     | password  |
| Agent  | agent1@example.com        | password  |
| Client | client@example.com        | password  |

---

## File Structure (Phase 1)

```
app/
  Models/
    User.php              # Role constants + helpers + relationships
    Property.php          # Scopes, casts, cover_image + formatted_price accessors
    Inquiry.php           # Contact inquiries from property pages

  Http/
    Controllers/
      AuthController.php      # Register, login, logout → role-based redirect
      PropertyController.php  # Public + agent CRUD (10 methods)
      AdminController.php     # Dashboard, agent approval, property moderation

    Middleware/
      CheckRole.php           # role:admin | role:agent | role:admin,agent
      EnsureAgentApproved.php # Redirect unapproved agents to /agent/pending

database/
  migrations/
    *_create_users_table.php
    *_create_properties_table.php
    *_create_inquiries_and_favorites.php

  seeders/
    DatabaseSeeder.php        # Admin + 2 agents + client + 4 approved properties

routes/
  web.php                     # Public, auth, agent (role+approval), admin groups

resources/views/
  layouts/app.blade.php       # Navbar, flash messages, footer
  home.blade.php              # Hero + featured grid + agent CTA
  auth/
    login.blade.php
    register.blade.php        # Role selector (client / agent) with JS toggle
  properties/
    index.blade.php           # Filter bar + paginated grid
    show.blade.php            # Gallery + specs + contact form + related
  agent/
    dashboard.blade.php       # Stats + recent properties table
    pending.blade.php         # "Awaiting approval" screen
    properties/
      create.blade.php        # Full form with image upload + preview
      edit.blade.php          # Same form pre-populated
      index.blade.php         # Table with status badges
  admin/
    dashboard.blade.php       # Stats + pending queues + quick links
    properties/
      index.blade.php         # Filterable table + quick approve
      show.blade.php          # Full review with approve/reject/feature
    agents/
      index.blade.php         # Approve / revoke / remove agents
```

---

## Phase 2 Preview

Next build will add:
- 🗺 Google Maps integration on property listings
- 💬 Real-time inquiry notifications (Laravel Echo + Pusher)
- 📸 Cloudinary image optimization
- 🔍 Full-text search with Laravel Scout + Meilisearch
- ❤️  Favorites (save properties) for clients

Run `php artisan serve` and click **"Build Phase 2"** in the architecture diagram to continue.
