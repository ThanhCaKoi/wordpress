<?php
function bot_scripts() {
    // Enqueue Styles
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), null);
    wp_enqueue_style('bot-style', get_stylesheet_uri());

    // Payment Scripts
    // REPLACE 'sb' WITH YOUR REAL PAYPAL CLIENT ID
    wp_enqueue_script('paypal-sdk', 'https://www.paypal.com/sdk/js?client-id=sb&currency=USD', array(), null, true);
    wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', array(), null, true);

    // Enqueue Scripts
    wp_enqueue_script('bot-script', get_template_directory_uri() . '/script.js', array('jquery'), '1.0', true);
    
    // Pass PHP data to JS (for AJAX URL)
    wp_localize_script('bot-script', 'bot_vars', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'bot_scripts');

// --- STRIPE SERVER SIDE LOGIC ---
add_action('wp_ajax_bot_create_stripe_session', 'bot_create_stripe_session');
add_action('wp_ajax_nopriv_bot_create_stripe_session', 'bot_create_stripe_session');

function bot_create_stripe_session() {
    // 1. CONFIGURATION (Replace with your Secret Key)
    $stripe_secret_key = 'sk_test_...'; // <--- PASTE YOUR SECRET KEY HERE
    $your_domain = home_url();

    // 2. Prepare Data for Stripe API
    $body = array(
        'payment_method_types' => array('card'),
        'line_items' => array(
            array(
                'price_data' => array(
                    'currency' => 'usd',
                    'product_data' => array(
                        'name' => 'Verifiable Onward Ticket',
                        'description' => 'Valid for 48 hours',
                    ),
                    'unit_amount' => 1400, // $14.00 (in cents)
                ),
                'quantity' => 1,
            ),
        ),
        'mode' => 'payment',
        'success_url' => $your_domain . '/success?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $your_domain . '/transaction-cancelled',
    );

    // 3. Convert nested array to HTTP query string (Stripe URL Encoded Form Body)
    // Custom recursive build query or manual construction needed because http_build_query handles arrays differently than Stripe expects
    // Simpler approach for WP: Use standard http_build_query but we need to match Stripe's format line_items[0][price_data]...
    // Let's use a simpler structure or just loop:
    
    $args = array(
        'method'    => 'POST',
        'headers'   => array(
            'Authorization' => 'Bearer ' . $stripe_secret_key,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ),
        'body'      => _bot_build_stripe_query($body),
        'timeout'   => 45,
    );

    // 4. Call Stripe API
    $response = wp_remote_post('https://api.stripe.com/v1/checkout/sessions', $args);

    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => $response->get_error_message()));
    }

    $response_body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($response_body['error'])) {
        wp_send_json_error(array('message' => $response_body['error']['message']));
    }

    // 5. Return Session ID to Frontend
    wp_send_json_success(array('id' => $response_body['id']));
}

// Helper: Format array for Stripe API (WordPress http_build_query isn't perfectly matched for deep nested Stripe arrays often)
function _bot_build_stripe_query($data, $prefix = null) {
    if (!is_array($data)) return $data;
    $params = array();
    foreach ($data as $key => $value) {
        $k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
        if (is_array($value)) {
            $params[] = _bot_build_stripe_query($value, $k);
        } else {
            $params[] = urlencode($k) . '=' . urlencode($value);
        }
    }
    return implode('&', $params);
}
?>
