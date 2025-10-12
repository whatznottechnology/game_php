# 🔗 GameHub - Quick Access URLs

## 🏠 Main Pages (Frontend - Public)

### Landing & Games
- **Home Page**: http://localhost:8000/index.html
  - Features: Hero slider, all games grid, categories sidebar with filtering, inquiry form
  - Category filtering works on same page (no page reload)
  
- **Game Detail**: http://localhost:8000/game.html
  - Features: Single game information page

### Setup & Guides
- **Setup Checker**: http://localhost:8000/setup.php
  - Check PHP version, SQLite, permissions
  
- **Database Seeder**: http://localhost:8000/admin/seed-database.php
  - Add dummy data (6 categories, 15 games, 5 inquiries)
  - ⚠️ WARNING: Clears existing data!
  
- **Integration Guide**: http://localhost:8000/integration-guide.html
  - Visual guide showing complete integration

---

## 🔐 Admin Panel (Backend)

### Main Admin Pages
- **Login**: http://localhost:8000/admin/login.php
  - Credentials: admin / admin123
  
- **Dashboard**: http://localhost:8000/admin/index.php
  - Analytics, charts, recent inquiries
  
- **Site Settings**: http://localhost:8000/admin/settings.php
  - Contact info, social media, logos
  
- **Categories**: http://localhost:8000/admin/categories.php
  - Add/edit/delete categories with icons
  
- **Games**: http://localhost:8000/admin/games.php
  - Add/edit/delete games with banners
  
- **Inquiries**: http://localhost:8000/admin/inquiries.php
  - View and manage customer leads
  
- **Profile**: http://localhost:8000/admin/profile.php
  - Change password, update account

---

## 📡 API Endpoints (RESTful JSON)

### GET Requests (Public Data)
- **Get Categories**: http://localhost:8000/admin/api/categories.php
  ```json
  {
    "success": true,
    "data": [...],
    "count": 5
  }
  ```

- **Get All Games**: http://localhost:8000/admin/api/games.php
  ```json
  {
    "success": true,
    "data": [...],
    "count": 10
  }
  ```

- **Get Featured Games**: http://localhost:8000/admin/api/games.php?featured=true
  
- **Get Games by Category**: http://localhost:8000/admin/api/games.php?category=1
  
- **Get Site Settings**: http://localhost:8000/admin/api/settings.php
  ```json
  {
    "success": true,
    "data": {
      "site_name": "GameHub",
      "contact_number": "+91 98765 43210",
      ...
    }
  }
  ```

### POST Requests (Form Submissions)
- **Submit Inquiry**: http://localhost:8000/admin/api/submit-inquiry.php
  ```javascript
  fetch('admin/api/submit-inquiry.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      name: "John Doe",
      mobile: "9876543210",
      platform: "Reddy999",
      interest: "Cricket Betting",
      ...
    })
  })
  ```

- **Track Visitor**: http://localhost:8000/admin/api/track-visitor.php
  ```javascript
  fetch('admin/api/track-visitor.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      page_url: window.location.href
    })
  })
  ```

---

## 📁 Important File Paths

### Database
- **SQLite File**: `c:\Users\pmihu\OneDrive\Desktop\gg\admin\config\database.sqlite`

### Upload Directories
- **Category Icons**: `c:\Users\pmihu\OneDrive\Desktop\gg\assets\img\categories\`
- **Game Banners**: `c:\Users\pmihu\OneDrive\Desktop\gg\assets\img\games\`

### Configuration
- **Database Class**: `admin/config/database.php`
- **Auth Helpers**: `admin/config/auth.php`
- **API Integration**: `assets/js/api.js`

### Documentation
- **Full Docs**: `README.md`
- **Testing Guide**: `TESTING.md`
- **Quick Summary**: `SUMMARY.md`
- **This File**: `URLS.md`

---

## 🎯 Quick Testing Flow

### 1. First Time Setup
```
http://localhost:8000/setup.php
↓
Check all requirements are green
↓
Click "Go to Admin Panel"
```

### 2. Admin Login & Setup
```
http://localhost:8000/admin/
↓
Login: admin / admin123
↓
Go to Site Settings → Update contact info
↓
Go to Categories → Add 3-5 categories
↓
Go to Games → Add 10-15 games (mark some as featured)
```

### 3. Test Frontend
```
http://localhost:8000/index.html
↓
Check categories load in sidebar
↓
Check featured games display
↓
Click category → redirects to games.html
```

### 4. Test Games Page
```
http://localhost:8000/games.html
↓
See all games loaded
↓
Test search: type "cricket"
↓
Test filter: click category chip
↓
Click "Play Now" → inquiry modal opens
```

### 5. Submit Inquiry
```
Fill inquiry form on index.html or games.html
↓
Submit form
↓
See success message
↓
Redirects to WhatsApp
```

### 6. Check Admin
```
http://localhost:8000/admin/inquiries.php
↓
See submitted inquiry
↓
Click eye icon to view details
↓
Update status to "Contacted"
↓
Add notes
```

---

## 🔧 Development URLs

If using different server:

### XAMPP
- Base URL: `http://localhost/gg/`
- Admin: `http://localhost/gg/admin/`

### WAMP
- Base URL: `http://localhost/gg/`
- Admin: `http://localhost/gg/admin/`

### Custom Port
If running on different port (e.g., 3000):
- Replace `localhost:8000` with `localhost:3000`

---

## 📊 Analytics & Monitoring

### Check Dashboard Stats
```
http://localhost:8000/admin/
```
Shows:
- Total Visitors (all-time + today)
- Total Inquiries (all-time + today)  
- Pending Inquiries
- Active Games Count
- Platform Distribution Chart
- Interest Distribution Chart
- Recent Inquiries Table

### Check Inquiry Details
```
http://localhost:8000/admin/inquiries.php
```
Features:
- Filter by status (new/contacted/closed)
- Search by name/mobile/email/platform
- View full details in modal
- Update status & add notes

---

## 🎨 Customize Content

### Add Category
```
http://localhost:8000/admin/categories.php
→ Add New Category
→ Name: "Sports Betting"
→ Upload Icon (1:1 ratio)
→ Display Order: 1
→ Check "Active"
→ Click "Add"
```

### Add Game
```
http://localhost:8000/admin/games.php
→ Add New Game
→ Name: "Cricket Betting"
→ Category: Select from dropdown
→ Upload Banner (16:9 recommended)
→ Description: Use rich text editor
→ Platforms: "Reddy999, Tenexch"
→ Bonus: "₹5000 Welcome Bonus"
→ Min Deposit: "₹500"
→ Check "Featured" and "Active"
→ Click "Add Game"
```

---

## 🔐 Security Notes

### Important URLs to Protect in Production
- `admin/*` - Protect with .htaccess or server config
- `admin/api/submit-inquiry.php` - Add CSRF protection
- `admin/config/database.sqlite` - Move outside web root

### Change Default Password
```
http://localhost:8000/admin/profile.php
→ Change Password section
→ Current: admin123
→ New: your_secure_password
→ Confirm: your_secure_password
→ Click "Change Password"
```

---

## 📱 Mobile Testing

Test responsive design:
```
Chrome DevTools → Toggle Device Toolbar (Ctrl+Shift+M)
→ Select device (iPhone, iPad, etc.)
→ Test all pages
```

---

## 🎉 You're Ready!

All URLs are accessible and functional!

**Start with**: http://localhost:8000/integration-guide.html

**Need help?**: Check TESTING.md or README.md

---

*Last Updated: Today*
