<?php
require_once __DIR__ . '/../includes/header.php';
?>

    <section class="contact-intro">

        <div class="contact-info">
            <h2>Contact US</h2>
            <p>Have questions or want to start learning with us? Fill out the form below and let us know which module you are interested in. We'll get back to you as soon as possible.</p>
            <div class="contact-note" role="status" aria-live="polite">
                <i class="bx bx-mail-send note-icon" aria-hidden="true"></i>
                <div class="note-text">Create an account using the email you used to send the message to track or check available messages.</div>
            </div>
        </div>

        <div class="contact-form-section">
            <form action="../controllers/send_contact.php" method="post" class="contact-form">
            
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Your Name" required>
                
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
                
                <label for="module">Select Module</label>
                <select id="module" name="module" required>
                    <option value="" disabled selected>-- Choose a Module --</option>
                    <option value="web-development">Web Development</option>
                    <option value="software-development">Software Development</option>
                    <option value="programming-logic">Programming Logic</option>
                    <option value="full-stack">Full Stack Development</option>
                </select>
                
                <label for="message">Your Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Tell us more..." required></textarea>
                
                <button type="submit">Send Message</button>
            </form>

        </div>
        
    </section>

    <!-- Success Modal -->
    <div class="success-modal" id="successModal">
        <div class="success-content">
            <h2>Message Sent</h2> <br>
            <p>Thank you for contacting SmartPath Tutors! We will get back to you soon.</p><br>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/auth_modal.php'; ?>
    
    <script>
    function closeModal() {
        document.getElementById('successModal').style.display = 'none';
    }
    </script>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>
