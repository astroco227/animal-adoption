</main> <!-- End of main container -->

<footer>
    <div class="footer-content">
        <div class="footer-section">
            <p>&copy; <?php echo date('Y'); ?> <?php echo s($settings, 'site_name', 'Paws &amp; Hearts Animal Adoption'); ?>. All Rights Reserved.</p>
            <p style="font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.8;"><?php echo s($settings, 'site_tagline', 'Adopt a friend, save a life ❤️'); ?></p>
        </div>
        <div class="footer-social-wrapper" style="text-align: right;">
            <p style="margin-bottom: 0.8rem; font-weight: 600; font-size: 1.1rem;">Follow Us</p>
            <div class="footer-social">
                <a href="<?php echo s($settings, 'social_twitter', '#'); ?>" target="_blank" aria-label="X (Twitter)" class="social-icon twitter"><i class="fab fa-twitter"></i></a>
                <a href="<?php echo s($settings, 'social_instagram', '#'); ?>" target="_blank" aria-label="Instagram" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo s($settings, 'social_linkedin', '#'); ?>" target="_blank" aria-label="LinkedIn" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
                <a href="<?php echo s($settings, 'social_telegram', '#'); ?>" target="_blank" aria-label="Telegram" class="social-icon telegram"><i class="fab fa-telegram-plane"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
