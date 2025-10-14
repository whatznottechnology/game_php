<?php
require_once 'config/database.php';
require_once 'config/auth.php';

requireLogin();

$db = Database::getInstance()->getConnection();
$message = '';
$messageType = '';

$admin = getAdminInfo();

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['update_profile'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            
            $stmt = $db->prepare("UPDATE admin_users SET username = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$username, $email, $admin['id']]);
            
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_email'] = $email;
            
            $message = 'Profile updated successfully!';
            $messageType = 'success';
            $admin = getAdminInfo();
        }
        
        if (isset($_POST['change_password'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Verify current password
            if (!password_verify($current_password, $admin['password'])) {
                throw new Exception('Current password is incorrect');
            }
            
            if ($new_password !== $confirm_password) {
                throw new Exception('New passwords do not match');
            }
            
            if (strlen($new_password) < 6) {
                throw new Exception('Password must be at least 6 characters');
            }
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE admin_users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$hashed_password, $admin['id']]);
            
            $message = 'Password changed successfully!';
            $messageType = 'success';
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Get statistics for sidebar
$stmt = $db->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
$stats['pending_inquiries'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $db->query("SELECT COUNT(*) as count FROM games WHERE is_active = 1");
$stats['total_games'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - GameHub Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>
    
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">My Account</h1>
                <p class="text-gray-600 mt-2">Manage your profile and account settings</p>
            </div>
            
            <!-- Success/Error Message -->
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 text-white text-center">
                    <div class="mb-6">
                        <div class="w-32 h-32 bg-white rounded-full mx-auto flex items-center justify-center text-6xl text-blue-600 font-bold">
                            <?php echo strtoupper(substr($admin['username'], 0, 1)); ?>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($admin['username']); ?></h3>
                    <p class="text-blue-100 mb-1">
                        <i class="fas fa-envelope mr-2"></i><?php echo htmlspecialchars($admin['email']); ?>
                    </p>
                    <p class="text-blue-100 text-sm mt-4">
                        <i class="fas fa-user-shield mr-2"></i>Administrator
                    </p>
                    <p class="text-blue-100 text-sm mt-2">
                        <i class="fas fa-calendar mr-2"></i>Member since <?php echo date('M Y', strtotime($admin['created_at'])); ?>
                    </p>
                </div>
                
                <!-- Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Update Profile -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                            Update Profile
                        </h3>
                        
                        <form method="POST" class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Username *</label>
                                <input type="text" name="username" required
                                       value="<?php echo htmlspecialchars($admin['username']); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                                <input type="email" name="email" required
                                       value="<?php echo htmlspecialchars($admin['email']); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                            </div>
                            
                            <button type="submit" name="update_profile" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </form>
                    </div>
                    
                    <!-- Change Password -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-lock text-purple-600 mr-2"></i>
                            Change Password
                        </h3>
                        
                        <form method="POST" class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Current Password *</label>
                                <input type="password" name="current_password" required
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="Enter current password">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">New Password *</label>
                                <input type="password" name="new_password" required
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="Enter new password (min 6 characters)">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Confirm New Password *</label>
                                <input type="password" name="confirm_password" required
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="Confirm new password">
                            </div>
                            
                            <button type="submit" name="change_password" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all">
                                <i class="fas fa-key mr-2"></i>Change Password
                            </button>
                        </form>
                    </div>
                    
                    <!-- Account Info -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            Account Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">Account ID</p>
                                    <p class="font-semibold text-gray-800">#<?php echo $admin['id']; ?></p>
                                </div>
                                <i class="fas fa-id-card text-2xl text-gray-400"></i>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">Account Created</p>
                                    <p class="font-semibold text-gray-800">
                                        <?php 
                                        if (!empty($admin['created_at'])) {
                                            echo date('d M Y, h:i A', strtotime($admin['created_at'])); 
                                        } else {
                                            echo 'Not available';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <i class="fas fa-calendar-plus text-2xl text-gray-400"></i>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">Last Updated</p>
                                    <p class="font-semibold text-gray-800">
                                        <?php 
                                        if (!empty($admin['updated_at'])) {
                                            echo date('d M Y, h:i A', strtotime($admin['updated_at'])); 
                                        } else {
                                            echo 'Not available';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <i class="fas fa-clock text-2xl text-gray-400"></i>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border-2 border-green-200">
                                <div>
                                    <p class="text-sm text-green-600">Account Status</p>
                                    <p class="font-semibold text-green-700">
                                        <i class="fas fa-check-circle mr-1"></i>Active
                                    </p>
                                </div>
                                <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
