<?php
/**
 * Template Name: Recarga Paso 2
 *
 * Description: Template recarga
 */

get_header(); ?>
			<div class="content">
			<?php while ( have_posts() ) : the_post(); ?>
				
			<?php endwhile; // end of the loop. ?>
			</div>
<?php get_footer(); ?>