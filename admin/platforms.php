<?php
require_once 'config/database.php';
require_once 'config/auth.php';

requireLogin();

$db = Database::getInstance()->getConnection();
$message = '';
$messageType = '';

// Handle Delete
if (isset($_GET['delete'])) {
    try {
        // Get platform info for logo deletion
        $stmt = $db->prepare("SELECT logo FROM platforms WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $platform = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete logo file
        if ($platform && $platform['logo'] && file_exists('../' . $platform['logo'])) {
            unlink('../' . $platform['logo']);
        }
        
        $stmt = $db->prepare("DELETE FROM platforms WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = 'Platform deleted successfully!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error deleting platform: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'];
        $website_link = $_POST['website_link'];
        $description = $_POST['description'] ?? '';
        $display_order = $_POST['display_order'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        
        // Handle logo upload
        $logo_path = $_POST['existing_logo'] ?? '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $uploadDir = '../assets/img/platforms/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $fileName = 'platform_' . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                // Delete old logo if exists
                if ($logo_path && file_exists('../' . $logo_path)) {
                    unlink('../' . $logo_path);
                }
                $logo_path = 'assets/img/platforms/' . $fileName;
            }
        }
        
        if ($id) {
            // Update
            $stmt = $db->prepare("UPDATE platforms SET name = ?, logo = ?, website_link = ?, description = ?, display_order = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$name, $logo_path, $website_link, $description, $display_order, $status, $id]);
            $message = 'Platform updated successfully!';
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO platforms (name, logo, website_link, description, display_order, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $logo_path, $website_link, $description, $display_order, $status]);
            $message = 'Platform added successfully!';
        }
        
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error saving platform: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Fetch all platforms
$stmt = $db->query("SELECT * FROM platforms ORDER BY display_order ASC, name ASC");
$platforms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get edit data if editing
$editPlatform = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM platforms WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editPlatform = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get statistics for sidebar
$stmt = $db->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
$stats['pending_inquiries'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $db->query("SELECT COUNT(*) as count FROM games WHERE is_active = 1");
$stats['total_games'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$admin = getAdminInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platforms Management - GameHub Admin</title>
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
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Platforms Management</h1>
                        <p class="text-gray-600 mt-2">Manage gaming platforms displayed on your website</p>
                    </div>
                    <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Platform
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>">
                <div class="flex items-center">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Platforms Grid -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">All Platforms</h2>
                    <p class="text-gray-600 mt-1">Total: <?php echo count($platforms); ?> platforms</p>
                </div>
                
                <?php if (empty($platforms)): ?>
                <div class="p-12 text-center">
                    <i class="fas fa-server text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-500 mb-2">No Platforms Yet</h3>
                    <p class="text-gray-400 mb-6">Start by adding your first gaming platform</p>
                    <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add First Platform
                    </button>
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platform</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($platforms as $platform): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <?php if ($platform['logo']): ?>
                                            <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" src="../<?php echo htmlspecialchars($platform['logo']); ?>" alt="<?php echo htmlspecialchars($platform['name']); ?>">
                                            <?php else: ?>
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-server text-gray-400"></i>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($platform['name']); ?></div>
                                            <?php if ($platform['description']): ?>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars(substr($platform['description'], 0, 50)) . (strlen($platform['description']) > 50 ? '...' : ''); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?php echo htmlspecialchars($platform['website_link']); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        <?php echo htmlspecialchars(parse_url($platform['website_link'], PHP_URL_HOST) ?? $platform['website_link']); ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $platform['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst($platform['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $platform['display_order']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($platform)); ?>)" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded transition-colors">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button onclick="deletePlatform(<?php echo $platform['id']; ?>, '<?php echo htmlspecialchars($platform['name']); ?>')" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="platformModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Platform</h3>
                </div>
                
                <form id="platformForm" method="POST" enctype="multipart/form-data" class="p-6">
                    <input type="hidden" id="platformId" name="id">
                    <input type="hidden" id="existingLogo" name="existing_logo">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Platform Name *</label>
                            <input type="text" id="platformName" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Website Link *</label>
                            <input type="url" id="platformLink" name="website_link" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="https://example.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                            <input type="file" id="platformLogo" name="logo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Recommended: 200x200px, JPG/PNG format</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="platformDescription" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Brief description of the platform"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                                <input type="number" id="platformOrder" name="display_order" min="0" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="platformStatus" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            <i class="fas fa-save mr-2"></i>Save Platform
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Platform';
            document.getElementById('platformForm').reset();
            document.getElementById('platformId').value = '';
            document.getElementById('existingLogo').value = '';
            document.getElementById('platformModal').classList.remove('hidden');
        }

        function openEditModal(platform) {
            document.getElementById('modalTitle').textContent = 'Edit Platform';
            document.getElementById('platformId').value = platform.id;
            document.getElementById('platformName').value = platform.name;
            document.getElementById('platformLink').value = platform.website_link;
            document.getElementById('platformDescription').value = platform.description || '';
            document.getElementById('platformOrder').value = platform.display_order;
            document.getElementById('platformStatus').value = platform.status;
            document.getElementById('existingLogo').value = platform.logo || '';
            document.getElementById('platformModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('platformModal').classList.add('hidden');
        }

        function deletePlatform(id, name) {
            if (confirm(`Are you sure you want to delete the platform "${name}"? This action cannot be undone.`)) {
                window.location.href = `platforms.php?delete=${id}`;
            }
        }

        // Close modal when clicking outside
        document.getElementById('platformModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>