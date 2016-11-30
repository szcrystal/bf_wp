<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
            
            //$user_info = get_userdata(1);
            //print_r($user_info);
            
//            global $wpdb;
//            $payObj = $wpdb -> get_results("SELECT DISTINCT user_id, report_id FROM purchase_repo WHERE user_id = 23", OBJECT); //DISTINCT report_id
//            //print_r($payObj);
//            $arr = array();
//            
//            foreach($payObj as $val) {
//            	$arr[] = $wpdb -> get_row("SELECT * FROM purchase_repo WHERE user_id = $val->user_id AND report_id = $val->report_id", OBJECT);
//            }
            
            //print_r($arr);
            
            
			while ( have_posts() ) : the_post();
            

				get_template_part( 'template-parts/content', 'page' );
				
                /* Include Auth File -> ShortCode On Functions ******************************
                if(is_page('login'))
					include_once('inc/auth/login.php');
				if(is_page('register') || is_page('edit'))
                	include_once('inc/auth/register/auth-register.php');
                if(is_page('userinfo'))
                	include_once('inc/auth/register/auth-information.php');
                */
//                if(is_page('reset-passwd'))
//                	include_once('inc/auth/register/auth-reset.php');
//                
//                else if (is_page('contact'))
//					include_once('inc/contact/contact.php');
                
				// If comments are open or we have at least one comment, load up the comment template.
//				if ( comments_open() || get_comments_number() ) :
//					comments_template();
//				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
