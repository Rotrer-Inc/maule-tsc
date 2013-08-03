<?php
/**
 * Template Name: Recarga Home
 *
 * Description: Template recarga
 */

get_header(); ?>
			<div class="content">
			<?php while ( have_posts() ) : the_post(); ?>
				
			<?php endwhile; // end of the loop. ?>
			</div>
<?php get_footer(); ?>