<!-- Footer -->
<footer class="bg-gray-800 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Logo & Description -->
            <div class="md:col-span-2">
                <div class="flex items-center mb-4" id="footer-logo">
                    <i class="fas fa-gamepad text-3xl text-blue-400 mr-3"></i>
                    <h3 class="text-2xl font-bold text-white" id="footer-site-name">GameHub</h3>
                </div>
                <p class="text-gray-400 mb-6 max-w-md">
                    Your ultimate destination for discovering and playing amazing games. Join our community of gamers and explore endless adventures!
                </p>
                <div class="flex space-x-4" id="social-media-links">
                    <a href="#" id="footer-facebook-link" class="text-gray-400 hover:text-blue-400 text-2xl transition-colors" style="display: none;">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" id="footer-twitter-link" class="text-gray-400 hover:text-blue-400 text-2xl transition-colors" style="display: none;">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" id="footer-instagram-link" class="text-gray-400 hover:text-pink-400 text-2xl transition-colors" style="display: none;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" id="footer-youtube-link" class="text-gray-400 hover:text-red-400 text-2xl transition-colors" style="display: none;">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" id="footer-telegram-link" class="text-gray-400 hover:text-blue-500 text-2xl transition-colors" style="display: none;">
                        <i class="fab fa-telegram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-400 hover:text-blue-400 transition-colors">Home</a></li>
                    <li><a href="contact" class="text-gray-400 hover:text-blue-400 transition-colors">Contact Us</a></li>
                    <li><a href="terms" class="text-gray-400 hover:text-blue-400 transition-colors">Terms & Conditions</a></li>
                    <li><a href="privacy" class="text-gray-400 hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Contact Info</h4>
                <div class="space-y-3">
                    <div class="flex items-center text-gray-400">
                        <i class="fas fa-envelope mr-3 text-blue-400"></i>
                        <span id="footer-email">support@gamehub.com</span>
                    </div>
                    <div class="flex items-center text-gray-400">
                        <i class="fas fa-phone mr-3 text-blue-400"></i>
                        <span id="footer-phone">+1 (555) 123-4567</span>
                    </div>
                    <div class="flex items-center text-gray-400">
                        <i class="fab fa-whatsapp mr-3 text-green-400"></i>
                        <a id="footer-whatsapp-link" href="https://wa.me/1234567890" target="_blank" class="hover:text-green-400 transition-colors">
                            <span id="footer-whatsapp">WhatsApp Support</span>
                        </a>
                    </div>
                    <div class="flex items-center text-gray-400" id="footer-telegram-contact" style="display: none;">
                        <i class="fab fa-telegram mr-3 text-blue-500"></i>
                        <a id="footer-telegram-contact-link" href="#" target="_blank" class="hover:text-blue-500 transition-colors">
                            <span>Telegram Channel</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-12 pt-8 text-center">
            <p class="text-gray-400">
                &copy; 2025 <span id="footer-copyright-name">GameHub</span>. All rights reserved. Built with ❤️ for gamers worldwide.
            </p>
        </div>
    </div>
</footer>

<!-- Floating Buttons -->
<!-- WhatsApp Button -->
<a id="floating-whatsapp" href="https://wa.me/1234567890" target="_blank" class="fixed bottom-20 right-4 w-12 h-12 md:w-14 md:h-14 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center text-white text-lg md:text-xl shadow-lg transition-colors z-50">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Telegram Floating Button -->
<a id="floating-telegram" href="#" target="_blank" class="fixed bottom-20 left-4 w-12 h-12 md:w-14 md:h-14 bg-blue-500 hover:bg-blue-600 rounded-full flex items-center justify-center text-white text-lg md:text-xl shadow-lg transition-colors z-50" style="display: none;">
    <i class="fab fa-telegram"></i>
</a>

<!-- Back to Top Button -->
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-4 right-4 bg-blue-500 hover:bg-blue-600 w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center text-white shadow-lg transition-colors z-50">
    <i class="fas fa-arrow-up text-sm md:text-base"></i>
</button>

<script>
// Function to update footer with site configuration
function updateFooterWithConfig(config) {
    // Update site name
    if (config.site_name) {
        const siteName = config.site_name;
        document.getElementById('footer-site-name').textContent = siteName;
        document.getElementById('footer-copyright-name').textContent = siteName;
    }
    
    // Update logo if available
    if (config.site_logo) {
        const footerLogo = document.getElementById('footer-logo');
        footerLogo.innerHTML = `<img src="${config.site_logo}" alt="${config.site_name}" class="h-8 mr-3"><h3 class="text-2xl font-bold text-white" id="footer-site-name">${config.site_name}</h3>`;
    }
    
    // Update contact information
    if (config.support_email) {
        document.getElementById('footer-email').textContent = config.support_email;
    }
    
    if (config.contact_number) {
        document.getElementById('footer-phone').textContent = config.contact_number;
    }
    
    if (config.whatsapp_number) {
        const whatsappNumber = config.whatsapp_number;
        
        // Display number with + in footer
        document.getElementById('footer-whatsapp').textContent = `${whatsappNumber}`;
        
        // For WhatsApp links, remove + if present
        const whatsappForLink = whatsappNumber.replace(/^\+/, '').replace(/\s+/g, '');
        
        // Pre-filled message for WhatsApp
        const whatsappMessage = encodeURIComponent("I want a new ID");
        
        // Update WhatsApp links
        document.getElementById('footer-whatsapp-link').href = `https://wa.me/${whatsappForLink}?text=${whatsappMessage}`;
        document.getElementById('floating-whatsapp').href = `https://wa.me/${whatsappForLink}?text=${whatsappMessage}`;
    }
    
    // Update social media links
    if (config.facebook_url) {
        const facebookLink = document.getElementById('footer-facebook-link');
        facebookLink.href = config.facebook_url;
        facebookLink.style.display = 'inline-block';
    }
    
    if (config.twitter_url) {
        const twitterLink = document.getElementById('footer-twitter-link');
        twitterLink.href = config.twitter_url;
        twitterLink.style.display = 'inline-block';
    }
    
    if (config.instagram_url) {
        const instagramLink = document.getElementById('footer-instagram-link');
        instagramLink.href = config.instagram_url;
        instagramLink.style.display = 'inline-block';
    }
    
    if (config.youtube_url) {
        const youtubeLink = document.getElementById('footer-youtube-link');
        youtubeLink.href = config.youtube_url;
        youtubeLink.style.display = 'inline-block';
    }
    
    // Update Telegram links
    if (config.telegram_url) {
        const telegramLink = document.getElementById('footer-telegram-link');
        const telegramContactLink = document.getElementById('footer-telegram-contact-link');
        const telegramContact = document.getElementById('footer-telegram-contact');
        const floatingTelegram = document.getElementById('floating-telegram');
        
        telegramLink.href = config.telegram_url;
        telegramLink.style.display = 'inline-block';
        
        telegramContactLink.href = config.telegram_url;
        telegramContact.style.display = 'flex';
        
        floatingTelegram.href = config.telegram_url;
        floatingTelegram.style.display = 'flex';
    }
}

// Load site configuration and update footer
async function loadFooterConfig() {
    try {
        const response = await fetch('admin/api/site-config.php');
        const data = await response.json();
        
        if (data.success && data.data) {
            updateFooterWithConfig(data.data);
        }
    } catch (error) {
        console.error('Failed to load footer configuration:', error);
    }
}

// Load configuration when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadFooterConfig();
});
</script>