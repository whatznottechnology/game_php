<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    require_once '../config/database.php';
    
    $db = Database::getInstance()->getConnection();
    
    // Get game ID from URL parameter
    $gameId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($gameId <= 0) {
        throw new Exception('Invalid game ID');
    }
    
    // Get game details with category information
    $stmt = $db->prepare("
        SELECT 
            g.id, 
            g.name, 
            g.slug,
            g.banner_image, 
            g.description, 
            g.platforms, 
            g.bonus_amount, 
            g.min_deposit,
            g.is_featured,
            c.name as category_name,
            c.slug as category_slug
        FROM games g 
        LEFT JOIN categories c ON g.category_id = c.id 
        WHERE g.id = ? AND g.is_active = 1
    ");
    
    $stmt->execute([$gameId]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$game) {
        throw new Exception('Game not found');
    }
    
    // Process data for frontend
    $game['id'] = (int)$game['id'];
    $game['is_featured'] = (bool)$game['is_featured'];
    
    // Make banner image path absolute if it exists
    if ($game['banner_image']) {
        // Remove any existing '../' and ensure correct path
        $game['banner_image'] = str_replace('../', '', $game['banner_image']);
    }
    
    // Parse platforms into array if it's a string
    if ($game['platforms']) {
        $game['platforms_array'] = array_map('trim', explode(',', $game['platforms']));
    } else {
        $game['platforms_array'] = [];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $game
    ]);
    
} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Game not found',
        'message' => $e->getMessage()
    ]);
}
?>