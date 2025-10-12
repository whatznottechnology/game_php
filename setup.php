<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Check - GameHub Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-rocket text-blue-600 mr-3"></i>GameHub Setup Check
            </h1>
            <p class="text-gray-600">Verify your server environment before getting started</p>
        </div>

        <?php
        $checks = [];
        $allPassed = true;

        // PHP Version Check
        $phpVersion = phpversion();
        $phpCheck = version_compare($phpVersion, '7.4.0', '>=');
        $checks[] = [
            'name' => 'PHP Version',
            'required' => '7.4 or higher',
            'current' => $phpVersion,
            'passed' => $phpCheck,
            'icon' => 'fa-code'
        ];
        $allPassed = $allPassed && $phpCheck;

        // SQLite Extension Check
        $sqliteCheck = extension_loaded('sqlite3') || extension_loaded('pdo_sqlite');
        $checks[] = [
            'name' => 'SQLite Extension',
            'required' => 'Enabled',
            'current' => $sqliteCheck ? 'Enabled' : 'Not Found',
            'passed' => $sqliteCheck,
            'icon' => 'fa-database'
        ];
        $allPassed = $allPassed && $sqliteCheck;

        // PDO Extension Check
        $pdoCheck = extension_loaded('pdo');
        $checks[] = [
            'name' => 'PDO Extension',
            'required' => 'Enabled',
            'current' => $pdoCheck ? 'Enabled' : 'Not Found',
            'passed' => $pdoCheck,
            'icon' => 'fa-plug'
        ];
        $allPassed = $allPassed && $pdoCheck;

        // Config Directory Writable
        $configPath = __DIR__ . '/admin/config';
        $configWritable = is_writable($configPath);
        $checks[] = [
            'name' => 'Config Directory Writable',
            'required' => 'Yes',
            'current' => $configWritable ? 'Yes' : 'No',
            'passed' => $configWritable,
            'icon' => 'fa-folder-open'
        ];
        $allPassed = $allPassed && $configWritable;

        // Categories Upload Directory
        $categoriesPath = __DIR__ . '/assets/img/categories';
        $categoriesWritable = is_writable($categoriesPath);
        $checks[] = [
            'name' => 'Categories Upload Directory',
            'required' => 'Writable',
            'current' => $categoriesWritable ? 'Writable' : 'Not Writable',
            'passed' => $categoriesWritable,
            'icon' => 'fa-images'
        ];
        $allPassed = $allPassed && $categoriesWritable;

        // Games Upload Directory
        $gamesPath = __DIR__ . '/assets/img/games';
        $gamesWritable = is_writable($gamesPath);
        $checks[] = [
            'name' => 'Games Upload Directory',
            'required' => 'Writable',
            'current' => $gamesWritable ? 'Writable' : 'Not Writable',
            'passed' => $gamesWritable,
            'icon' => 'fa-gamepad'
        ];
        $allPassed = $allPassed && $gamesWritable;

        // Upload Max Filesize
        $uploadMax = ini_get('upload_max_filesize');
        $uploadSizeOk = (int)$uploadMax >= 2;
        $checks[] = [
            'name' => 'Upload Max Filesize',
            'required' => '2M or higher',
            'current' => $uploadMax,
            'passed' => $uploadSizeOk,
            'icon' => 'fa-upload'
        ];

        // Database Check
        $dbPath = __DIR__ . '/admin/config/database.sqlite';
        $dbExists = file_exists($dbPath);
        $checks[] = [
            'name' => 'Database Status',
            'required' => 'Will be created on first access',
            'current' => $dbExists ? 'Exists' : 'Not Yet Created',
            'passed' => true,
            'icon' => 'fa-server'
        ];
        ?>

        <!-- Results -->
        <div class="space-y-3 mb-8">
            <?php foreach ($checks as $check): ?>
                <div class="flex items-center justify-between p-4 rounded-lg <?php echo $check['passed'] ? 'bg-green-50 border-2 border-green-200' : 'bg-red-50 border-2 border-red-200'; ?>">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full <?php echo $check['passed'] ? 'bg-green-500' : 'bg-red-500'; ?> flex items-center justify-center text-white">
                            <i class="fas <?php echo $check['icon']; ?> text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800"><?php echo $check['name']; ?></h3>
                            <p class="text-sm text-gray-600">Required: <span class="font-semibold"><?php echo $check['required']; ?></span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold <?php echo $check['passed'] ? 'text-green-700' : 'text-red-700'; ?>">
                                <?php echo $check['current']; ?>
                            </span>
                            <i class="fas <?php echo $check['passed'] ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600'; ?> text-2xl"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Summary -->
        <div class="p-6 rounded-xl <?php echo $allPassed ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 'bg-gradient-to-r from-red-500 to-orange-500'; ?> text-white text-center">
            <?php if ($allPassed): ?>
                <i class="fas fa-check-circle text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">All Checks Passed!</h2>
                <p class="mb-6">Your server is ready to run GameHub Admin Panel</p>
                <a href="admin/" class="inline-block px-8 py-4 bg-white text-green-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Go to Admin Panel
                </a>
                <div class="mt-4 p-4 bg-white bg-opacity-20 rounded-lg">
                    <p class="text-sm font-semibold">Default Login Credentials</p>
                    <p class="text-sm mt-1">Username: <code class="bg-white bg-opacity-30 px-2 py-1 rounded">admin</code></p>
                    <p class="text-sm">Password: <code class="bg-white bg-opacity-30 px-2 py-1 rounded">admin123</code></p>
                    <p class="text-xs mt-2 text-green-100"><i class="fas fa-exclamation-triangle mr-1"></i>Change password immediately after login!</p>
                </div>
            <?php else: ?>
                <i class="fas fa-exclamation-triangle text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">Some Checks Failed</h2>
                <p class="mb-6">Please fix the issues above before proceeding</p>
                <button onclick="location.reload()" class="px-8 py-4 bg-white text-red-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all shadow-lg">
                    <i class="fas fa-redo mr-2"></i>Re-check
                </button>
            <?php endif; ?>
        </div>

        <!-- Server Info -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <i class="fas fa-server text-2xl text-blue-600 mb-2"></i>
                <p class="text-xs text-gray-600">Server Software</p>
                <p class="font-semibold text-gray-800 text-sm"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <i class="fas fa-code text-2xl text-purple-600 mb-2"></i>
                <p class="text-xs text-gray-600">PHP Version</p>
                <p class="font-semibold text-gray-800 text-sm"><?php echo $phpVersion; ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <i class="fas fa-memory text-2xl text-green-600 mb-2"></i>
                <p class="text-xs text-gray-600">Memory Limit</p>
                <p class="font-semibold text-gray-800 text-sm"><?php echo ini_get('memory_limit'); ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <i class="fas fa-clock text-2xl text-orange-600 mb-2"></i>
                <p class="text-xs text-gray-600">Max Execution</p>
                <p class="font-semibold text-gray-800 text-sm"><?php echo ini_get('max_execution_time'); ?>s</p>
            </div>
        </div>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p><i class="fas fa-info-circle mr-1"></i>For detailed setup instructions, see <code class="bg-gray-100 px-2 py-1 rounded">README.md</code></p>
        </div>
    </div>
</body>
</html>
