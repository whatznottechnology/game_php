// API Base URL
const API_BASE = 'admin/api/';

// Global state
let siteSettings = {};
let categories = [];
let allGames = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Track visitor
    trackVisitor();
    
    // Load site settings
    loadSiteSettings();
    
    // Load categories
    loadCategories();
    
    // Load featured games for home page
    if (document.getElementById('featuredGames')) {
        loadFeaturedGames();
    }
    
    // Load all games for game page
    if (document.getElementById('gamesContainer')) {
        loadAllGames();
    }
    
    // Initialize AOS animations
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true
        });
    }
});

// Track visitor
async function trackVisitor() {
    try {
        await fetch(API_BASE + 'track-visitor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                page_url: window.location.href
            })
        });
    } catch (error) {
        console.log('Visitor tracking failed:', error);
    }
}

// Load site settings
async function loadSiteSettings() {
    try {
        const response = await fetch(API_BASE + 'settings.php');
        const result = await response.json();
        
        if (result.success) {
            siteSettings = result.data;
            updateSiteSettings();
        }
    } catch (error) {
        console.error('Failed to load site settings:', error);
    }
}

// Update site settings in DOM
function updateSiteSettings() {
    // Update contact number
    const contactElements = document.querySelectorAll('[data-contact]');
    contactElements.forEach(el => {
        if (siteSettings.contact_number) {
            el.textContent = siteSettings.contact_number;
            el.href = 'tel:' + siteSettings.contact_number.replace(/\s/g, '');
        }
    });
    
    // Update WhatsApp number
    const whatsappElements = document.querySelectorAll('[data-whatsapp]');
    whatsappElements.forEach(el => {
        if (siteSettings.whatsapp_number) {
            el.href = 'https://wa.me/' + siteSettings.whatsapp_number;
        }
    });
    
    // Update email
    const emailElements = document.querySelectorAll('[data-email]');
    emailElements.forEach(el => {
        if (siteSettings.support_email) {
            el.textContent = siteSettings.support_email;
            el.href = 'mailto:' + siteSettings.support_email;
        }
    });
    
    // Update social media links
    if (siteSettings.facebook_url) {
        const fbLinks = document.querySelectorAll('[data-facebook]');
        fbLinks.forEach(el => el.href = siteSettings.facebook_url);
    }
    
    if (siteSettings.twitter_url) {
        const twLinks = document.querySelectorAll('[data-twitter]');
        twLinks.forEach(el => el.href = siteSettings.twitter_url);
    }
    
    if (siteSettings.instagram_url) {
        const igLinks = document.querySelectorAll('[data-instagram]');
        igLinks.forEach(el => el.href = siteSettings.instagram_url);
    }
    
    if (siteSettings.youtube_url) {
        const ytLinks = document.querySelectorAll('[data-youtube]');
        ytLinks.forEach(el => el.href = siteSettings.youtube_url);
    }
}

// Load categories
async function loadCategories() {
    try {
        const response = await fetch(API_BASE + 'categories.php');
        const result = await response.json();
        
        if (result.success) {
            categories = result.data;
            displayCategories();
        }
    } catch (error) {
        console.error('Failed to load categories:', error);
    }
}

// Display categories
function displayCategories() {
    const container = document.getElementById('categoriesContainer');
    if (!container || categories.length === 0) return;
    
    // For home page sidebar - simplified category buttons
    if (window.location.pathname.includes('index.html') || window.location.pathname.endsWith('/')) {
        let categoryHTML = `
            <button onclick="filterGamesByCategory('all', 'All')" 
                    class="w-full flex items-center p-3 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg transition-all"
                    data-category-btn="all">
                <i class="fas fa-th-large mr-3 text-lg"></i>
                <span class="font-medium">All Games</span>
            </button>
        `;
        
        categoryHTML += categories.map(category => `
            <button onclick="filterGamesByCategory(${category.id}, '${category.name}')" 
                    class="w-full flex items-center p-3 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition-all"
                    data-category-btn="${category.id}">
                ${category.icon_path ? 
                    `<img src="${category.icon_path}" alt="${category.name}" class="w-6 h-6 mr-3 object-contain">` :
                    `<i class="fas fa-gamepad mr-3 text-lg text-blue-600"></i>`
                }
                <span class="font-medium">${category.name}</span>
            </button>
        `).join('');
        
        container.innerHTML = categoryHTML;
    } else {
        // For game page - full category cards
        container.innerHTML = categories.map(category => `
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up">
                <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    ${category.icon_path ? 
                        `<img src="${category.icon_path}" alt="${category.name}" class="w-24 h-24 object-contain">` :
                        `<i class="fas fa-gamepad text-6xl text-white"></i>`
                    }
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">${category.name}</h3>
                    <button onclick="filterGamesByCategory(${category.id}, '${category.name}')" 
                            class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all">
                        <i class="fas fa-play mr-2"></i>Explore Games
                    </button>
                </div>
            </div>
        `).join('');
    }
}

// Load featured games for home page
async function loadFeaturedGames() {
    try {
        // Load ALL games instead of just featured
        const response = await fetch(API_BASE + 'games.php');
        const result = await response.json();
        
        if (result.success) {
            allGames = result.data; // Store all games globally
            displayFeaturedGames(result.data);
        }
    } catch (error) {
        console.error('Failed to load featured games:', error);
    }
}

// Display featured games
function displayFeaturedGames(games) {
    const container = document.getElementById('featuredGames');
    if (!container) return;
    
    if (games.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500 col-span-full">No games available</p>';
        return;
    }
    
    // Display all games (not just featured)
    container.innerHTML = games.map(game => `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer" 
             data-aos="fade-up"
             onclick="openGameInquiry('${game.name.replace(/'/g, "\\'")}', '${(game.category_name || '').replace(/'/g, "\\'")}')">
            <div class="relative h-48 overflow-hidden">
                ${game.banner_image ? 
                    `<img src="${game.banner_image}" alt="${game.name}" class="w-full h-full object-cover">` :
                    `<div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-gamepad text-6xl text-white"></i>
                    </div>`
                }
                ${game.is_featured ? `
                    <div class="absolute top-2 right-2 px-3 py-1 bg-yellow-400 text-gray-900 rounded-full text-xs font-bold">
                        <i class="fas fa-star mr-1"></i>Featured
                    </div>
                ` : ''}
            </div>
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-800 mb-2">${game.name}</h3>
                ${game.category_name ? `<p class="text-sm text-gray-600 mb-2"><i class="fas fa-folder mr-1"></i>${game.category_name}</p>` : ''}
                ${game.bonus_amount ? `<p class="text-sm text-green-600 font-semibold mb-2"><i class="fas fa-gift mr-1"></i>${game.bonus_amount}</p>` : ''}
                ${game.platforms ? `<p class="text-xs text-gray-500 mb-3"><i class="fas fa-globe mr-1"></i>${game.platforms}</p>` : ''}
                <button onclick="event.stopPropagation(); openGameInquiry('${game.name.replace(/'/g, "\\'")}', '${(game.category_name || '').replace(/'/g, "\\'")}')" 
                        class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all">
                    <i class="fas fa-play mr-2"></i>Play Now
                </button>
            </div>
        </div>
    `).join('');
}

// Load all games for game page
async function loadAllGames() {
    try {
        const response = await fetch(API_BASE + 'games.php');
        const result = await response.json();
        
        if (result.success) {
            allGames = result.data;
            displayGames(allGames);
            updateGameCount(allGames.length);
        }
    } catch (error) {
        console.error('Failed to load games:', error);
    }
}

// Display games
function displayGames(games) {
    const container = document.getElementById('gamesContainer');
    if (!container) return;
    
    if (games.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">No games found</p>
                <p class="text-sm text-gray-400 mt-2">Try selecting a different category</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = games.map(game => `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up">
            <div class="relative h-56 overflow-hidden">
                ${game.banner_image ? 
                    `<img src="${game.banner_image}" alt="${game.name}" class="w-full h-full object-cover">` :
                    `<div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-gamepad text-6xl text-white"></i>
                    </div>`
                }
                ${game.is_featured ? `
                    <div class="absolute top-2 right-2 px-3 py-1 bg-yellow-400 text-gray-900 rounded-full text-xs font-bold">
                        <i class="fas fa-star mr-1"></i>Featured
                    </div>
                ` : ''}
            </div>
            <div class="p-5">
                <h3 class="text-xl font-bold text-gray-800 mb-2">${game.name}</h3>
                ${game.category_name ? `
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-folder mr-1 text-purple-600"></i>${game.category_name}
                    </p>
                ` : ''}
                
                ${game.description ? `
                    <div class="text-sm text-gray-600 mb-3 line-clamp-2">
                        ${game.description.replace(/<[^>]*>/g, '').substring(0, 100)}...
                    </div>
                ` : ''}
                
                ${game.bonus_amount ? `
                    <p class="text-sm text-green-600 font-semibold mb-2">
                        <i class="fas fa-gift mr-1"></i>${game.bonus_amount}
                    </p>
                ` : ''}
                
                ${game.min_deposit ? `
                    <p class="text-sm text-orange-600 mb-2">
                        <i class="fas fa-wallet mr-1"></i>Min Deposit: ${game.min_deposit}
                    </p>
                ` : ''}
                
                ${game.platforms ? `
                    <p class="text-xs text-gray-500 mb-3">
                        <i class="fas fa-globe mr-1"></i>${game.platforms}
                    </p>
                ` : ''}
                
                <button onclick="openGameInquiry('${game.name.replace(/'/g, "\\'")}', '${(game.category_name || '').replace(/'/g, "\\'")}')" 
                        class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg">
                    <i class="fas fa-play mr-2"></i>Play Now
                </button>
            </div>
        </div>
    `).join('');
}

// Filter games by category on index page
async function filterGamesByCategory(categoryId, categoryName) {
    // Update page title
    const contentTitle = document.getElementById('contentTitle');
    if (contentTitle) {
        contentTitle.textContent = categoryId === 'all' 
            ? 'Available Betting Platforms' 
            : categoryName + ' Games';
    }
    
    // Highlight active category button
    document.querySelectorAll('[data-category-btn]').forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-purple-600', 'text-white', 'shadow-lg');
        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200');
    });
    
    const activeBtn = document.querySelector(`[data-category-btn="${categoryId}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
        activeBtn.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-purple-600', 'text-white', 'shadow-lg');
    }
    
    // Fetch games by category
    try {
        const url = categoryId === 'all' 
            ? API_BASE + 'games.php' 
            : API_BASE + 'games.php?category=' + categoryId;
            
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success) {
            allGames = result.data;
            displayFeaturedGames(result.data);
        }
    } catch (error) {
        console.error('Failed to load games:', error);
    }
}

// Update game count display
function updateGameCount(count) {
    const countElement = document.getElementById('gameCount');
    if (countElement) {
        countElement.textContent = count;
    }
}

// Open inquiry modal with game pre-filled
function openGameInquiry(gameName, categoryName) {
    openModal();
    
    // Pre-fill game name in hidden field or message
    setTimeout(() => {
        const messageField = document.getElementById('inquiryMessage');
        if (messageField && gameName) {
            messageField.value = `I'm interested in playing ${gameName}${categoryName ? ' (' + categoryName + ')' : ''}`;
        }
        
        // Store game name for form submission
        const form = document.getElementById('inquiryForm');
        if (form) {
            form.dataset.gameName = gameName;
        }
    }, 100);
}

// Global form submission handler moved to main script.js

// Handle inquiry form submission
document.addEventListener('submit', async function(e) {
    if (e.target.id === 'inquiryForm') {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
        
        // Get form data
        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            mobile: formData.get('mobile'),
            email: formData.get('email') || '',
            platform: formData.get('platform'),
            interest: formData.get('interest'),
            deposit_amount: formData.get('deposit') || '',
            message: formData.get('message') || '',
            game_name: form.dataset.gameName || ''
        };
        
        try {
            const response = await fetch(API_BASE + 'submit-inquiry.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show success message
                alert('✅ Inquiry submitted successfully! We will contact you shortly via WhatsApp.');
                
                // Reset form
                form.reset();
                delete form.dataset.gameName;
                
                // Close modal
                closeModal();
                
                // Optional: Redirect to WhatsApp
                if (siteSettings.whatsapp_number) {
                    const whatsappMsg = encodeURIComponent(`Hi! I just submitted an inquiry for ${data.interest}. My name is ${data.name}.`);
                    window.open(`https://wa.me/${siteSettings.whatsapp_number}?text=${whatsappMsg}`, '_blank');
                }
            } else {
                throw new Error(result.message || 'Failed to submit inquiry');
            }
        } catch (error) {
            console.error('Submission error:', error);
            alert('❌ Failed to submit inquiry. Please try again or contact us directly via WhatsApp.');
        } finally {
            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('inquiryModal');
    if (e.target === modal) {
        closeModal();
    }
});

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});

// Search functionality for game page
function searchGames(query) {
    query = query.toLowerCase().trim();
    
    if (!query) {
        displayGames(allGames);
        updateGameCount(allGames.length);
        return;
    }
    
    const filtered = allGames.filter(game => 
        game.name.toLowerCase().includes(query) ||
        (game.description && game.description.toLowerCase().includes(query)) ||
        (game.category_name && game.category_name.toLowerCase().includes(query)) ||
        (game.platforms && game.platforms.toLowerCase().includes(query))
    );
    
    displayGames(filtered);
    updateGameCount(filtered.length);
}

// Check URL parameters on game page load
if (window.location.pathname.includes('game.html')) {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');
    
    if (categoryId) {
        // Wait for games to load then filter
        setTimeout(() => {
            filterGamesByCategory(categoryId);
        }, 500);
    }
}
