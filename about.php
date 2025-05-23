<?php require_once "includes/init.php"; ?>
<?php $pageTitle = "About Us"; // For dynamic page titles in header.php ?>
<?php include ("header.php"); ?>

<main id="main-content" role="main">
    <div class="container py-5">
        <header class="text-center mb-5">
            <h1 class="display-4 fw-bold">About Gobindaganj Public Library</h1>
            <p class="lead text-muted">Your gateway to knowledge, community, and lifelong learning.</p>
        </header>

        <div class="row g-5">
            <div class="col-lg-8">
                <!-- Mission Section -->
                <section class="mb-5" aria-labelledby="mission-heading">
                    <h2 id="mission-heading" class="mb-3 h3">Our Mission</h2>
                    <p>To provide free and equitable access to a comprehensive range of information resources, foster a love for reading across all ages, support lifelong learning endeavors, and serve as a vibrant and inclusive community hub for the residents of Gobindaganj and surrounding areas.</p>
                    <p>We are dedicated to empowering individuals with knowledge, promoting literacy, preserving local heritage, and encouraging civic engagement for a thriving community.</p>
                </section>

                <!-- History Section -->
                <section class="mb-5" aria-labelledby="history-heading">
                    <h2 id="history-heading" class="mb-3 h3">Our History</h2>
                    <p>Established in <strong>[Year of Establishment - e.g., 2021]</strong>, Gobindaganj Public Library has been a cornerstone of the community for many years. From its humble beginnings, perhaps with a small collection of donated books in a single room, it has grown significantly thanks to the dedication of community leaders, volunteers, and supportive patrons.</p>
                    <p>Over the decades, we've celebrated numerous milestones, including <em>[mention a significant milestone, e.g., "the construction of our current building in 2020", "the launch of our first computer lab in 2024", or "the introduction of digital library services in 2025"]</em>. We are proud of our rich heritage and remain excited about the future as we continue to adapt, innovate, and serve our community's evolving needs.</p>
                </section>

                <!-- Services Section -->
                <section class="mb-5" aria-labelledby="services-heading">
                    <h2 id="services-heading" class="mb-3 h3">What We Offer</h2>
                    <p>Gobindaganj Public Library provides a wide array of services and resources designed to meet the diverse needs of our community:</p>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-book-open text-primary me-2"></i>Extensive Collections</h5>
                                    <p class="card-text">Access a diverse range of fiction and non-fiction books for all ages, academic texts, reference materials, newspapers, magazines, and local history archives.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-laptop-code text-primary me-2"></i>Digital Resources</h5>
                                    <p class="card-text">Utilize public access computers, free Wi-Fi, e-books, audiobooks, online databases for research, and digital learning platforms.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-chalkboard-teacher text-primary me-2"></i>Programs & Events</h5>
                                    <p class="card-text">Participate in children's story times, adult literacy programs, author talks, workshops, book clubs, and community group meetings.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-users text-primary me-2"></i>Community Spaces</h5>
                                    <p class="card-text">Enjoy quiet study areas, comfortable reading lounges, dedicated children's and teen zones, and reservable meeting rooms for community use.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-4">
                <!-- Image Section -->
                <aside>
                    <div class="mb-4 text-center">
                        <img src="images/library-exterior-placeholder.jpg" class="img-fluid rounded shadow-sm" alt="Gobindaganj Public Library Building" style="max-height: 280px; object-fit: cover; width: 100%;">
                        <small class="d-block text-muted mt-1">Our welcoming space for learning and discovery.</small>
                    </div>

                    <!-- Get Involved Section -->
                    <section class="card shadow-sm" aria-labelledby="get-involved-heading">
                        <div class="card-body">
                            <h3 id="get-involved-heading" class="card-title h4 mb-3"><i class="fas fa-hands-helping text-primary me-2"></i>Get Involved</h3>
                            <p>Gobindaganj Public Library thrives on community support. Here are a few ways you can contribute:</p>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-hand-holding-heart text-success me-2"></i><a href="donate.php" class="text-decoration-none">Donate</a> to support our collections and programs.</li>
                                <li class="mb-2"><i class="fas fa-user-clock text-info me-2"></i>Volunteer your time and skills.</li>
                                <li class="mb-2"><i class="fas fa-calendar-check text-warning me-2"></i>Attend our events and spread the word.</li>
                            </ul>
                            <a href="contact.php" class="btn btn-outline-primary mt-2 w-100">Contact Us to Learn More</a>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</main>

<?php include ("footer.php"); ?>