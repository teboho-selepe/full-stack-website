<?php require_once __DIR__ . '/includes/header.php';
$alerts = $_SESSION['alerts'] ?? [];
$active_form = $_SESSION['active_form'] ?? '';
// Clear only flash data (alerts and active_form) so logged-in state persists
unset($_SESSION['alerts'], $_SESSION['active_form']);
?>

    <!-- <section>
        <h1>Hello <?=$name ?? 'World' ?>!</h1>
    </section> -->

    <main class="wrap">
        <!-- HERO / HOME -->
        <section id="home" class="hero">
            <div>
                <h1>Unlock your tech future — learn to code with confidence</h1>
                <p>Personalized tutoring in Web Development, Software Development and Programming Logic. Build portfolio-ready projects, master problem solving, and prepare for real internships.</p>
                <div class="buttons">
                    <a class="cta" href="#contact">Get Started</a>
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
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Alex Photo">
                    <p>"The one-on-one sessions helped me grasp complex concepts easily. Highly recommend!"</p>
                    <strong>- Alex P.</strong>
                </div>
                <div class="card">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Maria Photo">
                    <p>"Thanks to the project-based learning approach, I built a strong portfolio that landed me an internship."</p>
                    <strong>- Maria S.</strong>
                </div>
                <div class="card">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" alt="John Photo">
                    <p>"The exam support was a lifesaver! Clear explanations made all the difference."</p>
                    <strong>- John D.</strong>
                </div>
            </div>
        </section>

        

    </main>

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

    <div class="auth-modal <?= $active_form === 'register' ? 'slide' : ($active_form === 'login' ? 'show' : ''); ?>">

        <button class="close-btn-modal"><i class='bx  bxs-x'  ></i> </button>
        
        <div class="form-box login">
            <h2>Login</h2>
            <form action="controllers/auth_process.php" method="post">
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx  bxs-envelope'  ></i> 
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="password" required>
                    <i class='bx  bxs-lock'  ></i> 
                </div>
                <button type="submit" name="login_btn" class="btn">Login</button>
                <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
            </form>
        </div>

        <div class="form-box register">
            <h2>Register</h2>
            <form action="controllers/auth_process.php" method="post">
                <div class="input-box">
                    <input type="text" name="name" placeholder="Name" required>
                    <i class='bx  bxs-user'  ></i> 
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx  bxs-envelope'  ></i> 
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="password" required>
                    <i class='bx  bxs-lock'  ></i> 
                </div>
                <button type="submit" name="register_btn" class="btn">Register</button>
                <p>Already have an account? <a href="#" class="login-link">Login</a></p>
            </form>
        </div>
    </div>
    
<?php require_once __DIR__ . '/includes/footer.php'; ?>