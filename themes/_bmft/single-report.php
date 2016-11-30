<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package _s
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
        
//        if(is_singular('report'))
//            echo get_page_slug(get_the_id());
//        
//        if(is_page('report'))
//        	echo "page";
        
		while ( have_posts() ) : the_post();
        	
            if(isset($_GET['toDl']) && $_GET['toDl']) {
            	
                if($auth->getAuth()) {
//                	if(isset($_POST['toThank']) && $_POST['toThank'])
//                    	get_template_part( 'template-parts/content', 'report-thank' );
//                    else
	                	get_template_part( 'template-parts/content', 'report-dl' );
    			}
                else {
                	get_template_part( 'template-parts/content', 'report-noauth' );
                }
            }
            else {
				get_template_part( 'template-parts/content', 'report' );
            }
			
            //the_post_navigation();

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
