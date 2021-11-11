<?php
/**
 * The template for displaying Marques Taxonomies
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
?>
<div class="main">
	<div class="entry-content">
		<?php 
		$current_term_id = get_queried_object()->term_id;
		$cat_args = array(
			'orderby'       => $current_term_id, 
			'order'         => 'ASC',
			'hide_empty'    => true, 
		);

		$terms = get_terms('marques', $cat_args);

		// If current page is sub category this code whill be run / this code showing all voitures in the current page / marque
		if ($current_term_id !== "") :
			$loop_marque = get_posts( array(
				'post_type' => 'voitures',
				'post_status' => 'publish',
				'posts_per_page' => -1, 
				'orderby' => 'date', 
				'order' => 'ASC',
				'tax_query' => array(
					array(
						'taxonomy' => 'marques',
						'field' => 'term_id',
						'terms' => $current_term_id
					)
				)
			) );
			// this code show all of the marques
			if ($loop_marque) : ?>
				<header class="page-header alignwide">
					<div class="archive-description"><?php echo '<h1>' . single_term_title() . '</h1>'; ?></div>
				</header>

				<article>
						<?php 
						foreach( $loop_marque as $post ) { 
						?>
								<header>
									<div class="archive-description">
										<a href="<?php the_permalink(); ?>">
											<h2><?php the_title(); ?></h2>
										</a>
									</div>
								</header>
								<?php the_post_thumbnail(); ?>
								<p class="activity"><?php the_excerpt(); ?></p>
						<?php
						};
						?>   
				</article>
				<?php		

			else :
					echo "Rien ici";
			endif; // end of loop sub category

		// If current page is not a sub category ---->
		else:
			// If current page is parent category this code whill be run / this code showing list of marques
			if($terms) :
				foreach($terms as $taxonomy){
					$term_slug = $taxonomy->slug;
					$term_url  = get_term_link($taxonomy->name, 'marques');
					?>
					<header class="page-header alignwide">
						<div class="archive-description"><?php echo '<a href="'.$term_url .'"><h2>' . $taxonomy->name . '</h2></a>'; ?></div>
					</header>
				
				<?php }; //end foreach loop
			else :
					echo "Rien ici";
			endif;  // end of loop Parent category / all marques
			 
		
		endif; ?>
	</div>

</div>

<?php get_footer(); ?>
