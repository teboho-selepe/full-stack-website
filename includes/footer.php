    
    <footer class="site-footer">
    <div style="width:100%; background: rgba(3, 3, 3, 0.8); backdrop-filter: blur(20px); padding: 20px;">
        <div style="display: grid; grid-template-columns: 1fr; gap: 30px; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <!-- Logo Section -->
            <div style="text-align: center;">
                <img src="/web/assets/logo.png" alt="SmartPath Tutoring Logo" style="height:120px; display:inline-block; margin-bottom:8px;">
            </div>

            <!-- Quick Links -->
            <div style="text-align:center; color:#fff;">
                <strong style="display:block; margin-bottom:15px;">Quick Links</strong>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="/web/index.php" class="footer-link">Home</a>
                    <a href="/web/pages/about.php" class="footer-link">About</a>
                    <a href="/web/pages/contact.php" class="footer-link">Contact</a>
                </div>
            </div>

            <!-- Contact Info -->
            <div style="text-align:center; color:#fff;">
                <strong style="display:block; margin-bottom:10px;">Contact</strong>
                <div style="font-size:14px; margin-bottom:15px;">
                    <div>Email: info@smartpath.com</div>
                    <div>Phone: +27 12 345 6789</div>
                </div>
                
                <strong style="display:block; margin-bottom:10px;">Address</strong>
                <div style="font-size:14px;">
                    <div>2 Cassandra Avenue</div>
                    <div>Bedworth Park</div>
                    <div>Vereeniging</div>
                    <div>1939</div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div style="text-align:center; padding:20px 0 0; font-size:13px; color:#aaa; border-top:1px solid rgba(255,255,255,0.1); margin-top:20px;">
            &copy; 2025 SmartPath Tutoring. All rights reserved.
        </div>
    </div>
</footer>

<!-- Add responsive footer styles -->
<style>
@media (min-width: 768px) {
    .site-footer img {
        height: 160px !important;
    }
    
    .site-footer > div > div {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 40px !important;
        text-align: left !important;
    }
    
    .site-footer > div > div > div {
        text-align: left !important;
    }
}
</style>
    <script src="/web/script.js"></script>
    </body>
</html>
