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
    
    // Validate required fields
    $required = ['name', 'mobile', 'platform', 'interest'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    // Sanitize and prepare data
    $name = trim($data['name']);
    $mobile = trim($data['mobile']);
    $email = isset($data['email']) ? trim($data['email']) : '';
    $platform = trim($data['platform']);
    $interest = trim($data['interest']);
    $deposit_amount = isset($data['deposit_amount']) ? trim($data['deposit_amount']) : '';
    $message = isset($data['message']) ? trim($data['message']) : '';
    $game_name = isset($data['game_name']) ? trim($data['game_name']) : '';
    
    // Get visitor info
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    // Insert inquiry
    $stmt = $db->prepare("INSERT INTO inquiries 
        (name, mobile, email, platform, interest, deposit_amount, message, game_name, ip_address, user_agent, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new')");
    
    $stmt->execute([
        $name,
        $mobile,
        $email,
        $platform,
        $interest,
        $deposit_amount,
        $message,
        $game_name,
        $ip_address,
        $user_agent
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Inquiry submitted successfully',
        'inquiry_id' => $db->lastInsertId()
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to submit inquiry',
        'message' => $e->getMessage()
    ]);
}
