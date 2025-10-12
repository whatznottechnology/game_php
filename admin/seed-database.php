<?php
/**
 * Database Seeder - Populate with Dummy Data
 * Run this file once to add sample categories, games, and settings
 */

require_once 'config/database.php';

$db = Database::getInstance()->getConnection();

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Seeder - GameHub</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
</head>
<body class='bg-gray-100 p-8'>
<div class='max-w-4xl mx-auto'>
    <div class='bg-white rounded-xl shadow-lg p-8'>
        <h1 class='text-3xl font-bold text-gray-800 mb-6'>
            <i class='fas fa-database text-blue-600 mr-2'></i>Database Seeder
        </h1>";

try {
    // Clear existing data (optional - comment out if you want to keep existing data)
    echo "<div class='mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400'>
            <p class='text-yellow-700'><i class='fas fa-exclamation-triangle mr-2'></i>Clearing existing data...</p>
          </div>";
    
    $db->exec("DELETE FROM games");
    $db->exec("DELETE FROM categories");
    $db->exec("DELETE FROM inquiries");
    
    // Insert Categories
    echo "<div class='mb-4'>
            <h2 class='text-xl font-bold text-gray-700 mb-3'><i class='fas fa-folder text-purple-600 mr-2'></i>Adding Categories...</h2>";
    
    $categories = [
        ['name' => 'Sports Betting', 'slug' => 'sports-betting', 'icon_path' => 'https://img.icons8.com/color/96/000000/football2--v1.png', 'display_order' => 1],
        ['name' => 'Live Casino', 'slug' => 'live-casino', 'icon_path' => 'https://img.icons8.com/color/96/000000/roulette.png', 'display_order' => 2],
        ['name' => 'Card Games', 'slug' => 'card-games', 'icon_path' => 'https://img.icons8.com/color/96/000000/playing-cards.png', 'display_order' => 3],
        ['name' => 'Horse Racing', 'slug' => 'horse-racing', 'icon_path' => 'https://img.icons8.com/color/96/000000/horse.png', 'display_order' => 4],
        ['name' => 'Esports', 'slug' => 'esports', 'icon_path' => 'https://img.icons8.com/color/96/000000/controller.png', 'display_order' => 5],
        ['name' => 'Poker', 'slug' => 'poker', 'icon_path' => 'https://img.icons8.com/color/96/000000/poker-chip.png', 'display_order' => 6],
    ];
    
    $categoryIds = [];
    foreach ($categories as $cat) {
        $stmt = $db->prepare("INSERT INTO categories (name, slug, icon_path, display_order, is_active) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$cat['name'], $cat['slug'], $cat['icon_path'], $cat['display_order']]);
        $categoryIds[$cat['slug']] = $db->lastInsertId();
        echo "<p class='text-sm text-green-600 ml-4'><i class='fas fa-check mr-2'></i>Added: {$cat['name']}</p>";
    }
    
    echo "</div>";
    
    // Insert Games
    echo "<div class='mb-4'>
            <h2 class='text-xl font-bold text-gray-700 mb-3'><i class='fas fa-gamepad text-blue-600 mr-2'></i>Adding Games...</h2>";
    
    $games = [
        // Sports Betting
        [
            'category' => 'sports-betting',
            'name' => 'Cricket Betting',
            'banner' => 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=800&h=450&fit=crop',
            'description' => '<p><strong>Bet on IPL, T20, Test matches and more!</strong></p><p>Live betting on all international cricket matches. Get the best odds for IPL 2024, World Cup, and domestic cricket leagues.</p><ul><li>Live in-play betting</li><li>Ball-by-ball odds</li><li>Session betting available</li><li>Fancy betting options</li></ul>',
            'platforms' => 'Reddy999, Tenexch, KingExch9',
            'bonus' => '₹5,000 Welcome Bonus',
            'min_deposit' => '₹500',
            'featured' => 1
        ],
        [
            'category' => 'sports-betting',
            'name' => 'Football Betting',
            'banner' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=800&h=450&fit=crop',
            'description' => '<p><strong>Premier League, La Liga, Champions League & More!</strong></p><p>Bet on your favorite football teams and leagues worldwide. Live betting with competitive odds.</p><ul><li>Pre-match & live betting</li><li>Multiple bet types</li><li>Top European leagues</li><li>International tournaments</li></ul>',
            'platforms' => 'BetBhai9, AKQ777, Tenexch',
            'bonus' => '₹3,000 Welcome Bonus',
            'min_deposit' => '₹500',
            'featured' => 1
        ],
        [
            'category' => 'sports-betting',
            'name' => 'Tennis Betting',
            'banner' => 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=800&h=450&fit=crop',
            'description' => '<p><strong>Grand Slam, ATP, WTA Tournaments</strong></p><p>Bet on tennis matches from around the world including all Grand Slam events.</p><ul><li>Live match betting</li><li>Set betting options</li><li>Tournament winners</li><li>Player specials</li></ul>',
            'platforms' => 'Reddy999, KingExch9',
            'bonus' => '₹2,500 Welcome Bonus',
            'min_deposit' => '₹500',
            'featured' => 0
        ],
        
        // Live Casino
        [
            'category' => 'live-casino',
            'name' => 'Live Roulette',
            'banner' => 'https://images.unsplash.com/photo-1596838132731-3301c3fd4317?w=800&h=450&fit=crop',
            'description' => '<p><strong>Real dealers, Real-time action!</strong></p><p>Play European, American, and French Roulette with live dealers streaming 24/7.</p><ul><li>HD video streaming</li><li>Professional dealers</li><li>Multiple tables available</li><li>Chat with dealers</li></ul>',
            'platforms' => 'KingExch9, BetBhai9, AKQ777',
            'bonus' => '₹10,000 Casino Bonus',
            'min_deposit' => '₹1,000',
            'featured' => 1
        ],
        [
            'category' => 'live-casino',
            'name' => 'Live Blackjack',
            'banner' => 'https://images.unsplash.com/photo-1511193311914-0346f16efe90?w=800&h=450&fit=crop',
            'description' => '<p><strong>Beat the dealer at the classic card game!</strong></p><p>Multiple blackjack tables with different betting limits. Play with live dealers in HD quality.</p><ul><li>Classic & VIP tables</li><li>Side bets available</li><li>Professional dealers</li><li>Low & high stakes</li></ul>',
            'platforms' => 'Reddy999, Tenexch, BetBhai9',
            'bonus' => '₹8,000 Casino Bonus',
            'min_deposit' => '₹1,000',
            'featured' => 1
        ],
        [
            'category' => 'live-casino',
            'name' => 'Baccarat',
            'banner' => 'https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?w=800&h=450&fit=crop',
            'description' => '<p><strong>The sophisticated casino classic!</strong></p><p>Play Punto Banco with live dealers. High stakes tables available for VIP players.</p><ul><li>Banker, Player, Tie bets</li><li>VIP rooms available</li><li>Asian baccarat variants</li><li>Live streaming in HD</li></ul>',
            'platforms' => 'KingExch9, AKQ777',
            'bonus' => '₹12,000 VIP Bonus',
            'min_deposit' => '₹2,000',
            'featured' => 0
        ],
        
        // Card Games
        [
            'category' => 'card-games',
            'name' => 'Teen Patti',
            'banner' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=450&fit=crop',
            'description' => '<p><strong>India\'s favorite card game!</strong></p><p>Play Teen Patti with players from across India. Multiple variations including AK47, Muflis, and more.</p><ul><li>Live tables 24/7</li><li>Private tables available</li><li>Multiple game modes</li><li>Chat with players</li></ul>',
            'platforms' => 'Reddy999, Reddy444, Tenexch, KingExch9',
            'bonus' => '₹7,000 Welcome Bonus',
            'min_deposit' => '₹500',
            'featured' => 1
        ],
        [
            'category' => 'card-games',
            'name' => 'Andar Bahar',
            'banner' => 'https://images.unsplash.com/photo-1548690312-e3b507d8c110?w=800&h=450&fit=crop',
            'description' => '<p><strong>Simple, fast-paced card game!</strong></p><p>The classic Indian card game with live dealers. Easy to learn, exciting to play!</p><ul><li>Live dealer tables</li><li>Quick rounds</li><li>Side bets available</li><li>Mobile optimized</li></ul>',
            'platforms' => 'BetBhai9, AKQ777, Tenexch',
            'bonus' => '₹5,000 Welcome Bonus',
            'min_deposit' => '₹500',
            'featured' => 1
        ],
        [
            'category' => 'card-games',
            'name' => '7 Up Down',
            'banner' => 'https://images.unsplash.com/photo-1566443280617-35db331c54fb?w=800&h=450&fit=crop',
            'description' => '<p><strong>Predict if the card is 7 up or 7 down!</strong></p><p>Simple betting game with great odds. Perfect for beginners!</p><ul><li>Easy to play</li><li>Fast results</li><li>Multiple betting options</li><li>Live streaming</li></ul>',
            'platforms' => 'Reddy999, KingExch9',
            'bonus' => '₹4,000 Welcome Bonus',
            'min_deposit' => '₹500',
            'featured' => 0
        ],
        
        // Horse Racing
        [
            'category' => 'horse-racing',
            'name' => 'Horse Racing',
            'banner' => 'https://images.unsplash.com/photo-1534775139832-7e48ffa79706?w=800&h=450&fit=crop',
            'description' => '<p><strong>Bet on horses from around the world!</strong></p><p>Live streaming of races from UK, Ireland, USA, Australia, and India. Multiple bet types available.</p><ul><li>Live race streaming</li><li>Win, Place, Show bets</li><li>Exacta, Trifecta, Superfecta</li><li>Daily race cards</li></ul>',
            'platforms' => 'Tenexch, BetBhai9, AKQ777',
            'bonus' => '₹6,000 Racing Bonus',
            'min_deposit' => '₹1,000',
            'featured' => 0
        ],
        
        // Esports
        [
            'category' => 'esports',
            'name' => 'CS:GO Betting',
            'banner' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&h=450&fit=crop',
            'description' => '<p><strong>Bet on Counter-Strike tournaments!</strong></p><p>Major tournaments, online leagues, and showmatches. Live betting with competitive odds.</p><ul><li>Major tournaments coverage</li><li>Live in-play betting</li><li>Map betting options</li><li>Round winner bets</li></ul>',
            'platforms' => 'KingExch9, AKQ777',
            'bonus' => '₹5,000 Esports Bonus',
            'min_deposit' => '₹500',
            'featured' => 0
        ],
        [
            'category' => 'esports',
            'name' => 'Dota 2 Betting',
            'banner' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=800&h=450&fit=crop',
            'description' => '<p><strong>The International, DPC, and more!</strong></p><p>Bet on Dota 2 matches from all major tournaments worldwide.</p><ul><li>The International betting</li><li>Live match odds</li><li>First blood bets</li><li>Map winner options</li></ul>',
            'platforms' => 'Tenexch, BetBhai9',
            'bonus' => '₹4,500 Esports Bonus',
            'min_deposit' => '₹500',
            'featured' => 0
        ],
        
        // Poker
        [
            'category' => 'poker',
            'name' => 'Texas Hold\'em',
            'banner' => 'https://images.unsplash.com/photo-1511193311914-0346f16efe90?w=800&h=450&fit=crop',
            'description' => '<p><strong>The world\'s most popular poker variant!</strong></p><p>Cash games and tournaments running 24/7. From micro stakes to high roller tables.</p><ul><li>Cash games & tournaments</li><li>Multiple stake levels</li><li>Sit & Go tables</li><li>Multi-table tournaments</li></ul>',
            'platforms' => 'Reddy999, KingExch9, BetBhai9',
            'bonus' => '₹15,000 Poker Bonus',
            'min_deposit' => '₹1,000',
            'featured' => 0
        ],
    ];
    
    foreach ($games as $game) {
        $categoryId = $categoryIds[$game['category']];
        $slug = strtolower(str_replace(' ', '-', $game['name']));
        
        $stmt = $db->prepare("INSERT INTO games 
            (category_id, name, slug, banner_image, description, platforms, bonus_amount, min_deposit, is_featured, is_active, display_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0)");
        
        $stmt->execute([
            $categoryId,
            $game['name'],
            $slug,
            $game['banner'],
            $game['description'],
            $game['platforms'],
            $game['bonus'],
            $game['min_deposit'],
            $game['featured']
        ]);
        
        echo "<p class='text-sm text-green-600 ml-4'><i class='fas fa-check mr-2'></i>Added: {$game['name']}</p>";
    }
    
    echo "</div>";
    
    // Add Sample Inquiries
    echo "<div class='mb-4'>
            <h2 class='text-xl font-bold text-gray-700 mb-3'><i class='fas fa-envelope text-orange-600 mr-2'></i>Adding Sample Inquiries...</h2>";
    
    $inquiries = [
        [
            'name' => 'Rahul Sharma',
            'mobile' => '9876543210',
            'email' => 'rahul.sharma@example.com',
            'platform' => 'Reddy999',
            'interest' => 'Cricket Betting',
            'deposit_amount' => '5000',
            'message' => 'I want to start betting on IPL matches. Need help with registration.',
            'game_name' => 'Cricket Betting',
            'status' => 'new'
        ],
        [
            'name' => 'Priya Patel',
            'mobile' => '9123456789',
            'email' => 'priya.p@example.com',
            'platform' => 'KingExch9',
            'interest' => 'Live Casino',
            'deposit_amount' => '10000',
            'message' => 'Interested in live roulette games',
            'game_name' => 'Live Roulette',
            'status' => 'contacted'
        ],
        [
            'name' => 'Amit Kumar',
            'mobile' => '9988776655',
            'email' => '',
            'platform' => 'Tenexch',
            'interest' => 'Teen Patti',
            'deposit_amount' => '2000',
            'message' => 'Want to play teen patti online',
            'game_name' => 'Teen Patti',
            'status' => 'new'
        ],
        [
            'name' => 'Sneha Reddy',
            'mobile' => '9876512345',
            'email' => 'sneha.r@example.com',
            'platform' => 'BetBhai9',
            'interest' => 'Football Betting',
            'deposit_amount' => '3000',
            'message' => 'Looking for Premier League betting',
            'game_name' => 'Football Betting',
            'status' => 'closed'
        ],
        [
            'name' => 'Vikram Singh',
            'mobile' => '9012345678',
            'email' => 'vikram.s@example.com',
            'platform' => 'Any',
            'interest' => 'All Games',
            'deposit_amount' => '15000',
            'message' => 'New to online betting, need guidance on best platform',
            'game_name' => '',
            'status' => 'contacted'
        ]
    ];
    
    foreach ($inquiries as $inq) {
        $stmt = $db->prepare("INSERT INTO inquiries 
            (name, mobile, email, platform, interest, deposit_amount, message, game_name, ip_address, user_agent, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $inq['name'],
            $inq['mobile'],
            $inq['email'],
            $inq['platform'],
            $inq['interest'],
            $inq['deposit_amount'],
            $inq['message'],
            $inq['game_name'],
            '127.0.0.1',
            'Mozilla/5.0 (Sample User Agent)',
            $inq['status']
        ]);
        
        echo "<p class='text-sm text-green-600 ml-4'><i class='fas fa-check mr-2'></i>Added inquiry from: {$inq['name']}</p>";
    }
    
    echo "</div>";
    
    // Add Visitor Logs
    $stmt = $db->prepare("INSERT INTO visitor_logs (ip_address, user_agent, page_url, referrer) VALUES (?, ?, ?, ?)");
    for ($i = 0; $i < 25; $i++) {
        $pages = ['index.html', 'games.html', 'game.html'];
        $stmt->execute([
            '192.168.1.' . rand(1, 255),
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            $pages[array_rand($pages)],
            'https://google.com'
        ]);
    }
    
    // Success Message
    echo "<div class='mt-6 p-6 bg-green-50 border-2 border-green-500 rounded-lg'>
            <h2 class='text-2xl font-bold text-green-700 mb-2'>
                <i class='fas fa-check-circle mr-2'></i>Database Seeded Successfully!
            </h2>
            <div class='text-green-700 space-y-2 mt-4'>
                <p><strong>✅ 6 Categories added</strong></p>
                <p><strong>✅ 15 Games added</strong> (6 featured)</p>
                <p><strong>✅ 5 Sample inquiries added</strong></p>
                <p><strong>✅ 25 Visitor logs added</strong></p>
            </div>
            <div class='mt-6 flex gap-4'>
                <a href='../' class='px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-all'>
                    <i class='fas fa-home mr-2'></i>View Frontend
                </a>
                <a href='index.php' class='px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all'>
                    <i class='fas fa-tachometer-alt mr-2'></i>View Dashboard
                </a>
            </div>
          </div>";
    
} catch (Exception $e) {
    echo "<div class='p-6 bg-red-50 border-2 border-red-500 rounded-lg'>
            <h2 class='text-2xl font-bold text-red-700 mb-2'>
                <i class='fas fa-exclamation-triangle mr-2'></i>Error!
            </h2>
            <p class='text-red-600'>" . htmlspecialchars($e->getMessage()) . "</p>
          </div>";
}

echo "  </div>
    </div>
</body>
</html>";
?>
