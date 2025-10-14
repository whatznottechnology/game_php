<?php
/**
 * Authentication Helper Functions
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function login($username, $password) {
    require_once __DIR__ . '/database.php';
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_email'] = $user['email'];
        return true;
    }
    
    return false;
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

function getAdminInfo() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, username, email, created_at, updated_at FROM admin_users WHERE id = ?");
        $stmt->execute([$_SESSION['admin_id']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            return $admin;
        } else {
            // If user not found in database, return session data as fallback
            return [
                'id' => $_SESSION['admin_id'],
                'username' => $_SESSION['admin_username'],
                'email' => $_SESSION['admin_email'],
                'created_at' => null,
                'updated_at' => null
            ];
        }
    } catch (Exception $e) {
        // In case of database error, return session data as fallback
        return [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'email' => $_SESSION['admin_email'],
            'created_at' => null,
            'updated_at' => null
        ];
    }
}
