# Banner Image Upload Guide

## 📸 How to Upload Banner Images in Admin Panel

### Step-by-Step Instructions:

1. **Login to Admin Panel**
   - Go to `http://localhost:8000/admin/`
   - Login with credentials: `admin` / `admin123`

2. **Navigate to Games Section**
   - Click on "Games" in the left sidebar
   - You'll see the list of all games

3. **Add New Game (with banner upload)**
   - Scroll down to the "Add New Game" form
   - Fill in the game details:
     - **Game Name**: Enter the game name
     - **Category**: Select from dropdown
     - **Banner Image**: Click "Choose File" and select an image
       - Recommended size: 800x450 pixels
       - Formats: JPG, PNG, GIF
       - Max file size: 2MB (configurable)
     - **Platforms**: Enter platform names (comma-separated)
     - **Bonus Amount**: e.g., ₹5,000
     - **Min Deposit**: e.g., ₹500
     - **Description**: Rich text editor for detailed description
   - Click "Add Game" button

4. **Edit Existing Game (change banner)**
   - Find the game you want to edit
   - Click the "Edit" button
   - You'll see the current banner image preview
   - Click "Choose File" to select a new banner image
   - Leave blank if you want to keep the existing banner
   - Click "Update Game" to save

### 📁 Image Storage Location

- All banner images are stored in: `assets/img/games/`
- Images are automatically renamed with slug + timestamp
- Example: `teen-patti-1697123456.jpg`

### 🎨 Banner Image Guidelines

**Recommended Specifications:**
- **Dimensions**: 800x450 pixels (16:9 aspect ratio)
- **File Size**: Under 500KB for fast loading
- **Format**: JPG (for photos), PNG (for graphics with transparency)
- **Quality**: High quality but compressed

**Design Tips:**
- Use eye-catching visuals related to the game
- Include game logo or branding
- Avoid too much text in the image
- Ensure good contrast for visibility

### 🖼️ Frontend Display

Banner images are displayed in:
1. **Home Page** - Featured games grid
2. **Games Listing** - All games grid
3. **Search Results** - Filtered games

All images are responsive and automatically adjust to screen size.

### ⚠️ Important Notes

- **Automatic Upload**: Images are automatically uploaded when you submit the form
- **Backup Original**: Keep a copy of your original images before uploading
- **File Naming**: System automatically renames files to prevent conflicts
- **Directory Creation**: The `assets/img/games/` folder is created automatically if it doesn't exist
- **Existing Images**: When editing, if you don't select a new image, the existing banner is retained

### 🔧 Technical Details

**Upload Handling:**
```php
// File upload location
$uploadDir = '../assets/img/games/';

// Allowed file types
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

// Max file size
$maxSize = 2 * 1024 * 1024; // 2MB

// File naming convention
$fileName = $slug . '-' . time() . '.' . $fileExtension;
```

**Frontend Display:**
```javascript
// Banner is displayed from database path
<img src="${game.banner_image}" alt="${game.name}">

// Example path: assets/img/games/cricket-betting-1697123456.jpg
```

### 🎯 Quick Start - Seed Database

If you want to quickly populate your site with sample games and banners:

1. Visit: `http://localhost:8000/admin/seed-database.php`
2. Click "Seed Database" button
3. Database will be populated with 15 games
4. All games will have professional CDN banner images
5. Login to admin and start editing/replacing banners with your own images

### 📊 Alternative: Use CDN Images

Instead of uploading images, you can also:
1. Use image URLs from CDN services (Unsplash, Cloudinary, etc.)
2. Enter the full URL in the banner field
3. Example: `https://images.unsplash.com/photo-123...`

This method:
- ✅ Saves server storage
- ✅ Faster loading (served from CDN)
- ✅ No upload needed
- ❌ Requires internet connection
- ❌ Depends on external service

### 🆘 Troubleshooting

**Problem: Image not uploading**
- Check file size (must be under 2MB)
- Check file format (JPG, PNG, GIF only)
- Ensure `assets/img/games/` folder has write permissions

**Problem: Image not showing on frontend**
- Verify the image path in database
- Check if image file exists in `assets/img/games/`
- Clear browser cache
- Check browser console for errors

**Problem: Image quality is poor**
- Upload higher resolution images (800x450 recommended)
- Use JPG format with 80-90% quality
- Avoid over-compression

---

## 🎨 Example Workflow

### Adding a New Game with Banner:

1. **Prepare Banner Image**
   - Create/download a 800x450 image
   - Name it descriptively: `cricket-betting.jpg`
   - Optimize file size to ~200-300KB

2. **Add Game in Admin**
   - Login to admin panel
   - Go to Games section
   - Fill form:
     - Name: "Cricket Betting"
     - Category: "Sports Betting"
     - Banner: Select `cricket-betting.jpg`
     - Description: Add rich text description
     - Platforms: "Reddy999, Tenexch"
     - Bonus: "₹5,000"
     - Min Deposit: "₹500"
   - Check "Featured" if it's a featured game
   - Click "Add Game"

3. **Verify on Frontend**
   - Open `http://localhost:8000/index.html`
   - Game should appear with banner image
   - Click on category to filter
   - Banner should be displayed in card

### Editing Game Banner:

1. Go to Games in admin panel
2. Click "Edit" on the game
3. See current banner preview
4. Click "Choose File" to select new banner
5. Select new image file
6. Click "Update Game"
7. Verify on frontend that banner is updated

---

**Note**: The admin panel already has full banner upload functionality built-in. No additional setup needed!
