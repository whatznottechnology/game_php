<?php
require_once 'config/database.php';
require_once 'config/auth.php';

requireLogin();

$db = Database::getInstance()->getConnection();
$message = '';
$messageType = '';

// Handle Status Update
if (isset($_POST['update_status'])) {
    try {
        $id = $_POST['inquiry_id'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];
        
        $stmt = $db->prepare("UPDATE inquiries SET status = ?, notes = ? WHERE id = ?");
        $stmt->execute([$status, $notes, $id]);
        
        $message = 'Inquiry updated successfully!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error updating inquiry: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    try {
        $stmt = $db->prepare("DELETE FROM inquiries WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = 'Inquiry deleted successfully!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error deleting inquiry: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Filter logic
$filter_status = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query with filters
$query = "SELECT * FROM inquiries WHERE 1=1";
$params = [];

if ($filter_status !== 'all') {
    $query .= " AND status = ?";
    $params[] = $filter_status;
}

if ($search) {
    $query .= " AND (name LIKE ? OR mobile LIKE ? OR email LIKE ? OR platform LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$query .= " ORDER BY created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$stats_queries = [
    'total' => "SELECT COUNT(*) as count FROM inquiries",
    'new' => "SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'",
    'contacted' => "SELECT COUNT(*) as count FROM inquiries WHERE status = 'contacted'",
    'closed' => "SELECT COUNT(*) as count FROM inquiries WHERE status = 'closed'"
];

$inquiry_stats = [];
foreach ($stats_queries as $key => $sql) {
    $stmt = $db->query($sql);
    $inquiry_stats[$key] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

// Get sidebar statistics
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
    <title>Inquiries - GameHub Admin</title>
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
                <h1 class="text-3xl font-bold text-gray-800">Inquiries Management</h1>
                <p class="text-gray-600 mt-2">View and manage customer inquiries</p>
            </div>
            
            <!-- Success/Error Message -->
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>">
                    <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-semibold">Total Inquiries</p>
                            <h3 class="text-3xl font-bold mt-2"><?php echo $inquiry_stats['total']; ?></h3>
                        </div>
                        <div class="bg-white bg-opacity-30 rounded-full p-4">
                            <i class="fas fa-envelope text-3xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-semibold">New</p>
                            <h3 class="text-3xl font-bold mt-2"><?php echo $inquiry_stats['new']; ?></h3>
                        </div>
                        <div class="bg-white bg-opacity-30 rounded-full p-4">
                            <i class="fas fa-bell text-3xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold">Contacted</p>
                            <h3 class="text-3xl font-bold mt-2"><?php echo $inquiry_stats['contacted']; ?></h3>
                        </div>
                        <div class="bg-white bg-opacity-30 rounded-full p-4">
                            <i class="fas fa-phone text-3xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-semibold">Closed</p>
                            <h3 class="text-3xl font-bold mt-2"><?php echo $inquiry_stats['closed']; ?></h3>
                        </div>
                        <div class="bg-white bg-opacity-30 rounded-full p-4">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" 
                               value="<?php echo htmlspecialchars($search); ?>"
                               placeholder="Search by name, mobile, email, platform..."
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div>
                        <select name="status" 
                                class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                            <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="new" <?php echo $filter_status === 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="contacted" <?php echo $filter_status === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                            <option value="closed" <?php echo $filter_status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    
                    <a href="inquiries.php" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </form>
            </div>
            
            <!-- Inquiries List -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-inbox text-blue-600 mr-2"></i>
                    All Inquiries (<?php echo count($inquiries); ?>)
                </h3>
                
                <?php if (empty($inquiries)): ?>
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-inbox text-6xl mb-4"></i>
                        <p class="text-xl">No inquiries found</p>
                        <p class="text-sm mt-2">Inquiries will appear here when customers submit the contact form</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Customer</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Contact</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Interest</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Platform</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                                    <th class="text-right py-3 px-4 font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inquiries as $inquiry): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4">
                                            <div class="font-semibold text-gray-800"><?php echo htmlspecialchars($inquiry['name']); ?></div>
                                            <?php if ($inquiry['game_name']): ?>
                                                <div class="text-xs text-gray-500">
                                                    <i class="fas fa-gamepad mr-1"></i><?php echo htmlspecialchars($inquiry['game_name']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="text-sm">
                                                <i class="fas fa-phone text-green-600 mr-1"></i>
                                                <?php echo htmlspecialchars($inquiry['mobile']); ?>
                                            </div>
                                            <?php if ($inquiry['email']): ?>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($inquiry['email']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-sm text-gray-700"><?php echo htmlspecialchars($inquiry['interest']); ?></span>
                                            <?php if ($inquiry['deposit_amount']): ?>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-wallet mr-1"></i>₹<?php echo htmlspecialchars($inquiry['deposit_amount']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                                <?php echo htmlspecialchars($inquiry['platform']); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <?php
                                            $statusColors = [
                                                'new' => 'bg-yellow-100 text-yellow-700',
                                                'contacted' => 'bg-purple-100 text-purple-700',
                                                'closed' => 'bg-green-100 text-green-700'
                                            ];
                                            $statusIcons = [
                                                'new' => 'fa-bell',
                                                'contacted' => 'fa-phone',
                                                'closed' => 'fa-check-circle'
                                            ];
                                            $statusClass = $statusColors[$inquiry['status']] ?? 'bg-gray-100 text-gray-700';
                                            $statusIcon = $statusIcons[$inquiry['status']] ?? 'fa-circle';
                                            ?>
                                            <span class="px-3 py-1 <?php echo $statusClass; ?> rounded-full text-xs font-semibold">
                                                <i class="fas <?php echo $statusIcon; ?> mr-1"></i>
                                                <?php echo ucfirst($inquiry['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="text-sm text-gray-700">
                                                <?php echo date('d M Y', strtotime($inquiry['created_at'])); ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <?php echo date('h:i A', strtotime($inquiry['created_at'])); ?>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <button onclick="viewInquiry(<?php echo $inquiry['id']; ?>)" 
                                                    class="text-blue-600 hover:text-blue-800 mr-3">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="?delete=<?php echo $inquiry['id']; ?>" 
                                               onclick="return confirm('Are you sure you want to delete this inquiry?')"
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
    
    <!-- View/Edit Modal -->
    <div id="inquiryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                    Inquiry Details
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="modalContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        const inquiriesData = <?php echo json_encode($inquiries); ?>;
        
        function viewInquiry(id) {
            const inquiry = inquiriesData.find(i => i.id == id);
            if (!inquiry) return;
            
            const content = `
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-user text-blue-600 mr-2"></i>Customer Information
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">Name</label>
                                <p class="font-semibold text-gray-800">${inquiry.name}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Mobile</label>
                                <p class="font-semibold text-gray-800">${inquiry.mobile}</p>
                            </div>
                            ${inquiry.email ? `
                            <div class="col-span-2">
                                <label class="text-sm text-gray-600">Email</label>
                                <p class="font-semibold text-gray-800">${inquiry.email}</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Inquiry Details -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-info-circle text-purple-600 mr-2"></i>Inquiry Details
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">Platform</label>
                                <p class="font-semibold text-gray-800">${inquiry.platform}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Interest</label>
                                <p class="font-semibold text-gray-800">${inquiry.interest}</p>
                            </div>
                            ${inquiry.game_name ? `
                            <div>
                                <label class="text-sm text-gray-600">Game</label>
                                <p class="font-semibold text-gray-800">${inquiry.game_name}</p>
                            </div>
                            ` : ''}
                            ${inquiry.deposit_amount ? `
                            <div>
                                <label class="text-sm text-gray-600">Deposit Amount</label>
                                <p class="font-semibold text-gray-800">₹${inquiry.deposit_amount}</p>
                            </div>
                            ` : ''}
                            ${inquiry.message ? `
                            <div class="col-span-2">
                                <label class="text-sm text-gray-600">Message</label>
                                <p class="text-gray-800 bg-white p-3 rounded border border-gray-200">${inquiry.message}</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Technical Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-chart-line text-green-600 mr-2"></i>Technical Information
                        </h4>
                        <div class="text-sm space-y-2">
                            <div><span class="text-gray-600">IP Address:</span> <code class="ml-2 bg-white px-2 py-1 rounded">${inquiry.ip_address}</code></div>
                            <div><span class="text-gray-600">User Agent:</span> <code class="ml-2 bg-white px-2 py-1 rounded text-xs">${inquiry.user_agent}</code></div>
                            <div><span class="text-gray-600">Submitted:</span> <span class="ml-2 font-semibold">${new Date(inquiry.created_at).toLocaleString()}</span></div>
                        </div>
                    </div>
                    
                    <!-- Update Form -->
                    <form method="POST" class="bg-blue-50 rounded-lg p-4 border-2 border-blue-200">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-edit text-orange-600 mr-2"></i>Update Status
                        </h4>
                        <input type="hidden" name="inquiry_id" value="${inquiry.id}">
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                                <select name="status" required class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                                    <option value="new" ${inquiry.status === 'new' ? 'selected' : ''}>New</option>
                                    <option value="contacted" ${inquiry.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                                    <option value="closed" ${inquiry.status === 'closed' ? 'selected' : ''}>Closed</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label>
                                <textarea name="notes" rows="3" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none" placeholder="Add notes about this inquiry...">${inquiry.notes || ''}</textarea>
                            </div>
                            
                            <button type="submit" name="update_status" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all">
                                <i class="fas fa-save mr-2"></i>Update Inquiry
                            </button>
                        </div>
                    </form>
                </div>
            `;
            
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('inquiryModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('inquiryModal').classList.add('hidden');
        }
        
        // Close modal on outside click
        document.getElementById('inquiryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
