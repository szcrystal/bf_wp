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
        <h1 class="entry-title">お買い上げありがとうございました。</h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
    	<p>
    	<?php the_title(); ?>のダウンロード頂きましてありがとうございました。<br>
		振込先を記載したメールを送信しておりますので、合わせてご確認下さい。
		</p>

    <?php
        $post = $_POST;
        //print_r($post);
        
        if(isSetPost('toThank')) :
            $_SESSION['dl-finish'] = array();
            
            foreach($post as $key => $val) {
                $_SESSION['dl-finish'][$key] = isset($post[$key]) ? $post[$key] : NULL;
            }
        
        	//print_r($_SESSION['dl-finish']);
        
            $script = get_template_directory_uri() . "/inc/handle-file/dl-zipfile-ses.php";
            $script = '<script>location.href="'. $script .'";</script>';

            if(isset($post['zip_file'])) {
                echo $script;
            }
        
        else;
        	echo 'Error:21001 Invalid Access';
        endif;
        ?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //_s_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
