// Game Lead Capture Platform - JavaScript Functions

// Document ready event
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize AOS (Animate On Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    }

    // Initialize hero slider
    initHeroSlider();

    // Sticky navbar on scroll
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-lg');
                navbar.classList.remove('border-b');
            } else {
                navbar.classList.remove('shadow-lg');
                navbar.classList.add('border-b');
            }
        });
    }

    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Game filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    const gameCards = document.querySelectorAll('.game-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('bg-primary', 'text-white'));
            filterButtons.forEach(btn => btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200'));
            
            this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            this.classList.add('bg-primary', 'text-white');
            
            // Filter games
            gameCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Category filtering from sidebar
    const categoryButtons = document.querySelectorAll('.category-btn');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            categoryButtons.forEach(btn => btn.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200'));
            categoryButtons.forEach(btn => btn.classList.add('hover:bg-gray-50'));
            
            this.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
            this.classList.remove('hover:bg-gray-50');
            
            // Filter games
            gameCards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-category') === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const searchResults = document.getElementById('searchResults');
            
            if (searchTerm.length > 0) {
                // Show search results
                if (searchResults) {
                    searchResults.innerHTML = generateSearchResults(searchTerm);
                    searchResults.classList.remove('hidden');
                }
            } else {
                // Hide search results
                if (searchResults) {
                    searchResults.classList.add('hidden');
                }
            }
        });
    }

    // Initialize page-specific functionality
    initPageSpecific();
});

// Global filter function for category buttons
window.filterGames = function(category) {
    const gameCards = document.querySelectorAll('.game-card');
    const categoryButtons = document.querySelectorAll('.category-menu-item');
    const contentTitle = document.getElementById('contentTitle');
    
    // Update active button styling
    categoryButtons.forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white');
        btn.classList.add('bg-white', 'border', 'border-gray-200', 'text-gray-700', 'hover:bg-gray-100');
    });
    
    // Set active button
    event.target.closest('.category-menu-item').classList.remove('bg-white', 'border', 'border-gray-200', 'text-gray-700', 'hover:bg-gray-100');
    event.target.closest('.category-menu-item').classList.add('bg-blue-500', 'text-white');
    
    // Filter games
    gameCards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        if (category === 'all' || cardCategory === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update title
    if (contentTitle) {
        const titles = {
            'all': 'All Betting Platforms Available',
            'sports': 'Sports Betting Platforms',
            'casino': 'Live Casino Platforms',
            'poker': 'Poker Platforms',
            'racing': 'Horse Racing Platforms',
            'esports': 'Esports Betting Platforms',
            'bingo': 'Indian Card Games'
        };
        contentTitle.textContent = titles[category] || 'All Betting Platforms Available';
    }
};

// Hero slider functionality
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    
    if (slides.length === 0) return;
    
    let currentSlide = 0;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0');
                slide.classList.add('opacity-100');
            } else {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            }
        });
        
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('opacity-50');
                dot.classList.add('opacity-100');
            } else {
                dot.classList.remove('opacity-100');
                dot.classList.add('opacity-50');
            }
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }
    
    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
    
    // Auto-advance slides
    setInterval(nextSlide, 5000);
    
    // Initialize first slide
    showSlide(0);
}

// Modal functionality
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Global modal functions
window.openModal = openModal;
window.closeModal = closeModal;

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-backdrop')) {
        event.target.classList.add('hidden');
        event.target.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
});

// Search results generator
function generateSearchResults(searchTerm) {
    const games = [
        { name: 'Action Adventure', category: 'action', image: 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=300&h=200&fit=crop' },
        { name: 'Racing Fever', category: 'racing', image: 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=300&h=200&fit=crop' },
        { name: 'Strategy Master', category: 'strategy', image: 'https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=300&h=200&fit=crop' },
        { name: 'Sports Champion', category: 'sports', image: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=300&h=200&fit=crop' },
        { name: 'RPG Quest', category: 'rpg', image: 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=300&h=200&fit=crop' }
    ];
    
    const filteredGames = games.filter(game => 
        game.name.toLowerCase().includes(searchTerm) || 
        game.category.toLowerCase().includes(searchTerm)
    );
    
    if (filteredGames.length === 0) {
        return '<div class="p-4 text-gray-500">No games found</div>';
    }
    
    return filteredGames.map(game => `
        <div class="p-4 hover:bg-gray-50 cursor-pointer border-b">
            <div class="flex items-center space-x-3">
                <img src="${game.image}" alt="${game.name}" class="w-12 h-12 rounded object-cover">
                <div>
                    <h4 class="font-medium text-gray-900">${game.name}</h4>
                    <p class="text-sm text-gray-500">${game.category}</p>
                </div>
            </div>
        </div>
    `).join('');
}

// Contact form submission
function submitContactForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        message: formData.get('message')
    };
    
    // Here you would typically send the data to your server
    console.log('Contact form submitted:', data);
    
    showNotification('Message sent successfully!', 'success');
    event.target.reset();
    closeModal('contactModal');
}

// Newsletter subscription
function subscribeNewsletter(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const email = formData.get('email');
    
    // Here you would typically send the email to your server
    console.log('Newsletter subscription:', email);
    
    showNotification('Successfully subscribed to newsletter!', 'success');
    event.target.reset();
}

// Global form functions
window.submitContactForm = submitContactForm;
window.subscribeNewsletter = subscribeNewsletter;

// Copy link function
window.copyLink = function() {
    const url = window.location.href;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Link copied to clipboard!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Link copied to clipboard!', 'success');
    }
};

// Play game function
window.playGame = function(gameId) {
    // In a real application, this would open the game
    showNotification('Game is loading...', 'info');
    setTimeout(() => {
        window.open('#', '_blank'); // Replace with actual game URL
    }, 1000);
};

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotification = document.getElementById('notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };

    const notification = document.createElement('div');
    notification.id = 'notification';
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Slide in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification && notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification && notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Page-specific initialization
function initPageSpecific() {
    const path = window.location.pathname;
    const filename = path.substring(path.lastIndexOf('/') + 1);

    switch (filename) {
        case 'category.html':
            initCategoryPage();
            break;
        case 'game.html':
            initGamePage();
            break;
        case 'search.html':
            initSearchPage();
            break;
        default:
            break;
    }
}

// Category page initialization
function initCategoryPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryName = urlParams.get('name');
    
    if (categoryName) {
        const categoryTitle = document.getElementById('categoryTitle');
        if (categoryTitle) {
            categoryTitle.textContent = categoryName;
        }
        
        // Filter games based on category
        const gameCards = document.querySelectorAll('.game-card');
        gameCards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            if (cardCategory === categoryName.toLowerCase()) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
}

// Game page initialization
function initGamePage() {
    const urlParams = new URLSearchParams(window.location.search);
    const gameId = urlParams.get('id');
    
    if (gameId) {
        // Load game details based on ID
        loadGameDetails(gameId);
    }
}

// Search page initialization
function initSearchPage() {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q');
    
    if (query) {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = query;
            performSearch(query);
        }
    }
}

// Load game details
function loadGameDetails(gameId) {
    // Sample game data - in a real app, this would come from an API
    const games = {
        1: {
            name: 'Action Adventure',
            category: 'Action',
            rating: 4.5,
            description: 'An exciting action-adventure game with stunning graphics and immersive gameplay.',
            image: 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=600&h=400&fit=crop',
            screenshots: [
                'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=300&h=200&fit=crop'
            ]
        },
        2: {
            name: 'Racing Fever',
            category: 'Racing',
            rating: 4.3,
            description: 'High-speed racing game with realistic physics and multiple tracks.',
            image: 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=600&h=400&fit=crop',
            screenshots: [
                'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=300&h=200&fit=crop',
                'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=300&h=200&fit=crop'
            ]
        }
    };
    
    const game = games[gameId];
    if (game) {
        // Update page content with game details
        const gameTitle = document.getElementById('gameTitle');
        const gameImage = document.getElementById('gameImage');
        const gameDescription = document.getElementById('gameDescription');
        const gameRating = document.getElementById('gameRating');
        
        if (gameTitle) gameTitle.textContent = game.name;
        if (gameImage) gameImage.src = game.image;
        if (gameDescription) gameDescription.textContent = game.description;
        if (gameRating) gameRating.textContent = game.rating;
    }
}

// Perform search
function performSearch(query) {
    const games = [
        { id: 1, name: 'Action Adventure', category: 'action', image: 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=300&h=200&fit=crop' },
        { id: 2, name: 'Racing Fever', category: 'racing', image: 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=300&h=200&fit=crop' },
        { id: 3, name: 'Strategy Master', category: 'strategy', image: 'https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=300&h=200&fit=crop' },
        { id: 4, name: 'Sports Champion', category: 'sports', image: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=300&h=200&fit=crop' },
        { id: 5, name: 'RPG Quest', category: 'rpg', image: 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=300&h=200&fit=crop' }
    ];
    
    const filteredGames = games.filter(game => 
        game.name.toLowerCase().includes(query.toLowerCase()) || 
        game.category.toLowerCase().includes(query.toLowerCase())
    );
    
    const searchResults = document.getElementById('searchResults');
    if (searchResults) {
        if (filteredGames.length === 0) {
            searchResults.innerHTML = '<div class="text-center py-8 text-gray-500">No games found</div>';
        } else {
            searchResults.innerHTML = filteredGames.map(game => `
                <div class="game-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow" data-category="${game.category}">
                    <img src="${game.image}" alt="${game.name}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">${game.name}</h3>
                        <p class="text-gray-600 text-sm mb-3">${game.category}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="ml-2 text-sm text-gray-600">4.2</span>
                            </div>
                            <button onclick="playGame(${game.id})" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Play Now
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }
}