const authModal = document.querySelector('.auth-modal');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const loginBtnModal = document.querySelector('.login-btn-modal');
const closeBtnModal = document.querySelector('.close-btn-modal');
const avatarCicle = document.querySelector('.avatar-cicle');
const profileBox = document.querySelector('.profile-box:not(.notification-box)'); // Get profile box only
const notificationBtn = document.querySelector('.notification-btn');
const notificationBox = document.querySelector('.notification-box');
const alertBox = document.querySelector('.alert-box');


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

// Handle notification bell click
if (notificationBtn) {
    notificationBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent document click from immediately closing it
        if (notificationBox) {
            notificationBox.classList.toggle('show');
            // Close profile dropdown if open
            if (profileBox) profileBox.classList.remove('show');
        }
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    // Don't close if clicking inside a dropdown
    if (e.target.closest('.dropdown')) return;
    
    // Close both dropdowns
    if (profileBox) profileBox.classList.remove('show');
    if (notificationBox) notificationBox.classList.remove('show');
});

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
