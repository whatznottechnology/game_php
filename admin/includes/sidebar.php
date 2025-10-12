<!-- Sidebar Navigation -->
<div class="w-64 bg-gray-900 min-h-screen text-white">
    <div class="p-6">
        <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="index.php" class="flex items-center px-4 py-3 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-blue-600' : 'hover:bg-gray-800'; ?> transition-colors">
                <i class="fas fa-tachometer-alt w-6"></i>
                <span class="ml-3 font-semibold">Dashboard</span>
            </a>
            
            <!-- Site Settings -->
            <a href="settings.php" class="flex items-center px-4 py-3 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'bg-blue-600' : 'hover:bg-gray-800'; ?> transition-colors">
                <i class="fas fa-cog w-6"></i>
                <span class="ml-3 font-semibold">Site Settings</span>
            </a>
            
            <!-- Categories -->
            <a href="categories.php" class="flex items-center px-4 py-3 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'bg-blue-600' : 'hover:bg-gray-800'; ?> transition-colors">
                <i class="fas fa-th-large w-6"></i>
                <span class="ml-3 font-semibold">Categories</span>
            </a>
            
            <!-- Games -->
            <a href="games.php" class="flex items-center px-4 py-3 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) === 'games.php' ? 'bg-blue-600' : 'hover:bg-gray-800'; ?> transition-colors">
                <i class="fas fa-gamepad w-6"></i>
                <span class="ml-3 font-semibold">Games</span>
                <?php
                if (isset($stats) && $stats['total_games'] > 0) {
                    echo '<span class="ml-auto bg-purple-600 px-2 py-1 rounded-full text-xs">' . $stats['total_games'] . '</span>';
                }
                ?>
            </a>
            
            <!-- Inquiries -->
            <a href="inquiries.php" class="flex items-center px-4 py-3 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) === 'inquiries.php' ? 'bg-blue-600' : 'hover:bg-gray-800'; ?> transition-colors">
                <i class="fas fa-inbox w-6"></i>
                <span class="ml-3 font-semibold">Inquiries</span>
                <?php
                if (isset($stats) && $stats['pending_inquiries'] > 0) {
                    echo '<span class="ml-auto bg-orange-500 px-2 py-1 rounded-full text-xs animate-pulse">' . $stats['pending_inquiries'] . '</span>';
                }
                ?>
            </a>
            
            <!-- Divider -->
            <div class="border-t border-gray-700 my-4"></div>
            
            <!-- Account Settings -->
            <a href="profile.php" class="flex items-center px-4 py-3 rounded-lg <?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'bg-blue-600' : 'hover:bg-gray-800'; ?> transition-colors">
                <i class="fas fa-user-circle w-6"></i>
                <span class="ml-3 font-semibold">My Account</span>
            </a>
        </nav>
    </div>
    
    <!-- Bottom Info -->
    <div class="absolute bottom-0 w-64 p-6 bg-gray-800 border-t border-gray-700">
        <div class="text-xs text-gray-400">
            <p><i class="fas fa-info-circle mr-2"></i>Version 1.0.0</p>
            <p class="mt-2"><i class="fas fa-copyright mr-2"></i>2025 GameHub</p>
        </div>
    </div>
</div>
