<!-- Footer -->
<footer class="mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="mb-4">Gobindaganj Public Library</h5>
                <p class="text-muted">Discover a world of knowledge and imagination through our extensive collection of books, resources, and services.</p>
                <div class="social-links mt-4">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="mb-4">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="all-books.php">Books</a></li>
                    <li><a href="special-offers.php">Special Offers</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="mb-4">Resources</h5>
                <ul class="footer-links">
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="terms-of-service.php">Terms of Service</a></li>
                    <li><a href="sitemap.php">Sitemap</a></li>
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="mb-4">Contact Us</h5>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt me-2"></i> 123 Library Street, Gobindaganj</li>
                    <li><i class="fas fa-phone me-2"></i> (123) 456-7890</li>
                    <li><i class="fas fa-envelope me-2"></i> info@gobindaganjlibrary.com</li>
                    <li><i class="fas fa-clock me-2"></i> Mon-Fri: 9am-8pm, Sat-Sun: 10am-6pm</li>
                </ul>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="copyright text-center">
                    <p>&copy; <?php echo date('Y'); ?> Gobindaganj Public Library. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Toast container for notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <!-- Toasts will be inserted here dynamically -->
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
    crossorigin="anonymous"></script>

<!-- Custom JavaScript -->
<script src="js/validation.js"></script>
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Initialize toasts
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
    });
    
    // Show toast notification function
    function showToast(title, message, type = 'primary') {
        const toastContainer = document.querySelector('.toast-container');
        const id = 'toast-' + Date.now();
        
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true" id="${id}">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(id);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Remove toast from DOM after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function () {
            toastElement.remove();
        });
    }
</script>
</body>

</html>