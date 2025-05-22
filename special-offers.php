<?php require_once "includes/init.php"; ?>
<?php include ("header.php"); ?>

<!-- Special Offers Section -->
<section class="special-offers py-5">
    <div class="container">
        <h2 class="text-center mb-5 display-5 fw-bold">Special Promotions</h2>
        <div class="row g-4" id="offers-container">
            <!-- Skeleton placeholders -->
            <?php for ($i = 0; $i < 2; $i++) : ?>
                <div class="col-lg-6">
                    <div class="offer-card card shadow-lg h-100 overflow-hidden skeleton">
                        <div class="row g-0 h-100">
                            <div class="col-md-6 position-relative">
                                <div class="skeleton-image"></div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="card-body p-4">
                                    <div class="skeleton-text short mb-2"></div>
                                    <div class="skeleton-text long mb-3"></div>
                                    <div class="skeleton-text medium mb-4"></div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="skeleton-button"></div>
                                        <div class="skeleton-text short"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<style>
    .special-offers {
        background: linear-gradient(rgba(245, 245, 245, 0.9), rgba(255, 255, 255, 0.9));
    }

    .offer-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        position: relative;
    }

    .offer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }

    .offer-image {
        border-radius: 1rem 0 0 1rem;
        min-height: 250px;
    }

    .highlight-offer {
        border: 2px solid #ffc107;
        background: #fff9e6;
    }

    .offer-badge {
        border-radius: 0 1rem 0 0.5rem;
        font-size: 0.8rem;
        box-shadow: -2px 2px 5px rgba(0,0,0,0.1);
    }

    /* Skeleton styles */
    .skeleton {
        pointer-events: none;
        user-select: none;
    }

    .skeleton-image {
        width: 100%;
        height: 250px;
        background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 37%, #e0e0e0 63%);
        background-size: 400% 100%;
        animation: shimmer 1.4s ease infinite;
        border-radius: 1rem 0 0 1rem;
    }

    .skeleton-text {
        height: 1rem;
        background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 37%, #e0e0e0 63%);
        background-size: 400% 100%;
        animation: shimmer 1.4s ease infinite;
        border-radius: 0.25rem;
    }

    .skeleton-text.short {
        width: 30%;
    }

    .skeleton-text.medium {
        width: 60%;
    }

    .skeleton-text.long {
        width: 90%;
    }

    .skeleton-button {
        width: 100px;
        height: 36px;
        background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 37%, #e0e0e0 63%);
        background-size: 400% 100%;
        animation: shimmer 1.4s ease infinite;
        border-radius: 18px;
    }

    @keyframes shimmer {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    @media (max-width: 768px) {
        .offer-image {
            border-radius: 1rem 1rem 0 0;
            height: 200px;
        }
        
        .col-md-6 {
            width: 100%;
        }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const offersContainer = document.getElementById('offers-container');

    function createOfferCard(offer) {
        const offerClass = offer.highlight ? 'highlight-offer' : '';
        const offerCard = document.createElement('div');
        offerCard.className = 'col-lg-6';
        offerCard.innerHTML = `
            <div class="offer-card ${offerClass} card shadow-lg h-100 overflow-hidden">
                <div class="row g-0 h-100">
                    <div class="col-md-6 position-relative">
                        <img src="${offer.image_path}" 
                             class="offer-image img-fluid h-100 object-fit-cover" 
                             alt="${offer.header}">
                        <div class="offer-badge bg-danger text-white p-2 position-absolute top-0 end-0">
                            Limited Time!
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="card-body p-4">
                            <div class="offer-meta text-muted small mb-2">
                                ${offer.header_top}
                            </div>
                            <h3 class="card-title fs-3 fw-bold mb-3">
                                ${offer.header}
                            </h3>
                            <p class="card-text mb-4">
                                ${offer.header_bottom}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="all-books.php?offer=${offer.id}" 
                                   class="btn btn-primary rounded-pill px-4 py-2">
                                   Browse Collection
                                </a>
                                <small class="text-muted">
                                    Expires: ${new Date(offer.end_date).toLocaleDateString(undefined, { month: 'short', day: 'numeric' })}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        return offerCard;
    }

    function loadOffers() {
        fetch('special-offers-data.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    offersContainer.innerHTML = '';
                    if (data.offers.length > 0) {
                        data.offers.forEach(offer => {
                            const offerCard = createOfferCard(offer);
                            offersContainer.appendChild(offerCard);
                        });
                    } else {
                        offersContainer.innerHTML = '<div class="col-12 text-center py-5"><div class="alert alert-info">Check back soon for exciting offers!</div></div>';
                    }
                } else {
                    offersContainer.innerHTML = '<div class="col-12 text-center py-5"><div class="alert alert-danger">Unable to load offers. Please try again later.</div></div>';
                }
            })
            .catch(() => {
                offersContainer.innerHTML = '<div class="col-12 text-center py-5"><div class="alert alert-danger">Unable to load offers. Please try again later.</div></div>';
            });
    }

    loadOffers();
});
</script>

<?php include ("footer.php"); ?>
