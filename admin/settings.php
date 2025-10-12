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
            <form method="POST" action="" class="space-y-6">
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
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Site Logo Path</label>
                            <input type="text" name="settings[site_logo]"
                                   value="<?php echo htmlspecialchars($settingsData['site_logo'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="assets/img/logo.png">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Favicon Path</label>
                            <input type="text" name="settings[site_favicon]"
                                   value="<?php echo htmlspecialchars($settingsData['site_favicon'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="assets/img/favicon.ico">
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
                                <i class="fab fa-whatsapp mr-2 text-green-600"></i>WhatsApp Number *
                            </label>
                            <input type="text" name="settings[whatsapp_number]" required
                                   value="<?php echo htmlspecialchars($settingsData['whatsapp_number'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                   placeholder="1234567890">
                            <p class="text-xs text-gray-500 mt-1">Enter without + or country code</p>
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
