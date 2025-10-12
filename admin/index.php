<?php
require_once 'config/database.php';
require_once 'config/auth.php';

requireLogin();

$db = Database::getInstance()->getConnection();

// Get statistics
$stats = [];

// Total Visitors (unique IPs today)
$stmt = $db->query("SELECT COUNT(DISTINCT ip_address) as count FROM visitor_logs WHERE visit_date = DATE('now')");
$stats['visitors_today'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total Visitors (all time unique IPs)
$stmt = $db->query("SELECT COUNT(DISTINCT ip_address) as count FROM visitor_logs");
$stats['total_visitors'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total Inquiries
$stmt = $db->query("SELECT COUNT(*) as count FROM inquiries");
$stats['total_inquiries'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// New Inquiries (today)
$stmt = $db->query("SELECT COUNT(*) as count FROM inquiries WHERE DATE(created_at) = DATE('now')");
$stats['inquiries_today'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Pending Inquiries
$stmt = $db->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
$stats['pending_inquiries'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total Categories
$stmt = $db->query("SELECT COUNT(*) as count FROM categories WHERE is_active = 1");
$stats['total_categories'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total Games
$stmt = $db->query("SELECT COUNT(*) as count FROM games WHERE is_active = 1");
$stats['total_games'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Recent Inquiries (last 10)
$stmt = $db->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 10");
$recent_inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Platform distribution
$stmt = $db->query("SELECT platform, COUNT(*) as count FROM inquiries WHERE platform IS NOT NULL AND platform != '' GROUP BY platform ORDER BY count DESC LIMIT 5");
$platform_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Interest distribution
$stmt = $db->query("SELECT interest, COUNT(*) as count FROM inquiries WHERE interest IS NOT NULL AND interest != '' GROUP BY interest ORDER BY count DESC LIMIT 5");
$interest_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$admin = getAdminInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GameHub</title>
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
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-600 mt-2">Welcome back, <?php echo htmlspecialchars($admin['username']); ?>! Here's your overview.</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Visitors -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Total Visitors</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($stats['total_visitors']); ?></h3>
                            <p class="text-sm text-blue-600 mt-2">
                                <i class="fas fa-arrow-up mr-1"></i><?php echo $stats['visitors_today']; ?> today
                            </p>
                        </div>
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-users text-3xl text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Inquiries -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Total Inquiries</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($stats['total_inquiries']); ?></h3>
                            <p class="text-sm text-green-600 mt-2">
                                <i class="fas fa-plus mr-1"></i><?php echo $stats['inquiries_today']; ?> today
                            </p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-envelope text-3xl text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Inquiries -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Pending</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($stats['pending_inquiries']); ?></h3>
                            <p class="text-sm text-orange-600 mt-2">
                                <i class="fas fa-clock mr-1"></i>Needs attention
                            </p>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <i class="fas fa-bell text-3xl text-orange-600"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Games -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Active Games</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($stats['total_games']); ?></h3>
                            <p class="text-sm text-purple-600 mt-2">
                                <i class="fas fa-gamepad mr-1"></i><?php echo $stats['total_categories']; ?> categories
                            </p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-full">
                            <i class="fas fa-gamepad text-3xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Platform Distribution -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-pie text-blue-600 mr-2"></i>Popular Platforms
                    </h3>
                    <div class="space-y-3">
                        <?php foreach ($platform_stats as $platform): ?>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-semibold text-gray-700"><?php echo htmlspecialchars($platform['platform']); ?></span>
                                    <span class="text-sm text-gray-600"><?php echo $platform['count']; ?> inquiries</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo min(100, ($platform['count'] / $stats['total_inquiries']) * 100); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Interest Distribution -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>Popular Interests
                    </h3>
                    <div class="space-y-3">
                        <?php foreach ($interest_stats as $interest): ?>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-semibold text-gray-700"><?php echo htmlspecialchars($interest['interest']); ?></span>
                                    <span class="text-sm text-gray-600"><?php echo $interest['count']; ?> inquiries</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo min(100, ($interest['count'] / $stats['total_inquiries']) * 100); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Inquiries -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-inbox text-purple-600 mr-2"></i>Recent Inquiries
                    </h3>
                    <a href="inquiries.php" class="text-blue-600 hover:text-blue-700 font-semibold">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Contact</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Platform</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Interest</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_inquiries as $inquiry): ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($inquiry['name']); ?></div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm text-gray-600">
                                            <i class="fab fa-whatsapp text-green-600 mr-1"></i><?php echo htmlspecialchars($inquiry['mobile']); ?>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-sm text-gray-700"><?php echo htmlspecialchars($inquiry['platform'] ?: 'N/A'); ?></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-sm text-gray-700"><?php echo htmlspecialchars($inquiry['interest'] ?: 'N/A'); ?></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php if ($inquiry['status'] === 'new'): ?>
                                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">New</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Contacted</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        <?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
