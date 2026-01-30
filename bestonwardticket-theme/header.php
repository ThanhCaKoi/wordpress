<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title is handled by WordPress in modern functions but for simplicity we let WP_Head handle it if theme support added, else fall back -->
    <title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
    
    <!-- SEO & Open Graph -->
    <meta name="description" content="Get a Verifiable Onward Ticket for Visa Applications & Airport Check-in. Valid flight reservations for just $14. Delivered instantly.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Best Onward Ticket - Rent A Proof Of Onward Travel">
    <meta property="og:description" content="Rent a verifiable flight reservation for visa applications and airport check-in. Only $14. Valid for 48 hours.">
    <meta property="og:url" content="<?php echo home_url(); ?>">
    <meta property="og:site_name" content="BestOnwardTicket">
    <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/hero.png">
    <meta name="twitter:card" content="summary_large_image">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="<?php echo home_url(); ?>" class="logo">BestOnwardTicket</a>
            <ul class="nav-links">
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#pricing">Price</a></li>
                <li><a href="#faq">FAQ</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="#hero" class="btn btn-primary nav-btn">Book Now</a></li>
            </ul>
            <div class="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>
