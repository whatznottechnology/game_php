<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    require_once '../config/database.php';
    
    $db = Database::getInstance()->getConnection();
    
    // Fetch site configuration
    $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('site_name', 'site_logo', 'site_favicon', 'whatsapp_number', 'contact_number', 'admin_email', 'support_email', 'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'telegram_url')");
    
    $config = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $config[$row['setting_key']] = $row['setting_value'];
    }
    
    // Ensure we have default values
    $config['site_name'] = $config['site_name'] ?? 'GameHub';
    $config['site_logo'] = $config['site_logo'] ?? '';
    $config['site_favicon'] = $config['site_favicon'] ?? '';
    $config['whatsapp_number'] = $config['whatsapp_number'] ?? '';
    $config['contact_number'] = $config['contact_number'] ?? '';
    $config['admin_email'] = $config['admin_email'] ?? '';
    $config['support_email'] = $config['support_email'] ?? '';
    $config['facebook_url'] = $config['facebook_url'] ?? '';
    $config['twitter_url'] = $config['twitter_url'] ?? '';
    $config['instagram_url'] = $config['instagram_url'] ?? '';
    $config['youtube_url'] = $config['youtube_url'] ?? '';
    $config['telegram_url'] = $config['telegram_url'] ?? '';
    
    // Check if files exist
    if (!empty($config['site_logo']) && !file_exists('../../' . $config['site_logo'])) {
        $config['site_logo'] = '';
    }
    
    if (!empty($config['site_favicon']) && !file_exists('../../' . $config['site_favicon'])) {
        $config['site_favicon'] = '';
    }
    
    echo json_encode([
        'success' => true,
        'data' => $config,
        'message' => 'Site configuration retrieved successfully'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to retrieve site configuration',
        'message' => $e->getMessage()
    ]);
}
?>