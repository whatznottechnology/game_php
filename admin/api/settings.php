<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Fetch all settings
    $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert to associative array
    $settings = [];
    foreach ($results as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $settings
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch settings',
        'message' => $e->getMessage()
    ]);
}
