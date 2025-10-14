<?php
require_once 'config/database.php';
require_once 'config/auth.php';

requireLogin();

$db = Database::getInstance()->getConnection();
$message = '';
$error = '';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['add_banner'])) {
        // Add new banner
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $description = trim($_POST['description']);
        $button_text = trim($_POST['button_text']);
        $button_link = trim($_POST['button_link']);
        $display_order = intval($_POST['display_order']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $background_image = '';
        
        // Handle file upload
        if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/img/banners/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Check file size (max 2MB)
            if ($_FILES['background_image']['size'] > 2 * 1024 * 1024) {
                $error = 'Image size must be less than 2MB.';
            } else {
                // Check image dimensions
                $imageInfo = getimagesize($_FILES['background_image']['tmp_name']);
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                    
                    // Recommended dimensions: 1920x600 pixels (3.2:1 aspect ratio)
                    if ($width < 1200 || $height < 400) {
                        $error = 'Image dimensions should be at least 1200x400 pixels. Recommended: 1920x600 pixels for best quality.';
                    } else {
                        $fileName = time() . '_' . basename($_FILES['background_image']['name']);
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['background_image']['tmp_name'], $uploadPath)) {
                            $background_image = 'assets/img/banners/' . $fileName;
                        } else {
                            $error = 'Failed to upload image.';
                        }
                    }
                } else {
                    $error = 'Invalid image file.';
                }
            }
        } else {
            $error = 'Banner image is required.';
        }
        
        if (!$error) {
            $stmt = $db->prepare("INSERT INTO banners (title, subtitle, description, button_text, button_link, background_image, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$title, $subtitle, $description, $button_text, $button_link, $background_image, $display_order, $is_active])) {
                $message = 'Banner added successfully!';
            } else {
                $error = 'Failed to add banner.';
            }
        }
    } elseif (isset($_POST['edit_banner'])) {
        // Edit existing banner
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $description = trim($_POST['description']);
        $button_text = trim($_POST['button_text']);
        $button_link = trim($_POST['button_link']);
        $display_order = intval($_POST['display_order']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Get current banner data
        $stmt = $db->prepare("SELECT background_image FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        $current_banner = $stmt->fetch(PDO::FETCH_ASSOC);
        $background_image = $current_banner['background_image'];
        
        // Handle file upload
        if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/img/banners/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Check file size (max 2MB)
            if ($_FILES['background_image']['size'] > 2 * 1024 * 1024) {
                $error = 'Image size must be less than 2MB.';
            } else {
                // Check image dimensions
                $imageInfo = getimagesize($_FILES['background_image']['tmp_name']);
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                    
                    // Recommended dimensions: 1920x600 pixels (3.2:1 aspect ratio)
                    if ($width < 1200 || $height < 400) {
                        $error = 'Image dimensions should be at least 1200x400 pixels. Recommended: 1920x600 pixels for best quality.';
                    } else {
                        $fileName = time() . '_' . basename($_FILES['background_image']['name']);
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['background_image']['tmp_name'], $uploadPath)) {
                            // Delete old image if it exists
                            if ($background_image && file_exists('../' . $background_image)) {
                                unlink('../' . $background_image);
                            }
                            $background_image = 'assets/img/banners/' . $fileName;
                        } else {
                            $error = 'Failed to upload image.';
                        }
                    }
                } else {
                    $error = 'Invalid image file.';
                }
            }
        }
        
        if (!$error) {
            $stmt = $db->prepare("UPDATE banners SET title = ?, subtitle = ?, description = ?, button_text = ?, button_link = ?, background_image = ?, display_order = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            if ($stmt->execute([$title, $subtitle, $description, $button_text, $button_link, $background_image, $display_order, $is_active, $id])) {
                $message = 'Banner updated successfully!';
            } else {
                $error = 'Failed to update banner.';
            }
        }
    } elseif (isset($_POST['delete_banner'])) {
        // Delete banner
        $id = intval($_POST['id']);
        
        // Get banner image to delete
        $stmt = $db->prepare("SELECT background_image FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $db->prepare("DELETE FROM banners WHERE id = ?");
        if ($stmt->execute([$id])) {
            // Delete image file if it exists
            if ($banner['background_image'] && file_exists('../' . $banner['background_image'])) {
                unlink('../' . $banner['background_image']);
            }
            $message = 'Banner deleted successfully!';
        } else {
            $error = 'Failed to delete banner.';
        }
    } elseif (isset($_POST['toggle_status'])) {
        // Toggle banner status
        $id = intval($_POST['id']);
        $stmt = $db->prepare("UPDATE banners SET is_active = 1 - is_active WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Banner status updated!';
        } else {
            $error = 'Failed to update banner status.';
        }
    }
}

// Get all banners
$stmt = $db->query("SELECT * FROM banners ORDER BY display_order ASC, created_at DESC");
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get banner for editing if ID is provided
$edit_banner = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM banners WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_banner = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner Management - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .banner-preview {
            min-height: 300px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
        }
        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="flex-1 p-8">
            <?php include 'includes/header.php'; ?>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-images text-blue-600 mr-2"></i>Banner Management
                    </h1>
                    <button onclick="toggleForm()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i><?php echo $edit_banner ? 'Cancel Edit' : 'Add New Banner'; ?>
                    </button>
                </div>
                
                <!-- Image Requirements Info -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <h3 class="text-blue-800 font-semibold mb-2"><i class="fas fa-info-circle mr-2"></i>Image Requirements</h3>
                    <ul class="text-blue-700 text-sm space-y-1">
                        <li>• <strong>Recommended Size:</strong> 1920x600 pixels (3.2:1 aspect ratio)</li>
                        <li>• <strong>Minimum Size:</strong> 1200x400 pixels</li>
                        <li>• <strong>Maximum File Size:</strong> 2MB</li>
                        <li>• <strong>Formats:</strong> JPG, JPEG, PNG, GIF</li>
                        <li>• <strong>Note:</strong> Images will be used as backgrounds, ensure text is readable over them</li>
                    </ul>
                </div>
                
                <!-- Messages -->
                <?php if ($message): ?>
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($message); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <p class="text-red-700"><i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Add/Edit Form -->
                <div id="bannerForm" class="<?php echo !$edit_banner ? 'hidden' : ''; ?> bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <?php echo $edit_banner ? 'Edit Banner' : 'Add New Banner'; ?>
                    </h2>
                    
                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <?php if ($edit_banner): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_banner['id']; ?>">
                        <?php endif; ?>
                        
                        <!-- Info Notice -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Note:</strong> Only the background image is mandatory. All other fields (title, subtitle, description, button text, button link, display order) are optional and can be left empty if not needed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                                       value="<?php echo $edit_banner ? htmlspecialchars($edit_banner['title']) : ''; ?>" 
                                       placeholder="Enter banner title (optional)">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle (Optional)</label>
                                <input type="text" name="subtitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                                       value="<?php echo $edit_banner ? htmlspecialchars($edit_banner['subtitle']) : ''; ?>" 
                                       placeholder="e.g., ⚡ SPECIAL OFFER (optional)">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                                      placeholder="Banner description text (optional)"><?php echo $edit_banner ? htmlspecialchars($edit_banner['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button Text (Optional)</label>
                                <input type="text" name="button_text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                                       value="<?php echo $edit_banner ? htmlspecialchars($edit_banner['button_text']) : ''; ?>" 
                                       placeholder="e.g., Get Started Now (optional)">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Button Link (Optional)</label>
                                <input type="url" name="button_link" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                                       value="<?php echo $edit_banner ? htmlspecialchars($edit_banner['button_link']) : ''; ?>" 
                                       placeholder="https://example.com or # (optional)">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Background Image *</label>
                                <input type="file" name="background_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" <?php echo !$edit_banner ? 'required' : ''; ?>>
                                <?php if ($edit_banner && $edit_banner['background_image']): ?>
                                <p class="text-sm text-gray-500 mt-1">Current: <?php echo basename($edit_banner['background_image']); ?></p>
                                <p class="text-xs text-gray-400">Leave empty to keep current image</p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Display Order (Optional)</label>
                                <input type="number" name="display_order" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                                       value="<?php echo $edit_banner ? $edit_banner['display_order'] : '0'; ?>">
                                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (default: 0)</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" class="mr-2" <?php echo (!$edit_banner || $edit_banner['is_active']) ? 'checked' : ''; ?>>
                            <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on website)</label>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" name="<?php echo $edit_banner ? 'edit_banner' : 'add_banner'; ?>" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i><?php echo $edit_banner ? 'Update Banner' : 'Add Banner'; ?>
                            </button>
                            <?php if ($edit_banner): ?>
                            <a href="banners.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                
                <!-- Banners List -->
                <div class="space-y-4">
                    <?php if (empty($banners)): ?>
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-images text-4xl mb-4"></i>
                        <p>No banners found. Add your first banner to get started.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($banners as $banner): ?>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <?php
                        $bg_image = '';
                        if ($banner['background_image']) {
                            $parts = pathinfo($banner['background_image']);
                            $bg_image = '../' . $parts['dirname'] . '/' . rawurlencode($parts['basename']);
                        }
                        ?>
                        <div class="banner-preview" style="background-image: url('<?php echo $bg_image; ?>');">
                            <div class="banner-overlay">
                                <div class="text-center px-4 py-8 text-white max-w-2xl">
                                    <?php if ($banner['subtitle']): ?>
                                    <div class="bg-yellow-400 text-gray-900 inline-block px-4 py-2 rounded-full font-bold mb-4">
                                        <?php echo htmlspecialchars($banner['subtitle']); ?>
                                    </div>
                                    <?php endif; ?>
                                    <h1 class="text-2xl md:text-4xl font-bold mb-4"><?php echo htmlspecialchars($banner['title']); ?></h1>
                                    <?php if ($banner['description']): ?>
                                    <p class="text-lg mb-6"><?php echo htmlspecialchars($banner['description']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($banner['button_text']): ?>
                                    <button class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-lg font-bold hover:bg-yellow-300 transition-colors">
                                        <?php echo htmlspecialchars($banner['button_text']); ?>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-gray-50 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="<?php echo $banner['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?> px-2 py-1 rounded-full text-xs font-semibold">
                                    <?php echo $banner['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                                <span class="text-sm text-gray-600">Order: <?php echo $banner['display_order']; ?></span>
                                <span class="text-sm text-gray-600">Created: <?php echo date('M j, Y', strtotime($banner['created_at'])); ?></span>
                                <?php if ($banner['background_image']): ?>
                                <span class="text-sm text-gray-600">Image: <?php echo basename($banner['background_image']); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="?edit=<?php echo $banner['id']; ?>" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors text-sm">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                
                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to toggle this banner status?')">
                                    <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
                                    <button type="submit" name="toggle_status" class="<?php echo $banner['is_active'] ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600'; ?> text-white px-3 py-1 rounded transition-colors text-sm">
                                        <i class="fas fa-<?php echo $banner['is_active'] ? 'pause' : 'play'; ?> mr-1"></i><?php echo $banner['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                    </button>
                                </form>
                                
                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this banner? This action cannot be undone.')">
                                    <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
                                    <button type="submit" name="delete_banner" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-colors text-sm">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleForm() {
            const form = document.getElementById('bannerForm');
            form.classList.toggle('hidden');
        }
    </script>
</body>
</html>