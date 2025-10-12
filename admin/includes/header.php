<!-- Top Navigation Bar -->
<nav class="bg-white shadow-lg border-b-4 border-blue-600">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <i class="fas fa-gamepad text-3xl text-blue-600 mr-3"></i>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">GameHub Admin</h1>
                    <p class="text-xs text-gray-600">Management Panel</p>
                </div>
            </div>
            
            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- View Site Button -->
                <a href="../index.html" target="_blank" class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    <i class="fas fa-external-link-alt mr-2 text-gray-600"></i>
                    <span class="text-gray-700 font-semibold">View Site</span>
                </a>
                
                <!-- Admin Info -->
                <div class="flex items-center">
                    <div class="bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($admin['username']); ?></p>
                        <p class="text-xs text-gray-600">Administrator</p>
                    </div>
                </div>
                
                <!-- Logout Button -->
                <a href="logout.php" class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>
