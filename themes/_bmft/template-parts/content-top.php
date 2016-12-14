<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php
        	//the_post_thumbnail();
			the_content();
		?>
	</div><!-- .entry-content -->

    <div class="clear">
		<div class="topics">
        </div>
    </div>

	<footer class="entry-footer">

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
