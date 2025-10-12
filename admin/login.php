<?php
require_once 'config/database.php';
require_once 'config/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - GameHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-600 to-purple-700 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="bg-blue-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gamepad text-4xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-gray-600 mt-2">GameHub Management System</p>
            </div>
            
            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input type="text" name="username" required 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                           placeholder="Enter your username" autofocus>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                           placeholder="Enter your password">
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard
                </button>
            </form>
            
            <!-- Default Credentials Info -->
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i><strong>Default Login:</strong><br>
                    Username: <code class="bg-yellow-100 px-2 py-1 rounded">admin</code><br>
                    Password: <code class="bg-yellow-100 px-2 py-1 rounded">admin123</code>
                </p>
            </div>
        </div>
        
        <p class="text-center text-white mt-6">
            <i class="fas fa-shield-alt mr-2"></i>Secure Admin Access
        </p>
    </div>
</body>
</html>
