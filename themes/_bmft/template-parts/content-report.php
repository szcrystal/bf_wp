<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
			if ( is_single() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php _s_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content repo">
		<?php
        	
            
			the_content();
            
            $meta = get_post_meta(get_the_id(), 'dl_file', true);
            //echo $meta;
            
            if($meta != '') {
            ?>
            
            <form method="get" action="" class="clear">
            	<input type="hidden" name="file_name" value="<?php echo $meta; ?>">
                <input type="hidden" name="toDl" value=1>
            	<input type="submit" name="sub" value="ダウンロードページへ">
            </form>

            <?php
            }
                    //echo wp_get_attachment_url($meta);
                    
//                    $path = ABSPATH . "/this_zip/";
//                    $file_name = $_FILES['dl_file']['name'];
//    
//                   if(! move_uploaded_file($_FILES['dl_file']['tmp_name'], $path.$file_name))
//                        echo "error";
//                    
//                    echo "aaa";
//                    print_r($_FILES);
                    
            ?>



	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //_s_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
