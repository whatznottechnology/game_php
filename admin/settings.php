<?php
require_once 'config/database.php';
require_once 'config/auth.php';

requireLogin();

$db = Database::getInstance()->getConnection();
$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $settings = $_POST['settings'] ?? [];
        
        // Handle file uploads
        $uploadDir = '../assets/uploads/';
        
        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logoFile = $_FILES['logo'];
            $logoExt = strtolower(pathinfo($logoFile['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
            
            if (in_array($logoExt, $allowedExtensions)) {
                $logoName = 'logo_' . time() . '.' . $logoExt;
                $logoPath = $uploadDir . $logoName;
                
                if (move_uploaded_file($logoFile['tmp_name'], $logoPath)) {
                    $settings['site_logo'] = 'assets/uploads/' . $logoName;
                    
                    // Delete old logo if exists
                    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'site_logo'");
                    $stmt->execute();
                    $oldLogo = $stmt->fetchColumn();
                    if ($oldLogo && file_exists('../' . $oldLogo)) {
                        unlink('../' . $oldLogo);
                    }
                }
            } else {
                throw new Exception('Invalid logo file format. Please use JPG, PNG, GIF, or SVG.');
            }
        }
        
        // Handle favicon upload
        if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
            $faviconFile = $_FILES['favicon'];
            $faviconExt = strtolower(pathinfo($faviconFile['name'], PATHINFO_EXTENSION));
            $allowedFaviconExtensions = ['ico', 'png', 'jpg', 'jpeg', 'gif'];
            
            if (in_array($faviconExt, $allowedFaviconExtensions)) {
                $faviconName = 'favicon_' . time() . '.' . $faviconExt;
                $faviconPath = $uploadDir . $faviconName;
                
                if (move_uploaded_file($faviconFile['tmp_name'], $faviconPath)) {
                    $settings['site_favicon'] = 'assets/uploads/' . $faviconName;
                    
                    // Delete old favicon if exists
                    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'site_favicon'");
                    $stmt->execute();
                    $oldFavicon = $stmt->fetchColumn();
                    if ($oldFavicon && file_exists('../' . $oldFavicon)) {
                        unlink('../' . $oldFavicon);
                    }
                }
            } else {
                throw new Exception('Invalid favicon file format. Please use ICO, PNG, JPG, or GIF.');
            }
        }
        
        $stmt = $db->prepare("UPDATE site_settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?");
        
        foreach ($settings as $key => $value) {
            $stmt->execute([$value, $key]);
        }
        
        $message = 'Settings updated successfully!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error updating settings: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Fetch current settings
$stmt = $db->query("SELECT * FROM site_settings");
$settingsData = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settingsData[$row['setting_key']] = $row['setting_value'];
}

// Get statistics for sidebar
$stmt = $db->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
$stats['pending_inquiries'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $db->query("SELECT COUNT(*) as count FROM games WHERE is_active = 1");
$stats['total_games'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$admin = getAdminInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - GameHub Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>
    
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Site Settings</h1>
                <p class="text-gray-600 mt-2">Manage your website configuration and contact information</p>
            </div>
            
            <!-- Success/Error Message -->
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Settings Form -->
            <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Site Name *</label>
                            <input type="text" name="settings[site_name]" required
                                   value="<?php echo htmlspecialchars($settingsData['site_name'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="GameHub - Your Tagline">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Admin Email *</label>
                            <input type="email" name="settings[admin_email]" required
                                   value="<?php echo htmlspecialchars($settingsData['admin_email'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="admin@gamehub.com">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Support Email *</label>
                            <input type="email" name="settings[support_email]" required
                                   value="<?php echo htmlspecialchars($settingsData['support_email'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="support@gamehub.com">
                        </div>
                    </div>
                </div>
                
                <!-- Logo and Favicon -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-image text-purple-600 mr-2"></i>Logo & Favicon
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-picture-o text-blue-600 mr-2"></i>Site Logo
                            </label>
                            <div class="space-y-3">
                                <!-- Current Logo Display -->
                                <?php if (!empty($settingsData['site_logo']) && file_exists('../' . $settingsData['site_logo'])): ?>
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 mb-2">Current Logo:</p>
                                        <img src="../<?php echo htmlspecialchars($settingsData['site_logo']); ?>" 
                                             alt="Current Logo" 
                                             class="max-h-16 max-w-32 object-contain bg-white border rounded">
                                    </div>
                                <?php else: ?>
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 text-center">
                                        <i class="fas fa-image text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500">No logo uploaded</p>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Upload Input -->
                                <input type="file" name="logo" accept=".jpg,.jpeg,.png,.gif,.svg"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500">Supported formats: JPG, PNG, GIF, SVG (Max 2MB)</p>
                            </div>
                        </div>
                        
                        <!-- Favicon Upload -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-star text-yellow-600 mr-2"></i>Site Favicon
                            </label>
                            <div class="space-y-3">
                                <!-- Current Favicon Display -->
                                <?php if (!empty($settingsData['site_favicon']) && file_exists('../' . $settingsData['site_favicon'])): ?>
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 mb-2">Current Favicon:</p>
                                        <img src="../<?php echo htmlspecialchars($settingsData['site_favicon']); ?>" 
                                             alt="Current Favicon" 
                                             class="w-8 h-8 object-contain bg-white border rounded">
                                    </div>
                                <?php else: ?>
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 text-center">
                                        <i class="fas fa-star text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm text-gray-500">No favicon uploaded</p>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Upload Input -->
                                <input type="file" name="favicon" accept=".ico,.png,.jpg,.jpeg,.gif"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-yellow-50 file:text-yellow-600 hover:file:bg-yellow-100">
                                <p class="text-xs text-gray-500">Supported formats: ICO, PNG, JPG, GIF (Max 1MB)</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-phone text-green-600 mr-2"></i>Contact Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>Contact Number *
                            </label>
                            <input type="text" name="settings[contact_number]" required
                                   value="<?php echo htmlspecialchars($settingsData['contact_number'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="+1 (555) 123-4567">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fab fa-whatsapp mr-2 text-green-600"></i>WhatsApp Number (with country code) *
                            </label>
                            <input type="text" name="settings[whatsapp_number]" required
                                   value="<?php echo htmlspecialchars($settingsData['whatsapp_number'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="+91 1234567890 or +1 234-567-8900">
                            <p class="text-xs text-gray-500 mt-1">Enter with country code (e.g., +91 for India, +1 for US)</p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media Links -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-share-alt text-purple-600 mr-2"></i>Social Media Links
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fab fa-facebook mr-2 text-blue-600"></i>Facebook URL
                            </label>
                            <input type="url" name="settings[facebook_url]"
                                   value="<?php echo htmlspecialchars($settingsData['facebook_url'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="https://facebook.com/gamehub">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fab fa-twitter mr-2 text-blue-400"></i>Twitter URL
                            </label>
                            <input type="url" name="settings[twitter_url]"
                                   value="<?php echo htmlspecialchars($settingsData['twitter_url'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="https://twitter.com/gamehub">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fab fa-instagram mr-2 text-pink-600"></i>Instagram URL
                            </label>
                            <input type="url" name="settings[instagram_url]"
                                   value="<?php echo htmlspecialchars($settingsData['instagram_url'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="https://instagram.com/gamehub">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fab fa-youtube mr-2 text-red-600"></i>YouTube URL
                            </label>
                            <input type="url" name="settings[youtube_url]"
                                   value="<?php echo htmlspecialchars($settingsData['youtube_url'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="https://youtube.com/gamehub">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fab fa-telegram mr-2 text-blue-500"></i>Telegram URL
                            </label>
                            <input type="url" name="settings[telegram_url]"
                                   value="<?php echo htmlspecialchars($settingsData['telegram_url'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="https://t.me/gamehub">
                        </div>
                    </div>
                </div>
                
                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
