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
            <a href="#" aria-label="Instagram" class="text-light me-3 fs-5"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>
<!-- Load Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Verify Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JS failed to load properly');
    } else {
        console.log('Bootstrap JS loaded successfully');
    }
</script>
<script>
// Force-enable all Bootstrap dropdowns after DOM loads
window.addEventListener('DOMContentLoaded', function() {
    // Ensure Bootstrap is loaded before initializing components
    if (typeof bootstrap !== 'undefined') {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });

        // Initialize modals (e.g., the logout modal from navbar.php)
        var modalElementList = [].slice.call(document.querySelectorAll('.modal'));
        modalElementList.map(function (modalEl) {
            // This will initialize all modals on the page.
            return new bootstrap.Modal(modalEl);
        });

        // Keyboard navigation for dropdowns (example, can be enhanced)
        document.querySelectorAll('.dropdown').forEach(function(dropdown) {
            var toggle = dropdown.querySelector('.dropdown-toggle');
            var menu = dropdown.querySelector('.dropdown-menu');
            if(toggle && menu) {
                // Basic keyboard support can be added here if Bootstrap's default isn't sufficient
                // For example, handling Escape key to close dropdowns.
            }
        });
    } else {
        console.error('Bootstrap JS not loaded, cannot initialize components in footer.php');
    }
});
</script>
<script>
// --- Book Dataset JSON (sample, replace/fetch as needed) ---
const bookData = [
  {
    id: "bhoot-samagro",
    title: "Bhoot Samagro",
    author: "Ahsan Habib",
    reviews: [4, 5, 3]
  }
  // Add more books as needed
];

// --- Book Rating System ---
class BookRating {
  static getRating(bookId) {
    const stored = localStorage.getItem(`rating_${bookId}`);
    return stored ? parseInt(stored, 10) : null;
  }
  static setRating(bookId, rating) {
    localStorage.setItem(`rating_${bookId}`, rating);
  }
  static getAverage(reviews) {
    if (!reviews.length) return 0;
    return (reviews.reduce((a, b) => a + b, 0) / reviews.length).toFixed(1);
  }
}

// --- Donation Progress System ---
class DonationProgress {
  constructor(goal, current) {
    this.goal = goal;
    this.current = current;
    this.progressBar = document.getElementById('donation-progress-bar');
    this.counter = document.getElementById('donation-counter');
    this.input = document.getElementById('donation-input');
    this.button = document.getElementById('donate-btn');
    this.status = document.getElementById('donation-status');
    this.init();
  }
  init() {
    this.updateUI();
    if (this.button) {
      this.button.addEventListener('click', () => this.handleDonate());
    }
    this.animateCounter();
  }
  updateUI() {
    if (this.progressBar) {
      this.progressBar.style.width = `${(this.current / this.goal) * 100}%`;
      this.progressBar.setAttribute('aria-valuenow', this.current);
    }
    if (this.counter) {
      this.counter.textContent = `$${this.current.toLocaleString()} / $${this.goal.toLocaleString()}`;
    }
  }
  animateCounter() {
    if (!this.counter) return;
    let start = 0;
    const end = this.current;
    const duration = 1200;
    const step = (timestamp, startTime) => {
      if (!startTime) startTime = timestamp;
      const progress = Math.min((timestamp - startTime) / duration, 1);
      const value = Math.floor(progress * (end - start) + start);
      this.counter.textContent = `$${value.toLocaleString()} / $${this.goal.toLocaleString()}`;
      if (progress < 1) {
        requestAnimationFrame(ts => step(ts, startTime));
      }
    };
    requestAnimationFrame(step);
  }
  handleDonate() {
    const amount = parseInt(this.input.value, 10);
    if (isNaN(amount) || amount < 10) {
      alert('Minimum donation is $10.');
      if (this.status) {
        this.status.textContent = 'Minimum donation is $10.';
        this.status.className = 'status-error';
      }
      return;
    }
    this.current += amount;
    this.input.value = '';
    this.updateUI();
    if (this.status) {
      this.status.textContent = 'Thank you for your donation!';
      this.status.className = 'status-success';
    }
  }
}


</body>
</html>
