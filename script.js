// Get all the DOM elements we need
const authModal = document.querySelector('.auth-modal');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const loginBtnModal = document.querySelector('.login-btn-modal');
const closeBtnModal = document.querySelector('.close-btn-modal');
const avatarCicle = document.querySelector('.avatar-cicle');
const profileBox = document.querySelector('.profile-box:not(.notification-box)');
const notificationBtn = document.querySelector('.notification-btn');
const notificationBox = document.querySelector('.notification-box');
const alertBox = document.querySelector('.alert-box');

// Mobile menu elements
const menuToggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('nav');
const header = document.querySelector('header');


registerLink.addEventListener('click', () => authModal.classList.add('slide'));
loginLink.addEventListener('click', () => authModal.classList.remove('slide'));

if (loginBtnModal) loginBtnModal.addEventListener('click', () => authModal.classList.add('show'));
closeBtnModal.addEventListener('click', () => authModal.classList.remove('show', 'slide'));

// Handle avatar circle click
if (avatarCicle) {
    avatarCicle.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent document click from immediately closing it
        if (profileBox) {
            profileBox.classList.toggle('show');
            // Close notification dropdown if open
            if (notificationBox) notificationBox.classList.remove('show');
        }
    });
}

// Update notification count on page load (so badge reflects existing messages)
async function updateNotificationCount() {
    if (!notificationBtn) return;
    const countEl = notificationBtn.querySelector('.notif-count');
    try {
        const res = await fetch('/web/controllers/get_notifications.php', { credentials: 'same-origin' });
        if (!res.ok) {
            // not authenticated or no access
            countEl.textContent = '0';
            return;
        }
        const data = await res.json();
        if (!data.success || !Array.isArray(data.notifications)) {
            countEl.textContent = '0';
            return;
        }
        countEl.textContent = data.notifications.length;
    } catch (err) {
        console.error('Failed to update notification count', err);
        const countEl = notificationBtn.querySelector('.notif-count');
        if (countEl) countEl.textContent = '0';
    }
}

// Run count update after initial load
updateNotificationCount();

// Handle notification bell click
if (notificationBtn) {
    notificationBtn.addEventListener('click', async (e) => {
        e.stopPropagation(); // Prevent document click from immediately closing it
        if (!notificationBox) return;

        // Close profile dropdown if open
        if (profileBox) profileBox.classList.remove('show');

        const dropdown = notificationBox.querySelector('.dropdown');
        // Show loading state
        dropdown.innerHTML = '<div style="padding:14px;color:#fff;">Loading...</div>';
        notificationBox.classList.add('show');

        try {
            const res = await fetch('/web/controllers/get_notifications.php', { credentials: 'same-origin' });
            if (!res.ok) {
                dropdown.innerHTML = '<div style="padding:14px;color:#fff;">Could not load notifications.</div>';
                return;
            }
            const data = await res.json();
            if (!data.success) {
                dropdown.innerHTML = `<div style="padding:14px;color:#fff;">${data.error || 'No notifications'}</div>`;
                return;
            }

            const items = data.notifications || [];
            const countEl = notificationBtn.querySelector('.notif-count');
            countEl.textContent = items.length;

            if (items.length === 0) {
                dropdown.innerHTML = '<div style="padding:14px;color:#fff;">No new notifications</div>';
                return;
            }

            // Render conversation-like list
            dropdown.innerHTML = '<div class="notif-conversation"></div>';
            const conv = dropdown.querySelector('.notif-conversation');
            conv.innerHTML = items.map(item => {
                const time = item.created_at ? new Date(item.created_at).toLocaleString() : '';
                const moduleTag = item.module ? `<div class="notif-module">${escapeHtml(item.module)}</div>` : '';
                return `\n                    <div class="notif-item">\n                        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">\n                            <div class="notif-sender">${escapeHtml(item.sender)}</div>\n                            ${moduleTag}\n                        </div>\n                        <div class="notif-message">${escapeHtml(item.message)}</div>\n                        <div class="notif-time">${time}</div>\n                    </div>\n                `;
            }).join('');

            // Make dropdown scrollable if long
            if (conv.scrollHeight > 400) conv.style.maxHeight = '400px';

        } catch (err) {
            dropdown.innerHTML = '<div style="padding:14px;color:#fff;">Error loading notifications</div>';
            console.error(err);
        }
    });
}

// small helper to avoid XSS when injecting server data
function escapeHtml(unsafe) {
    return String(unsafe)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    // Don't close if clicking inside a dropdown
    if (e.target.closest('.dropdown')) return;
    
    // Close both dropdowns
    if (profileBox) profileBox.classList.remove('show');
    if (notificationBox) notificationBox.classList.remove('show');
});

// Handle mobile menu
if (menuToggle && nav) {
    let lastScrollTop = 0;

    // Toggle menu on button click
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        nav.classList.toggle('show');
        const icon = menuToggle.querySelector('i');
        icon.className = nav.classList.contains('show') ? 'bx bx-x' : 'bx bx-menu';
    });

    // Close menu when clicking a link
    nav.addEventListener('click', (e) => {
        if (e.target.tagName === 'A') {
            nav.classList.remove('show');
            menuToggle.querySelector('i').className = 'bx bx-menu';
        }
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('nav') && 
            !e.target.closest('.menu-toggle') && 
            nav.classList.contains('show')) {
            nav.classList.remove('show');
            menuToggle.querySelector('i').className = 'bx bx-menu';
        }
    });

    // Handle header visibility and appearance on scroll
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Update header background opacity based on scroll position
        if (scrollTop > 50) {
            header.style.backgroundColor = 'rgba(3, 3, 3, 0.98)';
            header.style.borderBottom = '1px solid rgba(255, 255, 255, 0.1)';
        } else {
            header.style.backgroundColor = 'rgba(3, 3, 3, 0.95)';
            header.style.borderBottom = 'none';
        }

        // Handle header visibility with some delay for smoother experience
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            if (scrollTop > lastScrollTop && scrollTop > 70) {
                // Scrolling down & past header - hide it
                header.style.transform = 'translateY(-100%)';
                nav.classList.remove('show');
                menuToggle.querySelector('i').className = 'bx bx-menu';
            } else {
                // Scrolling up - show header
                header.style.transform = 'translateY(0)';
            }
        }, 50); // Small delay for smoother behavior
        
        lastScrollTop = scrollTop;
    });
}

if(alertBox){
    setTimeout(() => alertBox.classList.add('show'), 50);

    setTimeout(() => {
        alertBox.classList.remove('show');
        setTimeout(() => alertBox.remove(), 1000);
    }, 6000);
}

// --- Image lightbox: open clicked images in a fullscreen modal ---
(() => {
    const selectors = ['.tutor-card img', '.testimonial-cards .card img', '.card img'];
    const selector = selectors.join(', ');

    // Create modal element once
    let modal = document.querySelector('.image-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.className = 'image-modal';
        modal.innerHTML = `
            <button class="close-btn" aria-label="Close preview">&times;</button>
            <img src="" alt="Preview">
        `;
        document.body.appendChild(modal);
    }

    const modalImg = modal.querySelector('img');
    const closeBtn = modal.querySelector('.close-btn');

    // Open modal when clicking matching images
    document.addEventListener('click', (e) => {
        const img = e.target.closest(selector);
        if (!img) return;
        e.stopPropagation();
        modalImg.src = img.src;
        modalImg.alt = img.alt || 'Image preview';
        modal.classList.add('show');
        // prevent body scroll while open
        document.documentElement.style.overflow = 'hidden';
    });

    // Close handlers
    function closeModal() {
        modal.classList.remove('show');
        modalImg.src = '';
        document.documentElement.style.overflow = '';
    }

    closeBtn.addEventListener('click', (e) => { e.stopPropagation(); closeModal(); });
    modal.addEventListener('click', (e) => {
        // clicking outside the image closes
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
})();
