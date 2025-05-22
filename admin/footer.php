            <!-- Sidebar end -->
        </div>
    </div>
</div>

<!-- Footer -->
<div class="container-fluid mt-5-fluid mt-5">
    <footer class="bg-light py-3 border-top3 border-top">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <p class="mb-0">Copyright &copy; 2025 Gobindaganj Public Library. All rights reserved</p>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Bootstrap JS Bundle -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl)
        })
    });
</script>
</body>

</html>
