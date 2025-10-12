<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Get visitor info
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $page_url = isset($data['page_url']) ? trim($data['page_url']) : '';
    $referrer = $_SERVER['HTTP_REFERER'] ?? '';
    
    // Insert visitor log
    $stmt = $db->prepare("INSERT INTO visitor_logs 
        (ip_address, user_agent, page_url, referrer) 
        VALUES (?, ?, ?, ?)");
    
    $stmt->execute([
        $ip_address,
        $user_agent,
        $page_url,
        $referrer
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Visitor tracked successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to track visitor',
        'message' => $e->getMessage()
    ]);
}
