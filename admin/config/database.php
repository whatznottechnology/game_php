<?php
/**
 * Database Configuration and Initialization
 * Using SQLite for easy deployment
 */

class Database {
    private $db;
    private static $instance = null;
    
    private function __construct() {
        try {
            // Create database file in config directory
            $dbPath = __DIR__ . '/database.sqlite';
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initializeTables();
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->db;
    }
    
    private function initializeTables() {
        // Admin Users Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            email TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Site Settings Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS site_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT NOT NULL UNIQUE,
            setting_value TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Categories Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            icon_path TEXT,
            display_order INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Games Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS games (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            banner_image TEXT,
            description TEXT,
            platforms TEXT,
            bonus_amount TEXT,
            min_deposit TEXT,
            is_featured INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            display_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        )");
        
        // Inquiries Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS inquiries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            mobile TEXT NOT NULL,
            email TEXT,
            platform TEXT,
            interest TEXT,
            deposit_amount TEXT,
            message TEXT,
            game_name TEXT,
            ip_address TEXT,
            user_agent TEXT,
            status TEXT DEFAULT 'new',
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Platforms Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS platforms (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            logo TEXT,
            website_link TEXT,
            description TEXT,
            display_order INTEGER DEFAULT 0,
            status TEXT DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Visitor Analytics Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS visitor_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ip_address TEXT,
            user_agent TEXT,
            page_url TEXT,
            referrer TEXT,
            visit_date DATE DEFAULT CURRENT_DATE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Banners Table
        $this->db->exec("CREATE TABLE IF NOT EXISTS banners (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            subtitle TEXT,
            description TEXT,
            button_text TEXT,
            button_link TEXT,
            background_image TEXT,
            display_order INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Insert default admin user if not exists
        $this->createDefaultAdmin();
        
        // Insert default settings if not exists
        $this->createDefaultSettings();
    }
    
    private function createDefaultAdmin() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM admin_users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            // Default credentials: admin / admin123
            $password = password_hash('admin123', PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute(['admin', $password, 'admin@gamehub.com']);
        }
    }
    
    private function createDefaultSettings() {
        $defaultSettings = [
            'site_name' => 'GameHub - Premium Betting Platform',
            'site_logo' => 'assets/img/logo_1760458275.png',
            'site_favicon' => 'assets/img/favicon.ico',
            'contact_number' => '+1 (555) 123-4567',
            'whatsapp_number' => '+91 1234567890',
            'facebook_url' => 'https://facebook.com/gamehub',
            'twitter_url' => 'https://twitter.com/gamehub',
            'instagram_url' => 'https://instagram.com/gamehub',
            'youtube_url' => 'https://youtube.com/gamehub',
            'telegram_url' => 'https://t.me/gamehub',
            'support_email' => 'support@gamehub.com',
            'admin_email' => 'admin@gamehub.com'
        ];
        
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM site_settings");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] == 0) {
            $stmt = $this->db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
            foreach ($defaultSettings as $key => $value) {
                $stmt->execute([$key, $value]);
            }
        } else {
            // Add telegram_url if it doesn't exist
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM site_settings WHERE setting_key = 'telegram_url'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['count'] == 0) {
                $stmt = $this->db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
                $stmt->execute(['telegram_url', 'https://t.me/gamehub']);
            }
        }
    }
}

// Initialize database
Database::getInstance();
