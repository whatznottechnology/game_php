# GameHub - Betting Platform Admin Panel# GameHub - Game Lead Capture Platform



A complete PHP/SQLite admin panel for managing a betting affiliate/lead generation website.A complete responsive static website design for a Game Lead Capture Platform built with HTML, Tailwind CSS, and minimal JavaScript.



## 🚀 Features## 🎮 Features



### Frontend- **Modern Gaming UI**: Clean, colorful, and playful design with gaming aesthetics

- **Lead Generation Landing Pages** - Optimized for Facebook ads- **Fully Responsive**: Mobile-first design that works on all devices

- **Platform Showcase** - Reddy999, Tenexch, KingExch9, BetBhai9, AKQ777- **Interactive Elements**: Smooth animations, hover effects, and transitions

- **Trust Elements** - Testimonials, FAQ, security badges- **Lead Capture System**: Inquiry modal forms for capturing user information

- **Conversion Forms** - Contact forms with instant WhatsApp connection- **SEO Friendly**: Proper meta tags, alt attributes, and semantic HTML

- **Game Categories** - Dynamic category and game display- **Laravel Ready**: Easy to integrate with Laravel backend later

- **Responsive Design** - Mobile-first with Tailwind CSS

## 📁 Project Structure

### Backend Admin Panel

- **Dashboard** - Analytics, visitor stats, inquiry tracking```

- **Site Settings** - Manage logos, contact info, social media/

- **Categories Management** - CRUD with icon uploads (1:1 ratio)├── index.html              # Home page with hero, categories, featured games

- **Games Management** - CRUD with banner images, rich text descriptions├── search.html             # Search page with filtering and results

- **Inquiries Management** - Lead tracking, status updates, filtering├── category.html           # Category page showing games by category

- **Profile Management** - Change username, email, password├── game.html              # Game detail page with screenshots and info

- **RESTful API** - JSON endpoints for frontend integration├── terms.html             # Terms & Conditions page

├── privacy.html           # Privacy Policy page

## 📦 Tech Stack└── assets/

    ├── css/

- **Backend**: PHP 7.4+ with PDO    │   └── style.css      # Custom styles with Tailwind integration

- **Database**: SQLite (single file database)    ├── js/

- **Frontend**: HTML5, Tailwind CSS, JavaScript    │   └── script.js      # Interactive functionality and animations

- **Authentication**: Session-based with bcrypt password hashing    └── img/               # Placeholder images folder

- **Security**: Prepared statements, CSRF protection, file upload validation```



## 🛠️ Installation## 🎨 Design Features



### Prerequisites### Color Scheme

- PHP 7.4 or higher- **Primary Blue**: #3B82F6

- SQLite3 extension enabled- **Primary Purple**: #8B5CF6  

- Apache/Nginx web server (or PHP built-in server for testing)- **Accent Neon**: #00D4FF

- Write permissions for database and upload directories- **Accent Pink**: #EC4899

- **Dark Background**: #0F172A

### Setup Steps- **Card Background**: #1E293B



1. **Clone/Extract the Project**### Typography

   ```bash- **Font Family**: Poppins (Google Fonts)

   cd c:\Users\pmihu\OneDrive\Desktop\gg- **Weights**: 300, 400, 500, 600, 700, 800

   ```

### UI Components

2. **Set File Permissions**- Gradient text effects

   - Ensure `admin/config/` directory is writable (for database.sqlite)- Glass morphism cards

   - Ensure `assets/img/categories/` and `assets/img/games/` are writable- Neon glow animations

- Floating animations

3. **Database Initialization**- Smooth hover transitions

   - Database will be created automatically on first access- Responsive navigation

   - Default admin account is created: `admin` / `admin123`- Modal dialogs

- Loading states

4. **Start Development Server**

   ```bash## 🚀 Pages Overview

   # Using PHP built-in server

   php -S localhost:8000### 1. Home Page (index.html)

   ```- **Hero Section**: Full-width banner with call-to-action

   Or configure Apache/Nginx to serve from project root- **Navigation**: Logo, menu links, and enquiry button

- **Categories**: 8 colorful category cards with hover effects

5. **Access Admin Panel**- **Featured Games**: 6 game cards with thumbnails and CTAs

   - Open browser: `http://localhost:8000/admin/`- **Footer**: Links, contact info, and social icons

   - Login: `admin` / `admin123`

   - **IMPORTANT**: Change default password immediately!### 2. Search Page (search.html)

- **Search Bar**: Real-time search functionality

## 📁 Project Structure- **Quick Filters**: Category filter buttons

- **Results Grid**: Game cards with thumbnails

```- **No Results**: Friendly message when no games found

gg/

├── index.html              # Home page (landing page)### 3. Category Page (category.html)

├── game.html               # Games listing page- **Category Banner**: Large visual header for the category

├── assets/- **Breadcrumb Navigation**: Easy navigation tracking

│   ├── css/               # Custom stylesheets- **Games Grid**: Filtered games by category

│   ├── js/                # JavaScript files- **Related Categories**: Quick access to other categories

│   └── img/

│       ├── categories/    # Category icon uploads### 4. Game Detail Page (game.html)

│       └── games/         # Game banner uploads- **Game Showcase**: Large thumbnail with play overlay

├── admin/- **Game Information**: Title, description, features, requirements

│   ├── config/- **Action Buttons**: Play Now, Enquire, Share via WhatsApp

│   │   ├── database.php   # Database singleton & initialization- **Related Games**: Suggestions for similar games

│   │   ├── auth.php       # Authentication helpers- **System Requirements**: Minimum and recommended specs

│   │   └── database.sqlite # SQLite database file (auto-created)

│   ├── includes/### 5. Terms & Conditions (terms.html)

│   │   ├── header.php     # Admin header component- **Legal Content**: Comprehensive terms and conditions

│   │   └── sidebar.php    # Admin sidebar component- **Structured Layout**: Clear headings and sections

│   ├── api/- **Contact Information**: How to reach support

│   │   ├── categories.php    # GET categories API- **Related Links**: Navigation to privacy policy

│   │   ├── games.php         # GET games API

│   │   ├── settings.php      # GET site settings API### 6. Privacy Policy (privacy.html)

│   │   ├── submit-inquiry.php # POST inquiry API- **Privacy Information**: Detailed privacy policy

│   │   └── track-visitor.php  # POST visitor tracking API- **Data Usage Summary**: Quick overview of data collection

│   ├── index.php          # Dashboard- **User Rights**: Information about user data rights

│   ├── login.php          # Login page- **Contact Details**: Privacy-specific contact information

│   ├── logout.php         # Logout handler

│   ├── settings.php       # Site settings management## 🔧 Technical Features

│   ├── categories.php     # Categories CRUD

│   ├── games.php          # Games CRUD### Responsive Design

│   ├── inquiries.php      # Inquiries management- **Mobile-first**: Optimized for mobile devices

│   └── profile.php        # Admin account settings- **Breakpoints**: Tailored for all screen sizes

└── README.md              # This file- **Grid Layouts**: Adaptive game and category grids

```- **Touch-friendly**: Large buttons and touch targets



## 🗄️ Database Schema### Interactive Elements

- **Modal System**: Inquiry forms with validation

### Tables- **Search Functionality**: Live search with filtering

- **Navigation**: Smooth scrolling and mobile menu

1. **admin_users** - Admin accounts- **Animations**: AOS (Animate On Scroll) integration

   - id, username, password (bcrypt), email, created_at, updated_at- **Loading States**: User feedback during interactions



2. **site_settings** - Site configuration (key-value pairs)### SEO Optimization

   - setting_key, setting_value, updated_at- **Meta Tags**: Proper title, description, keywords

   - Keys: site_name, admin_email, support_email, contact_number, whatsapp_number, site_logo, site_favicon, facebook_url, twitter_url, instagram_url, youtube_url- **Semantic HTML**: Proper heading structure

- **Alt Attributes**: Image accessibility

3. **categories** - Game categories- **Schema Ready**: Easy to add structured data

   - id, name, slug, icon_path, display_order, is_active, created_at, updated_at

### Performance

4. **games** - Betting games- **CDN Assets**: Tailwind CSS and Font Awesome via CDN

   - id, category_id, name, slug, banner_image, description (HTML), platforms, bonus_amount, min_deposit, is_featured, is_active, display_order, created_at, updated_at- **Optimized Images**: Placeholder system for easy replacement

- **Minimal JavaScript**: Lightweight functionality

5. **inquiries** - Customer inquiries/leads- **Fast Loading**: Optimized for quick page loads

   - id, name, mobile, email, platform, interest, deposit_amount, message, game_name, ip_address, user_agent, status (new/contacted/closed), notes, created_at

## 🎯 Lead Capture Features

6. **visitor_logs** - Analytics tracking

   - id, ip_address, user_agent, page_url, referrer, visit_date### Inquiry Modal

- **Global Access**: Available on all pages

## 🔐 Security Features- **Pre-filled Messages**: Context-aware form filling

- **Validation**: Client-side form validation

- **Password Hashing**: Bcrypt with cost factor 10- **Responsive Design**: Works on all devices

- **SQL Injection Protection**: PDO prepared statements

- **Session Management**: Secure session handling### Contact Points

- **File Upload Validation**: Image-only uploads with extension checks- **Floating Buttons**: WhatsApp and Inquiry CTAs

- **Directory Protection**: .htaccess files prevent PHP execution in upload dirs- **Game-specific Inquiries**: Targeted lead capture

- **XSS Protection**: htmlspecialchars() on all output- **Footer Contact**: Traditional contact information

- **Multiple Touchpoints**: Various conversion opportunities

## 📡 API Endpoints

## 🛠️ Technologies Used

### GET /admin/api/categories.php

Fetch all active categories- **HTML5**: Semantic markup

```json- **Tailwind CSS**: Utility-first CSS framework

{- **JavaScript**: Vanilla JS for interactions

  "success": true,- **Font Awesome**: Icon library

  "data": [- **AOS**: Animate On Scroll library

    {- **Google Fonts**: Poppins typography

      "id": 1,

      "name": "Sports Betting",## 🚀 Getting Started

      "slug": "sports-betting",

      "icon_path": "assets/img/categories/sports-betting.png",1. **Clone/Download** the project files

      "display_order": 12. **Open index.html** in your browser

    }3. **Test all pages** and functionality

  ],4. **Customize content** and replace placeholder images

  "count": 15. **Deploy** to your web server

}

```## 📱 Browser Support



### GET /admin/api/games.php- Chrome (latest)

Fetch games (optional filters: ?category=1&featured=true)- Firefox (latest)

```json- Safari (latest)

{- Edge (latest)

  "success": true,- Mobile browsers (iOS Safari, Chrome Mobile)

  "data": [

    {## 🔄 Laravel Integration Ready

      "id": 1,

      "name": "Teen Patti",The static design is structured to be easily converted to Laravel:

      "slug": "teen-patti",

      "category_name": "Card Games",- **Blade Templates**: HTML structure ready for Blade conversion

      "banner_image": "assets/img/games/teen-patti.jpg",- **Component Separation**: Reusable components identified

      "description": "<p>Popular card game...</p>",- **Form Integration**: Forms ready for Laravel validation

      "platforms": "Reddy999, Tenexch",- **Route Structure**: Clear page separation for routing

      "platforms_array": ["Reddy999", "Tenexch"],- **Asset Organization**: Assets organized for Laravel public folder

      "bonus_amount": "₹5000 Welcome Bonus",

      "min_deposit": "₹500",## 📞 Support

      "is_featured": 1

    }For questions or support regarding this design:

  ],

  "count": 1- **Email**: info@gamehub.com

}- **Phone**: +1 (555) 123-4567

```- **Website**: www.gamehub.com



### POST /admin/api/submit-inquiry.php## 📄 License

Submit customer inquiry

```jsonThis project is created for GameHub platform. All rights reserved.

// Request

{---

  "name": "John Doe",

  "mobile": "9876543210",**Built with ❤️ for gamers worldwide**
  "email": "john@example.com",
  "platform": "Reddy999",
  "interest": "Sports Betting",
  "deposit_amount": "5000",
  "message": "I want to start betting",
  "game_name": "Cricket Betting"
}

// Response
{
  "success": true,
  "message": "Inquiry submitted successfully",
  "inquiry_id": 123
}
```

### POST /admin/api/track-visitor.php
Track page visits
```json
// Request
{
  "page_url": "https://example.com/game.html"
}

// Response
{
  "success": true,
  "message": "Visitor tracked successfully"
}
```

### GET /admin/api/settings.php
Fetch site settings
```json
{
  "success": true,
  "data": {
    "site_name": "GameHub",
    "contact_number": "+91 98765 43210",
    "whatsapp_number": "919876543210",
    "facebook_url": "https://facebook.com/gamehub",
    ...
  }
}
```

## 🎯 Usage Guide

### 1. Admin Dashboard
- View total visitors, inquiries, pending requests, and active games
- See platform distribution chart
- View recent inquiries
- Quick stats with today's activity

### 2. Site Settings
- **Basic Info**: Site name, emails, logos
- **Contact**: Phone, WhatsApp
- **Social Media**: Facebook, Twitter, Instagram, YouTube
- All settings reflected in frontend via API

### 3. Categories Management
- Add categories with name and 1:1 icon image
- Set display order for sorting
- Toggle active/inactive status
- Edit/delete categories
- Icons shown in frontend category listing

### 4. Games Management
- Add games with banner images (recommended: 16:9 ratio)
- Rich text HTML editor for descriptions
- Select category from dropdown
- Add platform names (comma-separated)
- Set bonus amount and minimum deposit
- Mark as featured (shows on homepage)
- Toggle active/inactive status
- Set display order

### 5. Inquiries Management
- View all customer inquiries in table format
- Filter by status (new/contacted/closed)
- Search by name, mobile, email, or platform
- Click inquiry to view full details
- Update status and add notes
- Delete unwanted inquiries
- Export functionality (add CSV export if needed)

### 6. Profile Management
- Update admin username and email
- Change password (requires current password)
- View account information and creation date

## 🔧 Configuration

### Change Default Admin
Edit `admin/config/database.php` in the `createDefaultAdmin()` method:
```php
$username = 'your_admin';
$password = password_hash('your_password', PASSWORD_DEFAULT);
```

### Upload Limits
Modify PHP settings in `.htaccess` or `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Database Location
Database file: `admin/config/database.sqlite`
- Backup regularly for production use
- Keep outside web root for security in production

## 📊 Analytics

The dashboard tracks:
- Total visitors (all-time and today)
- Total inquiries (all-time and today)
- Pending inquiries needing attention
- Active games count
- Platform distribution (top 5 platforms by inquiry count)
- Interest distribution (top 5 interests)
- Recent inquiries (last 10)

## 🚨 Important Notes

### Security
1. **Change default password immediately** after first login
2. For production: Move database outside web root
3. Use HTTPS in production
4. Set proper file permissions (644 for files, 755 for directories)
5. Keep PHP updated to latest stable version

### Backups
- Backup `admin/config/database.sqlite` regularly
- Backup uploaded images in `assets/img/categories/` and `assets/img/games/`

### Performance
- SQLite is suitable for small-medium traffic
- For high traffic, consider migrating to MySQL/PostgreSQL
- Add caching (Redis/Memcached) for API responses

## 🐛 Troubleshooting

### Database errors
- Ensure `admin/config/` directory is writable
- Check SQLite3 PHP extension is enabled: `php -m | grep sqlite`

### Upload errors
- Check directory permissions on `assets/img/categories/` and `assets/img/games/`
- Verify PHP upload limits in `phpinfo()`

### Login issues
- Clear browser cookies/cache
- Check session.save_path in PHP configuration
- Verify PHP session extension is enabled

### API not working
- Check CORS headers if calling from different domain
- Verify JSON content-type in requests
- Check browser console for errors

## 📝 TODO / Future Enhancements

- [ ] CSV export for inquiries
- [ ] Bulk delete for inquiries
- [ ] Email notifications for new inquiries
- [ ] WhatsApp API integration
- [ ] Advanced analytics (conversion rates, referrer tracking)
- [ ] Multi-admin support with roles
- [ ] Activity logs
- [ ] Backup/restore functionality
- [ ] Frontend game filtering by category
- [ ] Search functionality on frontend

## 📄 License

This project is for educational and commercial use. Modify as needed for your requirements.

## 👨‍💻 Support

For issues or questions:
1. Check the troubleshooting section
2. Review PHP error logs
3. Check browser console for frontend errors

## 🎉 Getting Started Checklist

- [ ] Install PHP 7.4+ and enable SQLite3
- [ ] Extract project files
- [ ] Set directory permissions
- [ ] Start PHP server or configure Apache/Nginx
- [ ] Login to admin panel (admin/admin123)
- [ ] Change default password
- [ ] Configure site settings
- [ ] Add categories with icons
- [ ] Add games with banners
- [ ] Test frontend pages (index.html, game.html)
- [ ] Test API endpoints
- [ ] Configure frontend to use real API data

---

**Built with ❤️ for betting affiliate businesses**
