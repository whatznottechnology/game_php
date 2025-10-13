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
        $stmt = $db->prepare("DELETE FROM games WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = 'Game deleted successfully!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error deleting game: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        $category_id = $_POST['category_id'];
        $name = $_POST['name'];
        $slug = strtolower(str_replace(' ', '-', $name));
        $description = $_POST['description'];
        $platforms = $_POST['platforms'];
        $bonus_amount = $_POST['bonus_amount'] ?? '';
        $min_deposit = $_POST['min_deposit'] ?? '';
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $display_order = $_POST['display_order'] ?? 0;
        
        // Handle banner upload
        $banner_image = $_POST['existing_banner'] ?? '';
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === 0) {
            $uploadDir = '../assets/img/games/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
            $fileName = $slug . '-' . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $uploadPath)) {
                $banner_image = 'assets/img/games/' . $fileName;
            }
        }
        
        if ($id) {
            // Update
            $stmt = $db->prepare("UPDATE games SET category_id = ?, name = ?, slug = ?, banner_image = ?, description = ?, platforms = ?, bonus_amount = ?, min_deposit = ?, is_featured = ?, is_active = ?, display_order = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$category_id, $name, $slug, $banner_image, $description, $platforms, $bonus_amount, $min_deposit, $is_featured, $is_active, $display_order, $id]);
            $message = 'Game updated successfully!';
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO games (category_id, name, slug, banner_image, description, platforms, bonus_amount, min_deposit, is_featured, is_active, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $name, $slug, $banner_image, $description, $platforms, $bonus_amount, $min_deposit, $is_featured, $is_active, $display_order]);
            $message = 'Game added successfully!';
        }
        
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error saving game: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Fetch all games with category names
$stmt = $db->query("SELECT g.*, c.name as category_name FROM games g LEFT JOIN categories c ON g.category_id = c.id ORDER BY g.display_order ASC, g.name ASC");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all active categories for dropdown
$stmt = $db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get edit data if editing
$editGame = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editGame = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <title>Games - GameHub Admin</title>
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
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Games Management</h1>
                    <p class="text-gray-600 mt-2">Add and manage betting games with banners and details</p>
                </div>
                <?php if (!$editGame): ?>
                    <button onclick="document.getElementById('gameForm').scrollIntoView({behavior: 'smooth'})" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all">
                        <i class="fas fa-plus mr-2"></i>Add New Game
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Success/Error Message -->
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Games List -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-gamepad text-purple-600 mr-2"></i>
                    All Games (<?php echo count($games); ?>)
                </h3>
                
                <?php if (empty($games)): ?>
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-inbox text-6xl mb-4"></i>
                        <p class="text-xl">No games yet</p>
                        <p class="text-sm mt-2">Add your first game to get started</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($games as $game): ?>
                            <div class="border-2 border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                                <!-- Banner Image -->
                                <div class="relative h-48 bg-gray-200">
                                    <?php if ($game['banner_image']): ?>
                                        <img src="../<?php echo htmlspecialchars($game['banner_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($game['name']); ?>"
                                             class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-6xl text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Featured Badge -->
                                    <?php if ($game['is_featured']): ?>
                                        <div class="absolute top-2 right-2 px-3 py-1 bg-yellow-400 text-gray-900 rounded-full text-xs font-bold">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-2 left-2">
                                        <?php if ($game['is_active']): ?>
                                            <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">Active</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-gray-500 text-white rounded-full text-xs font-semibold">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Game Info -->
                                <div class="p-4">
                                    <h4 class="font-bold text-lg text-gray-800 mb-2"><?php echo htmlspecialchars($game['name']); ?></h4>
                                    
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-folder w-5 text-purple-600"></i>
                                            <span><?php echo htmlspecialchars($game['category_name'] ?? 'No Category'); ?></span>
                                        </div>
                                        
                                        <?php if ($game['platforms']): ?>
                                            <div class="flex items-center text-gray-600">
                                                <i class="fas fa-globe w-5 text-blue-600"></i>
                                                <span class="truncate"><?php echo htmlspecialchars($game['platforms']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($game['bonus_amount']): ?>
                                            <div class="flex items-center text-gray-600">
                                                <i class="fas fa-gift w-5 text-green-600"></i>
                                                <span>Bonus: <?php echo htmlspecialchars($game['bonus_amount']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($game['min_deposit']): ?>
                                            <div class="flex items-center text-gray-600">
                                                <i class="fas fa-wallet w-5 text-orange-600"></i>
                                                <span>Min: <?php echo htmlspecialchars($game['min_deposit']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                                        <a href="?edit=<?php echo $game['id']; ?>" 
                                           class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg text-center font-semibold hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <a href="?delete=<?php echo $game['id']; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this game?')"
                                           class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Add/Edit Form -->
            <div id="gameForm" class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-<?php echo $editGame ? 'edit' : 'plus-circle'; ?> text-blue-600 mr-2"></i>
                    <?php echo $editGame ? 'Edit Game' : 'Add New Game'; ?>
                </h3>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php if ($editGame): ?>
                        <input type="hidden" name="id" value="<?php echo $editGame['id']; ?>">
                        <input type="hidden" name="existing_banner" value="<?php echo htmlspecialchars($editGame['banner_image']); ?>">
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Game Name *</label>
                                <input type="text" name="name" required
                                       value="<?php echo htmlspecialchars($editGame['name'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="e.g., Teen Patti">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Category *</label>
                                <select name="category_id" required
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" 
                                                <?php echo ($editGame && $editGame['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Banner Image *</label>
                                <input type="file" name="banner" accept="image/*"
                                       <?php echo $editGame ? '' : 'required'; ?>
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                                <?php if ($editGame && $editGame['banner_image']): ?>
                                    <div class="mt-2">
                                        <img src="../<?php echo htmlspecialchars($editGame['banner_image']); ?>" 
                                             alt="Current banner" class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                                        <p class="text-xs text-gray-500 mt-1">Current banner</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Available Platforms</label>
                                <input type="text" name="platforms"
                                       value="<?php echo htmlspecialchars($editGame['platforms'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="e.g., Reddy999, Tenexch, KingExch9">
                                <p class="text-xs text-gray-500 mt-1">Comma-separated platform names</p>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Bonus Amount</label>
                                <input type="text" name="bonus_amount"
                                       value="<?php echo htmlspecialchars($editGame['bonus_amount'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="e.g., ₹5000 Welcome Bonus">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Minimum Deposit</label>
                                <input type="text" name="min_deposit"
                                       value="<?php echo htmlspecialchars($editGame['min_deposit'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="e.g., ₹500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Display Order</label>
                                <input type="number" name="display_order" min="0"
                                       value="<?php echo htmlspecialchars($editGame['display_order'] ?? '0'); ?>"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                                       placeholder="0">
                                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                            </div>
                            
                            <div class="flex gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_featured" id="is_featured" 
                                           <?php echo ($editGame && $editGame['is_featured']) ? 'checked' : ''; ?>
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_featured" class="ml-2 text-gray-700 font-semibold">Featured</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" 
                                           <?php echo (!$editGame || $editGame['is_active']) ? 'checked' : ''; ?>
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 text-gray-700 font-semibold">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description (Full Width) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Description (HTML Supported)</label>
                        
                        <!-- Formatting Toolbar -->
                        <div class="mb-2 flex flex-wrap gap-2 p-3 bg-gray-50 border border-gray-300 rounded-t-lg">
                            <button type="button" onclick="wrapText(document.getElementById('description'), '<p><strong>', '</strong></p>')" 
                                    class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100 text-sm font-bold">
                                <i class="fas fa-bold"></i> Bold
                            </button>
                            <button type="button" onclick="wrapText(document.getElementById('description'), '<p><em>', '</em></p>')" 
                                    class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100 text-sm italic">
                                <i class="fas fa-italic"></i> Italic
                            </button>
                            <button type="button" onclick="wrapText(document.getElementById('description'), '<ul><li>', '</li></ul>')" 
                                    class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100 text-sm">
                                <i class="fas fa-list-ul"></i> List
                            </button>
                            <button type="button" onclick="insertText(document.getElementById('description'), '<li></li>')" 
                                    class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100 text-sm">
                                <i class="fas fa-plus"></i> List Item
                            </button>
                            <button type="button" onclick="wrapText(document.getElementById('description'), '<p>', '</p>')" 
                                    class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-100 text-sm">
                                <i class="fas fa-paragraph"></i> Paragraph
                            </button>
                        </div>
                        
                        <textarea name="description" id="description" rows="10"
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-b-lg focus:border-blue-500 focus:outline-none"
                                  placeholder="Enter game description with HTML formatting... Use the buttons above to add formatting."><?php echo htmlspecialchars($editGame['description'] ?? ''); ?></textarea>
                        
                        <!-- HTML Help -->
                        <div class="mt-2 text-sm text-gray-600">
                            <p><strong>HTML Tips:</strong> Use &lt;p&gt;...&lt;/p&gt; for paragraphs, &lt;strong&gt;...&lt;/strong&gt; for bold text, &lt;ul&gt;&lt;li&gt;...&lt;/li&gt;&lt;/ul&gt; for bullet lists</p>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="flex gap-3 pt-4">
                        <?php if ($editGame): ?>
                            <a href="games.php" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all">
                            <i class="fas fa-<?php echo $editGame ? 'save' : 'plus'; ?> mr-2"></i>
                            <?php echo $editGame ? 'Update Game' : 'Add Game'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Simple formatting helper functions for textarea
        function insertText(textarea, text) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const value = textarea.value;
            
            textarea.value = value.slice(0, start) + text + value.slice(end);
            textarea.selectionStart = textarea.selectionEnd = start + text.length;
            textarea.focus();
        }
        
        function wrapText(textarea, before, after) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.slice(start, end);
            const replacement = before + selectedText + after;
            
            textarea.value = textarea.value.slice(0, start) + replacement + textarea.value.slice(end);
            textarea.selectionStart = start + before.length;
            textarea.selectionEnd = start + before.length + selectedText.length;
            textarea.focus();
        }
    </script>
</body>
</html>
