<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Fetch all active categories ordered by display_order
    $stmt = $db->query("SELECT id, name, slug, icon_path, display_order 
                        FROM categories 
                        WHERE is_active = 1 
                        ORDER BY display_order ASC, name ASC");
    
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $categories,
        'count' => count($categories)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch categories',
        'message' => $e->getMessage()
    ]);
}
