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
            // Top actions: allow composing a new message
            const topActions = `<div class="notif-actions-top" style="padding:8px;border-bottom:1px solid rgba(255,255,255,0.05);display:flex;gap:8px;align-items:center;">
                    <button class="new-message-btn" style="padding:6px 10px;border:1px solid rgba(255,255,255,0.08);background:transparent;color:#fff;border-radius:6px;">New Message</button>
                    <span style="font-size:12px;color:rgba(255,255,255,0.7)">Send a message without leaving the page</span>
                </div>`;

            conv.innerHTML = topActions + items.map(item => {
                const time = item.created_at ? new Date(item.created_at).toLocaleString() : '';
                const moduleTag = item.module ? `<div class="notif-module">${escapeHtml(item.module)}</div>` : '';
                return `\n                    <div class="notif-item" data-id="${escapeHtml(item.id)}" data-type="${escapeHtml(item.type)}">\n                        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">\n                            <div class="notif-sender">${escapeHtml(item.sender)}</div>\n                            ${moduleTag}\n                        </div>\n                        <div class="notif-message">${escapeHtml(item.message)}</div>\n                        <div class="notif-time">${time}</div>\n                        <div class="notif-actions" style="margin-top:8px;">\n                            <button class="notif-reply-btn" style="padding:6px 10px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff">Reply</button>\n                        </div>\n                    </div>\n                `;
            }).join('');

            // Make dropdown scrollable if long
            if (conv.scrollHeight > 400) conv.style.maxHeight = '400px';

            // Event delegation inside conversation: handle reply and new message actions
            conv.addEventListener('click', async (ev) => {
                const replyBtn = ev.target.closest('.notif-reply-btn');
                const newMsgBtn = ev.target.closest('.new-message-btn');
                const cancelBtn = ev.target.closest('.notif-cancel-btn');
                const sendBtn = ev.target.closest('.notif-send-btn');

                if (cancelBtn) {
                    const form = cancelBtn.closest('.notif-reply-form');
                    if (form) form.remove();
                    return;
                }

                if (replyBtn) {
                    const itemEl = replyBtn.closest('.notif-item');
                    if (!itemEl) return;
                    // Prevent multiple forms on same item
                    if (itemEl.querySelector('.notif-reply-form')) return;

                    const formHtml = `\n                        <div class="notif-reply-form" style="margin-top:8px;">\n                            <textarea class="notif-reply-text" rows="3" placeholder="Write your reply..." style="width:100%;padding:8px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff"></textarea>\n                            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:6px;">\n                                <input class="notif-module-input" placeholder="Module (optional)" style="padding:6px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff">\n                                <button class="notif-send-btn" style="padding:6px 10px;border-radius:6px;border:1px solid rgba(255,255,255,0.08);background:transparent;color:#fff">Send</button>\n                                <button class="notif-cancel-btn" style="padding:6px 10px;border-radius:6px;border:1px solid rgba(255,255,255,0.08);background:transparent;color:#fff">Cancel</button>\n                            </div>\n                        </div>\n                    `;
                    itemEl.insertAdjacentHTML('beforeend', formHtml);
                    const textarea = itemEl.querySelector('.notif-reply-text');
                    if (textarea) textarea.focus();
                    return;
                }

                if (newMsgBtn) {
                    // only one top-level form allowed
                    if (conv.querySelector('.notif-reply-form')) return;
                    const wrapper = document.createElement('div');
                    wrapper.className = 'notif-reply-form-top';
                    wrapper.innerHTML = `\n                        <div class="notif-reply-form" style="padding:8px;">\n                            <textarea class="notif-reply-text" rows="3" placeholder="Write your message..." style="width:100%;padding:8px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff"></textarea>\n                            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:6px;">\n                                <input class="notif-module-input" placeholder="Module (optional)" style="padding:6px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff">\n                                <button class="notif-send-btn" style="padding:6px 10px;border-radius:6px;border:1px solid rgba(255,255,255,0.08);background:transparent;color:#fff">Send</button>\n                                <button class="notif-cancel-btn" style="padding:6px 10px;border-radius:6px;border:1px solid rgba(255,255,255,0.08);background:transparent;color:#fff">Cancel</button>\n                            </div>\n                        </div>\n                    `;
                    conv.prepend(wrapper);
                    const textarea = conv.querySelector('.notif-reply-form .notif-reply-text');
                    if (textarea) textarea.focus();
                    return;
                }

                if (sendBtn) {
                    // figure out which form this belongs to
                    const form = sendBtn.closest('.notif-reply-form');
                    if (!form) return;
                    const textEl = form.querySelector('.notif-reply-text');
                    const moduleEl = form.querySelector('.notif-module-input');
                    const text = textEl ? textEl.value.trim() : '';
                    const moduleVal = moduleEl ? moduleEl.value.trim() : '';
                    if (!text) {
                        alert('Please enter a message');
                        return;
                    }

                    // determine reply target if any
                    const parentItem = form.closest('.notif-item');
                    const parentId = parentItem ? parentItem.getAttribute('data-id') : null;

                    // Send via AJAX to new endpoint
                    try {
                        const fd = new FormData();
                        fd.append('message', text);
                        fd.append('module', moduleVal);
                        if (parentId) fd.append('reply_to', parentId);

                        const res = await fetch('/web/controllers/send_message_ajax.php', { method: 'POST', credentials: 'same-origin', body: fd });
                        const j = await res.json();
                        if (!j.success) {
                            alert(j.error || 'Failed to send');
                            return;
                        }

                        // Insert the new notification at top of list (after top actions)
                        const newNotif = j.notification;
                        const notifHtml = `\n                            <div class="notif-item" data-id="${escapeHtml(newNotif.id)}" data-type="${escapeHtml(newNotif.type)}">\n                                <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">\n                                    <div class="notif-sender">${escapeHtml(newNotif.sender)}</div>\n                                    ${newNotif.module ? `<div class=\"notif-module\">${escapeHtml(newNotif.module)}</div>` : ''}\n                                </div>\n                                <div class="notif-message">${escapeHtml(newNotif.message)}</div>\n                                <div class="notif-time">${new Date(newNotif.created_at).toLocaleString()}</div>\n                                <div class="notif-actions" style="margin-top:8px;"><button class="notif-reply-btn" style="padding:6px 10px;border-radius:6px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff">Reply</button></div>\n                            </div>\n                        `;

                        // place after top actions
                        const firstChild = conv.querySelector(':scope > *:nth-child(1)');
                        if (firstChild) {
                            // if top actions exists, insert after it
                            if (firstChild.classList.contains('notif-actions-top')) {
                                firstChild.insertAdjacentHTML('afterend', notifHtml);
                            } else {
                                conv.insertAdjacentHTML('afterbegin', notifHtml);
                            }
                        } else {
                            conv.insertAdjacentHTML('afterbegin', notifHtml);
                        }

                        // update count
                        const countEl = notificationBtn.querySelector('.notif-count');
                        countEl.textContent = parseInt(countEl.textContent || '0', 10) + 1;

                        // remove form
                        form.remove();

                    } catch (err) {
                        console.error('Send failed', err);
                        alert('Failed to send message');
                    }
                }
            });

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
