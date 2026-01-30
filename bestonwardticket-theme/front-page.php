<?php get_header(); ?>

    <!-- Hero Section -->
    <header id="hero" class="hero">
        <div class="container hero-container">
            <!-- JSON-LD Structured Data for SEO -->
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "Product",
              "name": "Onward Ticket Reservation",
              "description": "Verifiable flight reservation for visa applications and proof of onward travel.",
              "brand": {
                "@type": "Brand",
                "name": "BestOnwardTicket"
              },
              "offers": {
                "@type": "Offer",
                "url": "<?php echo home_url(); ?>",
                "priceCurrency": "USD",
                "price": "14.00",
                "availability": "https://schema.org/InStock"
              },
              "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.9",
                "reviewCount": "1250"
              }
            }
            </script>

            <div class="hero-content">
                <span class="badge">The #1 Choice for Digital Nomads</span>
                <h1>Get a Verifiable Onward Ticket for <span class="highlight">Visa Applications</span></h1>
                <p class="hero-subtitle">Valid flight reservations for just <strong>$14</strong>. Delivered to your inbox in minutes.</p>
                
                <ul class="hero-features">
                    <li><i class="fas fa-check-circle"></i> No cancellation fees</li>
                    <li><i class="fas fa-check-circle"></i> Works for Visas</li>
                    <li><i class="fas fa-check-circle"></i> Instant Download</li>
                </ul>
            </div>
            
            <!-- Multi-Step Booking Container -->
            <div class="booking-container-wrapper">
                
                <!-- STEP 1: SEARCH FORM -->
                <div class="hero-form-card" id="search-step">
                    <div class="form-header">
                        <h3><i class="fas fa-plane"></i> Find Onward Flights</h3>
                        <p>Search millions of routes for your visa application</p>
                    </div>
                    <form class="booking-form" id="flight-search-form">
                        <!-- Trip Type -->
                        <div class="form-group-row">
                            <label class="radio-label">
                                <input type="radio" name="trip-type" value="oneway" checked> 
                                <span>One Way</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="trip-type" value="roundtrip"> 
                                <span>Round Trip</span>
                            </label>
                        </div>

                        <!-- Passengers -->
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Passengers</label>
                            <div class="passenger-inputs" style="display: flex; gap: 10px;">
                                <div class="pax-input-group">
                                    <span class="pax-label">Adults (12+)</span>
                                    <input type="number" id="adults" value="1" min="1" max="9">
                                </div>
                                <div class="pax-input-group">
                                    <span class="pax-label">Child (2-11)</span>
                                    <input type="number" id="children" value="0" min="0" max="9">
                                </div>
                                <div class="pax-input-group">
                                    <span class="pax-label">Infant (<2)</span>
                                    <input type="number" id="infants" value="0" min="0" max="9">
                                </div>
                            </div>
                        </div>

                        <!-- Route -->
                        <div class="form-group-row">
                            <div class="form-group" style="flex:1">
                                <label>From</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-plane-departure"></i>
                                    <input type="text" id="origin" placeholder="City or Airport" required>
                                </div>
                            </div>
                            <div class="form-group" style="flex:1">
                                <label>To</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-plane-arrival"></i>
                                    <input type="text" id="destination" placeholder="City or Airport" required>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="form-group">
                            <label>Departure Date</label>
                            <input type="date" id="departure-date" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" id="search-btn">
                            <i class="fas fa-search"></i> Search Flights
                        </button>
                    </form>
                </div>

                <!-- STEP 2: FLIGHT RESULTS (Hidden) -->
                <div class="hero-form-card" id="results-step" style="display: none; max-width: 800px; width: 100%;">
                    <div class="form-header">
                        <h3>Select Your Flight</h3>
                        <p>We found the following verifiable routes</p>
                    </div>
                    <div id="flight-results-list" class="flight-list">
                        <!-- Results will be injected here via JS -->
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i> Searching best routes...
                        </div>
                    </div>
                    <button class="btn btn-secondary back-btn" onclick="showStep('search')">Back to Search</button>
                </div>

                <!-- STEP 3: BOOKING DETAILS (Hidden - Matches Screenshot) -->
                <div class="hero-form-card" id="booking-step" style="display: none; max-width: 600px; width: 100%;">
                    <div class="form-header" style="position: relative;">
                        <!-- Back Button -->
                        <button type="button" class="btn-back-text" onclick="showStep('results')" style="position: absolute; left: 0; top: 5px; background: none; border: none; color: #666; cursor: pointer;">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        
                        <h3>Passenger Details</h3>
                        <p id="selected-flight-info"></p>
                    </div>
                    <form class="booking-form" id="final-booking-form">
                        
                        <!-- Passengers Readonly -->
                        <div class="form-group">
                            <label>Passengers <span class="required">(Required)</span></label>
                            <div class="readonly-input">
                                <i class="fas fa-user-friends"></i> <span id="pax-summary">1 Adult</span>
                            </div>
                        </div>

                        <!-- Name Fields -->
                        <div class="form-group-row name-row">
                            <div class="form-group" style="flex: 0 0 80px;">
                                <select id="title" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                                    <option>Mr</option>
                                    <option>Ms</option>
                                    <option>Mrs</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <input type="text" id="first-name" placeholder="First name *" required>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <input type="text" id="last-name" placeholder="Last name *" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label>Your Email* (Our tickets will be sent to this Email) <span class="required">(Required)</span></label>
                            <div class="input-with-icon">
                                <i class="far fa-envelope"></i>
                                <input type="email" id="email" placeholder="Your email" required>
                            </div>
                        </div>

                        <!-- Ticket Valid Options -->
                        <div class="form-group">
                            <label>Ticket Valid</label>
                            <div class="validity-options">
                                <label class="validity-pill active">
                                    <input type="radio" name="validity" value="48h" checked>
                                    48 hours
                                </label>
                                <label class="validity-pill">
                                    <input type="radio" name="validity" value="7d">
                                    7 days (+$7)
                                </label>
                                <label class="validity-pill">
                                    <input type="radio" name="validity" value="14d">
                                    14 days (+$10)
                                </label>
                            </div>
                        </div>

                        <!-- Ensure Vertical Checkboxes -->
                        <div class="checkbox-group" style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                            <div class="checkbox-item">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="later-ticket"> 
                                    I want to receive my ticket later (+$1.00 - No delays, served 24/7)
                                </label>
                            </div>
                            <div class="checkbox-item">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="add-notes"> 
                                    Add notes to order
                                </label>
                            </div>
                             <div class="checkbox-item">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="promo-code"> 
                                    Promotion Code
                                </label>
                            </div>
                        </div>

                        <!-- Payment Method Tabs -->
                        <div class="payment-section">
                            <label>Choose payment method</label>
                            <div class="payment-tabs">
                                <div class="payment-tab active" data-method="stripe">Credit card(Stripe)</div>
                                <div class="payment-tab" data-method="paypal">Paypal</div>
                            </div>
                            <input type="hidden" id="selected-payment-method" value="stripe">
                        </div>

                        <!-- Card Details (Stripe Mock) -->
                        <div id="stripe-fields" class="payment-fields">
                            <label>Credit Card</label>
                            <div class="form-group">
                                <label>Card Details</label>
                                <div class="input-with-icon">
                                    <i class="far fa-credit-card"></i>
                                    <input type="text" placeholder="Card number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Cardholder Name</label>
                                <input type="text" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                            </div>
                            <p class="secure-notice">We never store your credit card information.</p>
                        </div>

                        <!-- Total & Submit -->
                        <div class="total-bar">
                            <span>Total Price:</span>
                            <span class="total-price" id="final-price">$14.00</span>
                        </div>

                        <button type="button" id="pay-button" class="btn btn-primary btn-block btn-lg" style="background-color: #3b5998;">
                            Pay & Book Ticket
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    <!-- Features / Trust Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-shield-check"></i></div>
                    <h3>100% Verifiable</h3>
                    <p>Real PNR codes verifiable on major airline websites (AirFrance, Lufthansa, etc).</p>
                </div>
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-bolt"></i></div>
                    <h3>Instant Delivery</h3>
                    <p>Receive your perfectly formatted PDF ticket immediately after payment.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-lock"></i></div>
                    <h3>Secure & Safe</h3>
                    <p>We value your privacy. Payments processed securely via PayPal.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>How to Get Your Onward Ticket in 3 Steps</h2>
                <p>Simplicity is key. It shouldn't feel like booking a flight.</p>
            </div>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h4>Enter Details</h4>
                    <p>Select your route and enter passenger names. No passport number required.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h4>Secure Payment</h4>
                    <p>Pay the flat $14 fee securely via PayPal or Credit Card. No hidden charges.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h4>Fly Stress-Free</h4>
                    <p>Download your ticket instantly. Show it at immigration and travel with peace of mind.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Use Us (SEO Content) -->
    <section class="seo-content">
        <div class="container split-layout">
            <div class="text-content">
                <h2>Do You Really Need Proof of Onward Travel?</h2>
                <p>Traveling on a one-way ticket? You might face issues at the airport check-in desk or immigration control. Many countries require <strong>proof of onward travel</strong>—a return flight ticket to prove you won't overstay your visa.</p>
                <p>Don't risk being denied boarding or spending hundreds on a full-price flexible ticket you'll have to cancel later.</p>
                <br>
                <h3>BestOnwardTicket provides a solution for:</h3>
                <ul class="check-list">
                    <li><strong>Digital Nomads</strong> who don't know their next destination.</li>
                    <li><strong>Backpackers</strong> traveling overland or on open-ended trips.</li>
                    <li><strong>Visa Applicants</strong> who need a flight itinerary for their application.</li>
                </ul>
            </div>
            <div class="image-content placeholders" style="background: none; height: auto;">
                <!-- Icon Composition instead of Image -->
                <div class="ticket-visual">
                    <div class="ticket-icon-large">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <div class="ticket-details-visual">
                        <span><i class="fas fa-check"></i> Valid PNR Code</span>
                        <span><i class="fas fa-check"></i> Real Airline Reservation</span>
                        <span><i class="fas fa-check"></i> 48hr Validity</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>Trusted by Travelers Worldwide</h2>
            </div>
            <div class="testimonial-grid">
                <div class="content-card testimonial">
                    <p>"Saved my trip! The airline wouldn't let me check in without a return flight. I bought this at the counter, got the PDF in 2 minutes."</p>
                    <div class="user">
                        <div class="avatar">S</div>
                        <div class="info">
                            <h5>Sarah J.</h5>
                            <span>Digital Nomad</span>
                        </div>
                    </div>
                </div>
                <div class="content-card testimonial">
                    <p>"Perfect for my Schengen visa application. The consulate needed a flight itinerary, but I didn't want to pay for a flight yet. Works perfectly."</p>
                    <div class="user">
                        <div class="avatar">C</div>
                        <div class="info">
                            <h5>Carlos M.</h5>
                            <span>Traveler</span>
                        </div>
                    </div>
                </div>
                <div class="content-card testimonial">
                    <p>"Legit service. I entered the PNR on the airline's website and it was there. Valid for well over 48 hours which gave me plenty of time."</p>
                    <div class="user">
                        <div class="avatar">H</div>
                        <div class="info">
                            <h5>Hiroshi T.</h5>
                            <span>Backpacker</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Is this a fake ticket?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>No. We provide a genuine flight reservation with a unique PNR (Passenger Name Record) verifiable on the airline's website or CheckMyTrip.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>How long is the ticket valid?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Our tickets are valid for at least 48 hours, often longer. We recommend buying it within 24 hours of your flight or visa appointment.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Do I need to cancel the ticket?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>No, we handle the cancellation automatically. You don't need to do anything.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Can I actually fly with this ticket?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>No. This is a reservation for proof of travel purposes only. It does not include the full fare payment required to board the plane.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>
