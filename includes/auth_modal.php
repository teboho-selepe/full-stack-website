<!-- Login/Register Modal -->
<div class="auth-modal">
    <button class="close-btn-modal"><i class='bx  bxs-x'  ></i> </button>
    <div class="form-box login">
        <h2>Login</h2>
        <form action="../controllers/auth_process.php" method="post">
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
        <form action="../controllers/auth_process.php" method="post">
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
