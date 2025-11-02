<?php require_once __DIR__ . '/includes/header.php';
$alerts = $_SESSION['alerts'] ?? [];
$active_form = $_SESSION['active_form'] ?? '';
// Clear only flash data (alerts and active_form) so logged-in state persists
unset($_SESSION['alerts'], $_SESSION['active_form']);
?>

    
    <main class="wrap">
        <!-- HOME -->
        <section id="home" class="hero">
            <div>
                <h1> Hello <?=$name ?? 'Developer' ?>! Unlock your tech future — learn to code with confidence</h1>
                <p>Personalized tutoring in Web Development, Software Development and Programming Logic. Build portfolio-ready projects, master problem solving, and prepare for real internships.</p>
                <div class="buttons">
                    <a class="cta" href="pages/contact.php">Get Started</a>
                    <a style="padding:10px 14px;border-radius:10px;border:1px solid rgba(10,20,40,0.06);text-decoration:none;font-weight:700" href="pages/about.php">Why SmartPath</a>
                </div>


                <div class="features" aria-hidden="false">
                    <div class="feature">
                        <h4>One-on-One Tutoring</h4>
                        <p class="muted">Tailored lessons to match your course & pace.</p>
                    </div>
                    <div class="feature">
                        <h4>Project-Based Learning</h4>
                        <p class="muted">Hands-on projects for your portfolio and interviews.</p>
                    </div>
                    <div class="feature">
                        <h4>Exam & Assignment Support</h4>
                        <p class="muted">Get clear, step-by-step help when deadlines matter.</p>
                    </div>
                </div>
                
            </div>


            <aside class="card">
                <h3 style="margin-top:0">Quick Services</h3>
                <div class="services">
                    <div class="feature"><strong>Web Dev</strong><div class="muted">HTML • CSS • JS • React</div></div>
                    <div class="feature"><strong>Backend</strong><div class="muted">Java Spring • Node • Python</div></div>
                    <div class="feature"><strong>Programming Logic</strong><div class="muted">Algorithms • Data Structures</div></div>
                </div>
            </aside>
        </section>

        <!-- Testemonials -->
        <section class="testimonials">
            <h2>What Our Students Say</h2>
            <div class="testimonial-cards">
                <div class="card">
                    <img src="/web/assets/images/testimonials/alex.jpg" alt="Alex Photo">
                    <p>"The one-on-one sessions helped me grasp complex concepts easily. Highly recommend!"</p>
                    <strong>- Alex P.</strong>
                </div>
                <div class="card">
                    <img src="/web/assets/images/testimonials/maria.jpg" alt="Maria Photo">
                    <p>"Thanks to the project-based learning approach, I built a strong portfolio that landed me an internship."</p>
                    <strong>- Maria S.</strong>
                </div>
                <div class="card">
                    <img src="/web/assets/images/testimonials/john.jpg" alt="John Photo">
                    <p>"The exam support was a lifesaver! Clear explanations made all the difference."</p>
                    <strong>- John D.</strong>
                </div>
            </div>
        </section>

    </main>

    <!--  Show the alert message-->
    <?php if(!empty($alerts)): ?>
    <div class="alert-box" >
        <?php foreach($alerts as $alert): ?>
        <div class="alert <?= $alert['type']; ?>">
            <i class='bx  <?= $alert['type'] === 'success' ? 'bxs-checkbox-checked' : 'bxs-x-checkbox';?>'></i>
            <span><?= $alert['message']; ?></span> 
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Authentication Modal -->
    <?php include __DIR__ . '/includes/auth_modal.php'; ?> 
    
<?php require_once __DIR__ . '/includes/footer.php'; ?>