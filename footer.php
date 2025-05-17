<!-- Minimalist Footer -->
<footer class="mt-5 bg-secondary text-light py-4" role="contentinfo" aria-label="Footer">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="mb-3 mb-md-0">
            <a href="index.php" class="text-light text-decoration-none fw-bold fs-5" aria-label="Gobindaganj Public Library Home">Gobindaganj Public Library</a>
            <p class="small mb-0 mt-1">© <?php echo date('Y'); ?> Gobindaganj Public Library. All rights reserved.</p>
        </div>
        <ul class="list-inline mb-0">
            <li class="list-inline-item"><a href="index.php" class="text-light text-decoration-none" aria-label="Home">Home</a></li>
            <li class="list-inline-item"><a href="all-books.php" class="text-light text-decoration-none" aria-label="Books">Books</a></li>
            <li class="list-inline-item"><a href="contact.php" class="text-light text-decoration-none" aria-label="Contact">Contact</a></li>
        </ul>
        <div>
            <a href="#" aria-label="Facebook" class="text-light me-3 fs-5"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Twitter" class="text-light me-3 fs-5"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="Instagram" class="text-light me-3 fs-5"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="LinkedIn" class="text-light fs-5"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <button id="back-to-top" class="btn btn-primary btn-sm mt-3 mt-md-0" aria-label="Back to top" title="Back to top">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>
</footer>

<script>
document.getElementById('back-to-top').addEventListener('click', function() {
    window.scrollTo({top: 0, behavior: 'smooth'});
});
</script>
