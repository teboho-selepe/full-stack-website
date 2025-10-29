<?php
require_once __DIR__ . '/../includes/header.php';
?>

    <section class="about-intro">
        <h2>Who We Are</h2>
        <p>SmartPath Tutors is a tutoring platform designed to help university students master programming with confidence. We provide practical guidance in web development, software development, and logical problem solving through personalized and engaging lessons.</p>
    </section>

    <section class="about-mission">
        <h2>Our Mission</h2>
        <p>To bridge the gap between academic learning and real-world software development by offering personalized learning that builds confidence, skills, and career readiness.</p><br>

        <h3>Why Choose Us?</h3>
        <ul>
            <li>One-on-one personalized tutoring</li>
            <li>Project-based learning with real examples</li>
            <li>Assignment and exam support</li>
            <li>Beginner-friendly explanations</li>
            <li>Affordable learning that fits your schedule</li>
        </ul>

    </section>


    <section class="about-approach">
        <h2>How We Teach</h2>
        <p>Our learning approach is simple: <strong>Learn → Practice → Build → Grow</strong>. We explain concepts step by step, guide you through code logic, and help you build real projects so you gain confidence and hands-on experience.</p>

        <div class="video-container">
            <h3>Building ATM Interface using Java</h3>
            <iframe src="https://www.youtube.com/embed/XJOTMuVnils"
                title="Java Development Tutorial for Beginners"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        
    </section>

    <section class="about-team">
        <h2>Meet Our Tutors</h2>
        <p class="team-intro">Our tutoring team is made up of passionate developers who love teaching and helping students grow in tech.</p>

        <div class="tutor-cards">
            
            <div class="tutor-card">
                <img src="../assets/tutor1.jpeg" alt="Tutor 1">
                <h3>Angel M.</h3>
                <p>Web Development Tutor</p>
                <span class="skills">HTML • CSS • JavaScript • PHP</span>
            </div>

            <div class="tutor-card">
                <img src="../assets/tutor2.jpeg" alt="Tutor 2">
                <h3>Selepe T.</h3>
                <p>Software Development Tutor</p>
                <span class="skills">Java • Spring Boot • APIs • OOP</span>
            </div>

            <div class="tutor-card">
                <img src="../assets/tutor3.jpeg" alt="Tutor 3">
                <h3>Keneilwe M.</h3>
                <p>Programming Logic Tutor</p>
                <span class="skills">Algorithms • Python • Data Structures</span>
            </div>

        </div>
    </section>

    <?php include __DIR__ . '/../includes/auth_modal.php'; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
