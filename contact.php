<?php require_once "includes/init.php"; ?>
<?php $pageTitle = "Contact Us"; ?>
<?php include ("header.php"); ?>

<main id="main-content" role="main">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Contact Us</h1>
            <p class="lead text-muted">We'd love to hear from you! Please fill out the form below or use our contact details.</p>
        </div>

        <?php Helper::displayFlashMessage(); // To display any success/error messages from form submission ?>

        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Send us a Message</h3>
                        <form action="process_contact.php" method="POST" class="needs-validation" novalidate>
                            <?php echo Helper::csrfTokenField('contact_form'); ?>

                            <?php echo renderFormInput('text', 'name', 'Your Name', '', true, 'Enter your full name'); ?>
                            <?php echo renderFormInput('email', 'email', 'Your Email', '', true, 'Enter your email address'); ?>
                            <?php echo renderFormInput('text', 'subject', 'Subject', '', true, 'Enter the subject of your message'); ?>
                            <?php echo renderFormTextarea('message', 'Message', '', true, 'Write your message here...', '', ['rows' => 5]); ?>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Our Contact Details</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-map-marker-alt fa-fw fa-lg text-primary mt-1 me-3"></i>
                                <div>
                                    <strong>Address:</strong><br>
                                    Gobindaganj Govt. College Road,<br>
                                    Thana Chottor, Gobindaganj,<br>
                                    Gaibandha, Rangpur Division, 5740<br>
                                    Bangladesh
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-phone-alt fa-fw fa-lg text-primary mt-1 me-3"></i>
                                <div>
                                    <strong>Phone:</strong><br>
                                    <a href="tel:+8801712345678">+880 1712-345678</a> (General Inquiries)<br>
                                    <a href="tel:+8801812345679">+880 1812-345679</a> (Membership Desk)
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-envelope fa-fw fa-lg text-primary mt-1 me-3"></i>
                                <div>
                                    <strong>Email:</strong><br>
                                    <a href="mailto:info@gplibrary.org.bd">info@gplibrary.org.bd</a><br>
                                    <a href="mailto:support@gplibrary.org.bd">support@gplibrary.org.bd</a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-clock fa-fw fa-lg text-primary mt-1 me-3"></i>
                                <div>
                                    <strong>Opening Hours:</strong><br>
                                    Saturday - Thursday: 9:00 AM - 5:00 PM<br>
                                    Friday: Closed
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Placeholder for a map -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3">Find Us on Map</h3>
                        <!-- Example Google Maps Embed -->
                        <div class="ratio ratio-16x9">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3600.689985813095!2d89.38806781501617!3d25.13000038390453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fc5c1cc7a71c7f%3A0x7693c615aabd161a!2sGobindaganj%20Govt.%20College!5e0!3m2!1sen!2sbd!4v1678886655000!5m2!1sen!2sbd" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Gobindaganj Public Library Location">
                            </iframe>
                        </div>
                        <small class="d-block mt-2 text-muted">Note: This is an example map. Replace with your library's actual location.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include ("footer.php"); ?>