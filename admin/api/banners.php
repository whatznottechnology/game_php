<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    require_once '../config/database.php';
    
    $db = Database::getInstance()->getConnection();
    
    // Get all active banners ordered by display order
    $stmt = $db->prepare("SELECT id, title, subtitle, description, button_text, button_link, background_image, display_order FROM banners WHERE is_active = 1 ORDER BY display_order ASC, created_at ASC");
    $stmt->execute();
    
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Process banners for frontend
    foreach ($banners as &$banner) {
        // Make image path correct for frontend if it exists
        if ($banner['background_image']) {
            // Remove any existing '../' prefix and ensure correct path with proper URL encoding
            $cleanPath = str_replace('../', '', $banner['background_image']);
            $parts = pathinfo($cleanPath);
            $banner['background_image'] = $parts['dirname'] . '/' . rawurlencode($parts['basename']);
        }
        
        // Convert string values to proper types
        $banner['id'] = (int)$banner['id'];
        $banner['display_order'] = (int)$banner['display_order'];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $banners,
        'count' => count($banners)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch banners',
        'message' => $e->getMessage()
    ]);
}
?>