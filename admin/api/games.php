<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Get category filter if provided
    $category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
    $featured_only = isset($_GET['featured']) && $_GET['featured'] === 'true';
    
    // Build query
    $query = "SELECT g.*, c.name as category_name, c.slug as category_slug 
              FROM games g 
              LEFT JOIN categories c ON g.category_id = c.id 
              WHERE g.is_active = 1";
    
    $params = [];
    
    if ($category_id) {
        $query .= " AND g.category_id = ?";
        $params[] = $category_id;
    }
    
    if ($featured_only) {
        $query .= " AND g.is_featured = 1";
    }
    
    $query .= " ORDER BY g.display_order ASC, g.name ASC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Parse platforms string into array
    foreach ($games as &$game) {
        if ($game['platforms']) {
            $game['platforms_array'] = array_map('trim', explode(',', $game['platforms']));
        } else {
            $game['platforms_array'] = [];
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $games,
        'count' => count($games)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch games',
        'message' => $e->getMessage()
    ]);
}
