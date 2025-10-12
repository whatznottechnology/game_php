# Testing the GameHub Backend + Frontend Integration

## ✅ Complete Features Implemented

### Backend (PHP + SQLite)
- ✅ Database with 6 tables (users, settings, categories, games, inquiries, visitors)
- ✅ Admin login system (admin/admin123)
- ✅ Dashboard with analytics and charts
- ✅ Site settings management
- ✅ Categories CRUD with icon uploads
- ✅ Games CRUD with banner uploads and rich text editor
- ✅ Inquiries management with filtering and status updates
- ✅ Profile management
- ✅ 5 RESTful API endpoints (JSON responses)

### Frontend (HTML + JavaScript)
- ✅ Dynamic content loading from backend APIs
- ✅ Categories loaded from database
- ✅ Featured games display
- ✅ All games listing with search and filter
- ✅ Inquiry form submission to database
- ✅ Visitor tracking
- ✅ Responsive design with Tailwind CSS

## 🚀 How to Test

### Option 1: Using PHP Built-in Server (Recommended)

If you have PHP installed:

```bash
# Navigate to project directory
cd c:\Users\pmihu\OneDrive\Desktop\gg

# Start PHP server
php -S localhost:8000

# If PHP not in PATH, use full path:
C:\php\php.exe -S localhost:8000
```

Then open in browser:
- **Setup Check**: http://localhost:8000/setup.php
- **Seed Database**: http://localhost:8000/admin/seed-database.php (adds dummy data)
- **Admin Panel**: http://localhost:8000/admin/
- **Home Page**: http://localhost:8000/index.html
- **Games Page**: http://localhost:8000/games.html

### Option 2: Using XAMPP

1. Copy the `gg` folder to `C:\xampp\htdocs\`
2. Start XAMPP Apache
3. Open: http://localhost/gg/

### Option 3: Using WAMP

1. Copy the `gg` folder to `C:\wamp64\www\`
2. Start WAMP
3. Open: http://localhost/gg/

## 📋 Testing Checklist

### 1. Backend Setup (Admin Panel)

1. ✅ Open http://localhost:8000/setup.php
   - Check all requirements are green
   - Click "Go to Admin Panel"

2. ✅ Seed Database with Dummy Data (OPTIONAL - Quick Start)
   - Open http://localhost:8000/admin/seed-database.php
   - This adds:
     * 6 Categories (Sports Betting, Live Casino, Card Games, etc.)
     * 15 Games with banners and descriptions
     * 5 Sample inquiries
     * 25 Visitor logs
   - **Skip this if you want to add content manually**

3. ✅ Login to Admin (http://localhost:8000/admin/)
   - Username: `admin`
   - Password: `admin123`
   - Should see dashboard with stats

4. ✅ Manual Content Addition (if you skipped seeding)
   - Go to "Categories" menu
   - Add categories (see below for details)
   - Go to "Games" menu
   - Add games (see below for details)

### Manual Content Addition Steps

#### Add Categories
   - Go to "Categories" menu
   - Click "Add New Category"
   - Add: Name: "Sports Betting", upload icon (optional), Display Order: 1
   - Add: Name: "Live Casino", Display Order: 2
   - Add: Name: "Card Games", Display Order: 3

4. ✅ Add Games
   - Go to "Games" menu
   - Click "Add New Game"
   - Fill in:
     - Name: "Cricket Betting"
     - Category: "Sports Betting"
     - Banner: upload image (optional)
     - Description: "Bet on IPL, T20, Test matches..."
     - Platforms: "Reddy999, Tenexch"
     - Bonus: "₹5000 Welcome Bonus"
     - Min Deposit: "₹500"
     - Check "Featured" and "Active"
   - Add 2-3 more games

5. ✅ Configure Site Settings
   - Go to "Site Settings"
   - Update contact number, WhatsApp number
   - Add social media links
   - Save settings

### 2. Frontend Testing

1. ✅ Home Page (http://localhost:8000/index.html)
   - Should see categories loaded in left sidebar
   - Should see featured games in grid
   - Click category button → should redirect to games page

2. ✅ All Games Page (http://localhost:8000/games.html)
   - Should display all active games
   - Test search functionality
   - Test category filter chips
   - Game count should update

3. ✅ Inquiry Form
   - Click "Enquiry" or "Play Now" button
   - Fill in the form:
     - Name: "Test User"
     - Mobile: "9876543210"
     - Platform: "Reddy999"
     - Interest: "Cricket Betting"
   - Submit form
   - Should see success message

4. ✅ Check Admin Panel
   - Go to Admin → Inquiries
   - Should see the submitted inquiry
   - Click eye icon to view details
   - Update status to "Contacted"
   - Add notes

### 3. API Testing

Test APIs directly in browser:

- http://localhost:8000/admin/api/categories.php
- http://localhost:8000/admin/api/games.php
- http://localhost:8000/admin/api/games.php?featured=true
- http://localhost:8000/admin/api/settings.php

All should return JSON data.

## 🔧 Troubleshooting

### PHP not recognized
- Download PHP from https://windows.php.net/download/
- Extract to C:\php
- Add C:\php to system PATH
- Enable SQLite extension in php.ini

### Database not created
- Check admin/config/ folder is writable
- Look for database.sqlite file after first access
- Check PHP error logs

### API returning errors
- Check browser console for errors
- Verify API URLs are correct
- Check network tab in DevTools

### Images not uploading
- Ensure assets/img/categories/ and assets/img/games/ are writable
- Check PHP upload_max_filesize in php.ini

## 📊 Expected Results

After adding 3 categories and 5 games:

### Admin Dashboard Should Show:
- Total Visitors: (increases on each page visit)
- Total Inquiries: 1 (from test form)
- Pending Inquiries: 1
- Active Games: 5

### Frontend Should Show:
- Home page: 3 category buttons in sidebar + featured games grid
- Games page: All 5 games with search and category filtering
- Working inquiry forms with database submission

## 🎯 Full Workflow Test

1. **Admin adds content**:
   - Login to admin panel
   - Add 3 categories with icons
   - Add 5 games (mark 3 as featured)
   - Configure site settings

2. **Customer visits site**:
   - Open index.html
   - See categories and featured games loaded from database
   - Click a category → goes to games.html filtered by that category
   - Click "Play Now" → inquiry modal opens
   - Submit inquiry form

3. **Admin manages inquiry**:
   - Go to admin → Inquiries
   - See new inquiry with "New" status
   - Click to view full details
   - Change status to "Contacted"
   - Add notes: "Called customer, created ID"

4. **Analytics**:
   - Dashboard shows updated visitor count
   - Platform distribution chart shows inquiry platforms
   - Recent inquiries table shows latest submissions

## 📝 Notes

- **Default Admin**: admin / admin123 (CHANGE THIS!)
- **Database Location**: admin/config/database.sqlite
- **Upload Folders**: assets/img/categories/ and assets/img/games/
- **APIs**: All in admin/api/ folder

## ✨ Key Integration Points

1. **Categories**: 
   - Backend: admin/categories.php (CRUD)
   - API: admin/api/categories.php
   - Frontend: Loaded in index.html sidebar and games.html filters

2. **Games**:
   - Backend: admin/games.php (CRUD with TinyMCE editor)
   - API: admin/api/games.php (with ?featured=true and ?category=id filters)
   - Frontend: Featured on index.html, all on games.html

3. **Inquiries**:
   - Backend: admin/inquiries.php (view, update status, delete)
   - API: admin/api/submit-inquiry.php (POST endpoint)
   - Frontend: Modal forms on index.html and games.html

4. **Settings**:
   - Backend: admin/settings.php (manage 12 settings)
   - API: admin/api/settings.php
   - Frontend: Contact info, social links auto-populated

## 🎉 Success Criteria

✅ Admin can login and manage all content
✅ Categories appear dynamically on frontend
✅ Games load from database with search/filter
✅ Inquiry forms submit to database
✅ Admin can view and manage inquiries
✅ Analytics show real-time data
✅ No hardcoded content (all from backend)

---

**You're now fully integrated! 🚀**
