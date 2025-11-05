<!-- footer.php -->
</main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">SOUND Entertainment</h5>
                    <p>Your ultimate destination for music and videos. Discover new artists, albums, and genres.</p>
                    <div class="social-icons">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="music.php" class="text-white text-decoration-none">Music</a></li>
                        <li><a href="video.php" class="text-white text-decoration-none">Videos</a></li>
                        <li><a href="about.php" class="text-white text-decoration-none">About</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-3">Browse</h5>
                    <ul class="list-unstyled">
                        <li><a href="artist.php" class="text-white text-decoration-none">Artists</a></li>
                        <li><a href="album.php" class="text-white text-decoration-none">Albums</a></li>
                        <li><a href="genre.php" class="text-white text-decoration-none">Genres</a></li>
                        <li><a href="feedback.php" class="text-white text-decoration-none">Feedback</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-3">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Music Street, City</li>
                        <li><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                        <li><i class="fas fa-envelope me-2"></i> info@soundentertainment.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> SOUND Entertainment. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script src="js/script.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>