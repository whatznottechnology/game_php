<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Get active platforms ordered by display_order
    $stmt = $db->query("SELECT id, name, logo, website_link, description, display_order 
                       FROM platforms 
                       WHERE status = 'active' 
                       ORDER BY display_order ASC, created_at DESC");
    
    $platforms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Add full URL for logos
    foreach ($platforms as &$platform) {
        if ($platform['logo']) {
            // Use relative URL that works both locally and on deployment
            $platform['logo_url'] = $platform['logo'];
        } else {
            $platform['logo_url'] = null;
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $platforms
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch platforms: ' . $e->getMessage()
    ]);
}
?>