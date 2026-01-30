document.addEventListener("DOMContentLoaded", () => {
  // --- UI HELPERS ---
  const showStep = (stepName) => {
    document.getElementById("search-step").style.display = "none";
    document.getElementById("results-step").style.display = "none";
    document.getElementById("booking-step").style.display = "none";

    document.getElementById(stepName + "-step").style.display = "block";
  };

  // Make global for inline onclicks
  window.showStep = showStep;

  // Mobile Nav
  const hamburger = document.querySelector(".hamburger");
  const navLinks = document.querySelector(".nav-links");
  if (hamburger && navLinks) {
    hamburger.addEventListener("click", () => {
      navLinks.classList.toggle("active");
      hamburger.querySelector("i").classList.toggle("fa-bars");
      hamburger.querySelector("i").classList.toggle("fa-times");
    });
  }

  // --- STEP 1: SEARCH ---
  const searchForm = document.getElementById("flight-search-form");
  if (searchForm) {
    searchForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const origin = document.getElementById("origin").value;
      const dest = document.getElementById("destination").value;
      const date = document.getElementById("departure-date").value;

      // Validate
      if (!origin || !dest || !date) {
        alert("Please fill in all search fields");
        return;
      }

      // Show Loading in Results
      showStep("results");
      const resultsContainer = document.getElementById("flight-results-list");
      resultsContainer.innerHTML =
        '<div style="text-align:center; padding:40px;"><i class="fas fa-spinner fa-spin fa-3x" style="color:#0073e6"></i><p>Searching best flights for you...</p></div>';

      // Simulate API Call delay
      setTimeout(() => {
        displayMockFlights(origin, dest, date);
      }, 1500);
    });
  }

  const displayMockFlights = (from, to, date) => {
    const resultsContainer = document.getElementById("flight-results-list");
    // Generate nice looking dummy data
    const airlines = [
      "Qatar Airways",
      "Emirates",
      "Turkish Airlines",
      "Singapore Airlines",
      "Lufthansa",
    ];
    let html = "";

    for (let i = 0; i < 3; i++) {
      const airline = airlines[Math.floor(Math.random() * airlines.length)];
      const depTime =
        Math.floor(Math.random() * 23) +
        ":" +
        (Math.random() < 0.5 ? "00" : "30");
      const flightNum =
        airline.substring(0, 2).toUpperCase() +
        Math.floor(Math.random() * 900 + 100);

      html += `
            <div class="flight-item" onclick="selectFlight('${flightNum}', '${airline}', '${from}', '${to}', '${date}')">
                <div class="flight-logo" style="width:50px; height:50px; background:#eee; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#555;">
                    ${airline.substring(0, 1)}
                </div>
                <div class="flight-info" style="flex:1; margin-left:15px;">
                    <h4>${airline} (${flightNum})</h4>
                    <div class="flight-route">${from} <i class="fas fa-long-arrow-alt-right"></i> ${to}</div>
                    <div class="flight-meta" style="font-size:0.9rem; color:#666;">
                        <i class="far fa-clock"></i> ${date} at ${depTime}
                    </div>
                </div>
                <div class="flight-action">
                    <button class="select-btn">Select <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            `;
    }
    resultsContainer.innerHTML = html;
  };

  window.selectFlight = (flightNum, airline, from, to, date) => {
    // Update Summary in Step 3
    const summaryText = `${airline} (${flightNum}) - ${from} to ${to} on ${date}`;
    document.getElementById("selected-flight-info").innerText = summaryText;

    // Update Passenger Summary
    const adults = document.getElementById("adults").value;
    const children = document.getElementById("children").value;
    const infants = document.getElementById("infants").value;
    let paxString = `${adults} Adult(s)`;
    if (children > 0) paxString += `, ${children} Child`;
    if (infants > 0) paxString += `, ${infants} Infant`;
    document.getElementById("pax-summary").innerText = paxString;

    // Show Step 3
    showStep("booking");
  };

  // --- STEP 3: BOOKING DETAILS ---

  // Validity Pills Logic
  const validityPills = document.querySelectorAll(".validity-pill");
  validityPills.forEach((pill) => {
    pill.addEventListener("click", () => {
      // Remove active from all
      validityPills.forEach((p) => {
        p.classList.remove("active");
        p.querySelector("input").checked = false;
      });
      // Add active to clicked
      pill.classList.add("active");
      const input = pill.querySelector("input");
      input.checked = true;

      updateTotalPrice(input.value);
    });
  });

  const updateTotalPrice = (valValue) => {
    let base = 14.0;
    // Count passengers? Typically per pax. Let's assume total for now or multiply.
    // Copy implies $14 flat, but usually per pax. Let's keep simpler logic for now matching the prompt "Only $14.00".
    // Actually, user screenshot shows "Passengers" count input disabled as "1", so maybe $14 per pax.
    const paxCount =
      parseInt(document.getElementById("adults").value) +
      parseInt(document.getElementById("children").value) +
      parseInt(document.getElementById("infants").value);

    let extra = 0;
    if (valValue === "7d") extra = 7;
    if (valValue === "14d") extra = 10;

    const total = (base + extra) * paxCount;
    document.getElementById("final-price").innerText = "$" + total.toFixed(2);
  };

  // Payment Tabs Logic
  const paymentTabs = document.querySelectorAll(".payment-tab");
  paymentTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      paymentTabs.forEach((t) => t.classList.remove("active"));
      tab.classList.add("active");
      const method = tab.getAttribute("data-method");
      document.getElementById("selected-payment-method").value = method;

      // Show/Hide fields
      const stripeFields = document.getElementById("stripe-fields");
      const payButton = document.getElementById("pay-button");

      if (method === "paypal") {
        stripeFields.style.display = "none";
        payButton.innerHTML = '<i class="fab fa-paypal"></i> Pay with PayPal';
        payButton.style.backgroundColor = "#003087";
      } else {
        stripeFields.style.display = "block";
        payButton.innerHTML = "Pay & Book Ticket";
        payButton.style.backgroundColor = "#3b5998";
      }
    });
  });

  // Pay Button Logic
  document.getElementById("pay-button").addEventListener("click", () => {
    const method = document.getElementById("selected-payment-method").value;
    const email = document.getElementById("email").value;
    if (!email) {
      alert("Please enter your email address to receive the ticket.");
      return;
    }

    if (method === "stripe") {
      // Call the existing AJAX function we built earlier?
      // Re-using the logic from previous turn for Stripe
      const btn = document.getElementById("pay-button");
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
      btn.disabled = true;

      // Mocking the call for now as the AJAX endpoint needs updating to handle dynamic price
      if (typeof bot_vars !== "undefined") {
        fetch(bot_vars.ajax_url + "?action=bot_create_stripe_session", {
          method: "POST",
        })
          .then((r) => r.json())
          .then((res) => {
            if (res.success && typeof Stripe !== "undefined") {
              const stripe = Stripe("pk_test_TYooMQauvdEDq54NiTphI7jx");
              stripe.redirectToCheckout({ sessionId: res.data.id });
            } else {
              alert("Simulation: Stripe Session Created (Live key needed).");
              btn.disabled = false;
            }
          })
          .catch((e) => {
            alert("Order Placed Successfully (Simulation). check email.");
            btn.disabled = false;
          });
      } else {
        setTimeout(() => {
          alert("Order Placed Successfully! (Simulation)");
          btn.disabled = false;
          btn.innerHTML = "Pay & Book Ticket";
        }, 2000);
      }
    } else {
      // PayPal
      alert("Redirecting to PayPal...");
    }
  });
});
