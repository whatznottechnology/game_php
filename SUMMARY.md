# 🎮 GameHub - Complete Betting Platform Solution

## 📦 What You Got

A **complete betting affiliate/lead generation platform** with:
- ✅ **Frontend**: Responsive HTML pages with Tailwind CSS
- ✅ **Backend**: PHP admin panel with SQLite database
- ✅ **API**: RESTful JSON endpoints
- ✅ **Full Integration**: Dynamic content loading from database

---

## 📁 Project Structure

```
gg/
├── 📄 index.html              # Home page (landing page with featured games)
├── 📄 games.html              # All games listing with search & filter
├── 📄 game.html               # Single game details page
├── 📄 setup.php               # Server requirements checker
├── 📄 README.md               # Full documentation
├── 📄 TESTING.md              # Testing instructions
│
├── 📁 admin/                  # Backend Admin Panel
│   ├── 📄 login.php           # Admin login (admin/admin123)
│   ├── 📄 index.php           # Dashboard with analytics
│   ├── 📄 settings.php        # Site settings management
│   ├── 📄 categories.php      # Categories CRUD
│   ├── 📄 games.php           # Games CRUD with TinyMCE
│   ├── 📄 inquiries.php       # Lead management
│   ├── 📄 profile.php         # Admin account settings
│   ├── 📄 logout.php          # Logout handler
│   │
│   ├── 📁 config/
│   │   ├── 📄 database.php    # Database singleton class
│   │   ├── 📄 auth.php        # Authentication helpers
│   │   └── 📄 database.sqlite # SQLite database (auto-created)
│   │
│   ├── 📁 includes/
│   │   ├── 📄 header.php      # Admin header component
│   │   └── 📄 sidebar.php     # Admin sidebar menu
│   │
│   └── 📁 api/                # RESTful API Endpoints
│       ├── 📄 categories.php      # GET all categories
│       ├── 📄 games.php           # GET games (with filters)
│       ├── 📄 settings.php        # GET site settings
│       ├── 📄 submit-inquiry.php  # POST inquiry submission
│       └── 📄 track-visitor.php   # POST visitor analytics
│
└── 📁 assets/
    ├── 📁 js/
    │   ├── 📄 api.js          # API integration & dynamic loading
    │   └── 📄 script.js       # General JavaScript
    │
    └── 📁 img/
        ├── 📁 categories/     # Category icon uploads
        │   └── 📄 .htaccess   # Security (image-only)
        └── 📁 games/          # Game banner uploads
            └── 📄 .htaccess   # Security (image-only)
```

---

## 🗄️ Database Schema

**6 Tables:**

1. **admin_users** - Admin accounts
2. **site_settings** - Site configuration (12 settings)
3. **categories** - Game categories with icons
4. **games** - Betting games with banners, descriptions
5. **inquiries** - Customer leads with status tracking
6. **visitor_logs** - Analytics tracking

---

## 🚀 Quick Start

### 1. Setup (One-time)

```bash
# Navigate to project
cd c:\Users\pmihu\OneDrive\Desktop\gg

# Start PHP server (if PHP installed)
php -S localhost:8000

# OR use XAMPP/WAMP
# Copy gg folder to htdocs/www folder
```

### 2. Access Pages

- **Setup Check**: http://localhost:8000/setup.php
- **Admin Panel**: http://localhost:8000/admin/
- **Home Page**: http://localhost:8000/index.html
- **Games Page**: http://localhost:8000/games.html

### 3. Login to Admin

- **Username**: `admin`
- **Password**: `admin123`
- ⚠️ **CHANGE PASSWORD IMMEDIATELY!**

### 4. Add Content

1. **Categories**:
   - Admin → Categories
   - Add: "Sports Betting", "Live Casino", "Card Games", etc.
   - Upload 1:1 ratio icons (optional)

2. **Games**:
   - Admin → Games
   - Add games with banners, descriptions
   - Select category, add platforms (Reddy999, Tenexch, etc.)
   - Set bonus amount, min deposit
   - Mark featured games

3. **Site Settings**:
   - Admin → Settings
   - Update contact number, WhatsApp
   - Add social media links

---

## 🎯 How It Works

### Content Flow

```
Admin Panel → Database → API → Frontend
```

1. **Admin adds game**:
   - Fill form in admin/games.php
   - Saves to SQLite database

2. **API exposes data**:
   - GET admin/api/games.php returns JSON

3. **Frontend loads data**:
   - assets/js/api.js fetches from API
   - Dynamically renders games on page

4. **Customer submits inquiry**:
   - Form POST to admin/api/submit-inquiry.php
   - Saved to database

5. **Admin views inquiry**:
   - admin/inquiries.php shows all leads
   - Update status, add notes

---

## 💡 Key Features

### Frontend (Public)
✅ Responsive landing page with hero slider
✅ Dynamic categories from database
✅ Featured games showcase
✅ All games listing with search & filter
✅ Lead generation forms (inquiry modal)
✅ WhatsApp integration
✅ Visitor tracking

### Backend (Admin)
✅ Secure login system
✅ Dashboard with analytics (visitors, inquiries, charts)
✅ Categories management (CRUD + icon upload)
✅ Games management (CRUD + banner upload + rich text editor)
✅ Inquiries management (view, filter, update status, notes)
✅ Site settings (12 configurable options)
✅ Profile management (change password)

### APIs
✅ GET `/admin/api/categories.php` - All active categories
✅ GET `/admin/api/games.php` - All games (supports ?category=id&featured=true)
✅ GET `/admin/api/settings.php` - Site settings
✅ POST `/admin/api/submit-inquiry.php` - Submit inquiry
✅ POST `/admin/api/track-visitor.php` - Track page visit

---

## 📊 Admin Panel Sections

1. **Dashboard**
   - 4 stat cards (visitors, inquiries, pending, games)
   - Platform distribution chart
   - Interest distribution chart
   - Recent inquiries table

2. **Site Settings**
   - Basic info (site name, emails, logos)
   - Contact (phone, WhatsApp)
   - Social media (Facebook, Twitter, Instagram, YouTube)

3. **Categories**
   - Add/edit/delete categories
   - Upload 1:1 icons
   - Set display order
   - Toggle active/inactive

4. **Games**
   - Add/edit/delete games
   - Upload banner images
   - Rich text HTML editor for descriptions
   - Category dropdown
   - Platform names (comma-separated)
   - Bonus amount & min deposit
   - Featured toggle
   - Display order

5. **Inquiries**
   - List all inquiries with filters
   - Search by name/mobile/email/platform
   - Filter by status (new/contacted/closed)
   - View full details in modal
   - Update status & add notes
   - Delete inquiries

6. **My Account**
   - Update username & email
   - Change password
   - View account info

---

## 🔗 Integration Points

### Categories
- **Backend**: admin/categories.php
- **API**: admin/api/categories.php
- **Frontend**: 
  - index.html (sidebar buttons)
  - games.html (filter chips)

### Games
- **Backend**: admin/games.php
- **API**: admin/api/games.php
- **Frontend**:
  - index.html (featured games grid)
  - games.html (all games with search)

### Inquiries
- **Backend**: admin/inquiries.php
- **API**: admin/api/submit-inquiry.php
- **Frontend**:
  - index.html (inquiry modal)
  - games.html (inquiry modal)

### Settings
- **Backend**: admin/settings.php
- **API**: admin/api/settings.php
- **Frontend**: Contact info, social links auto-populated

---

## 🎨 Platforms Included

- **Reddy999** (Reddy999.com)
- **Reddy444** (Reddy444.com)
- **Tenexch** (Tenexch.com)
- **KingExch9** (KingExch9.com)
- **BetBhai9** (BetBhai9.red)
- **AKQ777** (AKQ777.com)

*All configurable through admin panel!*

---

## 🔐 Security Features

✅ Password hashing (bcrypt)
✅ PDO prepared statements (SQL injection prevention)
✅ Session-based authentication
✅ File upload validation (images only)
✅ .htaccess protection (no PHP in upload dirs)
✅ XSS protection (htmlspecialchars on output)

---

## 📱 Contact Integration

- **WhatsApp**: Auto-opens with pre-filled message after inquiry
- **Phone**: Click-to-call links
- **Email**: mailto: links
- **Social Media**: Configurable from admin panel

---

## 🎯 Typical Workflow

### Day 1: Setup
1. Run setup.php to check requirements
2. Login to admin panel
3. Change default password
4. Add 3-5 categories
5. Add 10-15 games (mark 5-6 as featured)
6. Configure site settings

### Day 2+: Operations
1. Check dashboard for visitor stats
2. Review new inquiries
3. Contact customers via WhatsApp
4. Update inquiry status to "Contacted"
5. Add games as needed
6. Monitor analytics

---

## 📈 Analytics Tracked

✅ Total visitors (all-time + today)
✅ Total inquiries (all-time + today)
✅ Pending inquiries count
✅ Platform distribution (top 5 platforms)
✅ Interest distribution (top 5 interests)
✅ Recent inquiries (last 10)
✅ IP address & user agent tracking

---

## 🛠️ Customization

### Add New Platform
1. Admin → Games → Add Game
2. Add platform name in "Platforms" field
3. Or edit inquiry form dropdown in HTML

### Change Colors
- Edit Tailwind classes in HTML files
- Main colors: blue-600, purple-600

### Add New Settings
- Edit admin/config/database.php
- Add INSERT in createDefaultSettings()
- Add field in admin/settings.php

---

## 📝 Important Files

- **Database**: `admin/config/database.sqlite`
- **Login**: `admin/login.php` (admin/admin123)
- **API Integration**: `assets/js/api.js`
- **Documentation**: `README.md`
- **Testing Guide**: `TESTING.md`

---

## ⚠️ Before Going Live

1. ✅ Change admin password
2. ✅ Update all site settings (contact, social media)
3. ✅ Add real game content
4. ✅ Test inquiry form submission
5. ✅ Configure WhatsApp number
6. ✅ Backup database regularly
7. ✅ Use HTTPS in production
8. ✅ Move database outside web root
9. ✅ Set proper file permissions

---

## 🎉 You're All Set!

**Your complete betting platform is ready to use!**

- ✅ Backend fully functional
- ✅ Frontend integrated with APIs
- ✅ Dynamic content loading
- ✅ Lead generation system
- ✅ Analytics dashboard
- ✅ Mobile responsive

**Start adding content and generate leads! 🚀**

---

## 🆘 Support

For issues:
1. Check `TESTING.md` for testing steps
2. Check `README.md` for detailed docs
3. Check browser console for JavaScript errors
4. Check PHP error logs for backend errors

---

**Built with ❤️ for betting affiliate businesses**

*Last Updated: Today*
