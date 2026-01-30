<?php get_header(); ?>

<div class="container" style="padding: 80px 20px; min-height: 60vh;">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; else: ?>
        <p><?php _e('Sorry, no content matched your criteria.'); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
