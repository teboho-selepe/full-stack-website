<?php
require_once __DIR__ . '/../includes/header.php';
?>

    <section class="contact-intro">

        <div class="contact-info">
            <h2>Contact SmartPath Tutors</h2>
            <p>Have questions or want to start learning with us? Fill out the form below and let us know which module you are interested in. We'll get back to you as soon as possible.</p>
        </div>

        <div class="contact-form-section">
            <form action="send_contact.php" method="post" class="contact-form">
            
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
