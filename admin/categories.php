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
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = 'Category deleted successfully!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error deleting category: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'];
        $slug = strtolower(str_replace(' ', '-', $name));
        $display_order = $_POST['display_order'] ?? 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle icon upload
        $icon_path = $_POST['existing_icon'] ?? '';
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === 0) {
            $uploadDir = '../assets/img/categories/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
            $fileName = $slug . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['icon']['tmp_name'], $uploadPath)) {
                $icon_path = 'assets/img/categories/' . $fileName;
            }
        }
        
        if ($id) {
            // Update
            $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ?, icon_path = ?, display_order = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$name, $slug, $icon_path, $display_order, $is_active, $id]);
            $message = 'Category updated successfully!';
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO categories (name, slug, icon_path, display_order, is_active) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $icon_path, $display_order, $is_active]);
            $message = 'Category added successfully!';
        }
        
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error saving category: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Fetch all categories
$stmt = $db->query("SELECT * FROM categories ORDER BY display_order ASC, name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get edit data if editing
$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCategory = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>Categories - GameHub Admin</title>
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
                <h1 class="text-3xl font-bold text-gray-800">Categories Management</h1>
                <p class="text-gray-600 mt-2">Add and manage betting categories with icons</p>
            </div>
            
            <!-- Success/Error Message -->
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Add/Edit Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-<?php echo $editCategory ? 'edit' : 'plus-circle'; ?> text-blue-600 mr-2"></i>
                            <?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?>
                        </h3>
                        
                        <form method="POST" enctype="multipart/form-data" class="space-y-4">
                            <?php if ($editCategory): ?>
                                <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
                                <input type="hidden" name="existing_icon" value="<?php echo htmlspecialchars($editCategory['icon_path']); ?>">
                            <?php endif; ?>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Category Name *</label>
                                <input type="text" name="name" required
                                       value="<?php echo htmlspecialchars($editCategory['name'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="e.g., Sports Betting">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Icon/Image (1:1 ratio recommended)</label>
                                <input type="file" name="icon" accept="image/*"
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                                <?php if ($editCategory && $editCategory['icon_path']): ?>
                                    <div class="mt-2">
                                        <img src="../<?php echo htmlspecialchars($editCategory['icon_path']); ?>" 
                                             alt="Current icon" class="w-16 h-16 object-cover rounded-lg border-2 border-gray-300">
                                        <p class="text-xs text-gray-500 mt-1">Current icon</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Display Order</label>
                                <input type="number" name="display_order" min="0"
                                       value="<?php echo htmlspecialchars($editCategory['display_order'] ?? '0'); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="0">
                                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" 
                                       <?php echo (!$editCategory || $editCategory['is_active']) ? 'checked' : ''; ?>
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="ml-2 text-gray-700 font-semibold">Active</label>
                            </div>
                            
                            <div class="flex gap-3 pt-4">
                                <?php if ($editCategory): ?>
                                    <a href="categories.php" class="flex-1 px-4 py-3 bg-gray-300 text-gray-700 rounded-lg text-center font-semibold hover:bg-gray-400 transition-colors">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                <?php endif; ?>
                                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all">
                                    <i class="fas fa-<?php echo $editCategory ? 'save' : 'plus'; ?> mr-2"></i>
                                    <?php echo $editCategory ? 'Update' : 'Add'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Categories List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">
                            <i class="fas fa-list text-purple-600 mr-2"></i>
                            All Categories (<?php echo count($categories); ?>)
                        </h3>
                        
                        <?php if (empty($categories)): ?>
                            <div class="text-center py-12 text-gray-500">
                                <i class="fas fa-inbox text-6xl mb-4"></i>
                                <p class="text-xl">No categories yet</p>
                                <p class="text-sm mt-2">Add your first category to get started</p>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b-2 border-gray-200">
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Icon</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Slug</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Order</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="py-3 px-4">
                                                    <?php if ($category['icon_path']): ?>
                                                        <img src="../<?php echo htmlspecialchars($category['icon_path']); ?>" 
                                                             alt="<?php echo htmlspecialchars($category['name']); ?>"
                                                             class="w-12 h-12 object-cover rounded-lg border-2 border-gray-200">
                                                    <?php else: ?>
                                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($category['name']); ?></div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <code class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded"><?php echo htmlspecialchars($category['slug']); ?></code>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="text-gray-700"><?php echo $category['display_order']; ?></span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <?php if ($category['is_active']): ?>
                                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                                    <?php else: ?>
                                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3 px-4 text-right">
                                                    <a href="?edit=<?php echo $category['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $category['id']; ?>" 
                                                       onclick="return confirm('Are you sure you want to delete this category?')"
                                                       class="text-red-600 hover:text-red-800">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
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
        </div>
    </div>
</body>
</html>
