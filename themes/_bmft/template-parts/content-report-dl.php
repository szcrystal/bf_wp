<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */
//echo $_SERVER['HTTP_USER_AGENT'];
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

	<div class="entry-content">

        <?php
//        	if(! $auth->getAuth()):
//            	echo 'ログインが必要です';
//                exit();
//            endif;
			
            global $authId, $ca;
            
        	include_once(get_template_directory() . "/inc/download/FileDownloadClass.php");
            $fd = new FileDownload();
            $isHistoryDl = $fd->checkIsSameData();
		?>

		<p>
    	ダウンロードボタンをクリックするとダウンロードが始まります。<br>
        <?php if(! $isHistoryDl) { ?>
		その後、振込口座が記載されたメールが送信されますので、ご確認の上お振込下さい。<br><br>
        <?php } ?>
		</p>
		<?php
        	
        	
            
        	$price = get_post_meta(get_the_id(), 'price', true);
//            echo get_the_title()."<br>";
//            echo $ca->getData('nick_name', $authId)."<br>";
//            echo $ca->getData('username', $authId)."<br>";
//            echo $price .'円'."<br>";
            
            $file_name = isset($_GET['file_name']) ? $_GET['file_name'] : NULL;
            ?>

            <table class="table table-form">
                <colgroup>
                    <col class="cth">
                    <col class="ctd">
                </colgroup>
            	<tbody>
				<tr>
					<th>レポート名</th>
                    <td><?php echo get_the_title(); ?></td>
                </tr>
                <tr>
					<th>ダウンロードファイル名</th>
                    <td><?php echo $file_name; ?></td>
                </tr>
                <tr>
					<th>氏名</th>
                    <td><?php echo $ca->getData('nick_name', $authId); ?></td>
                </tr>
                <tr>
					<th>メールアドレス</th>
                    <td><?php echo $ca->getData('username', $authId); ?></td>
                </tr>

                <?php if(! $isHistoryDl) { ?>
                <tr>
					<th>料金</th>
                    <td><?php echo $price .'円'; ?></td>
                </tr>
                <?php } ?>
				</tbody>
            </table>
            
            
            <?php
            
            
            $path = ABSPATH . "/this_zip/" . $file_name;
            $action = get_template_directory_uri() . "/inc/handle-file/dl-zipfile.php";
            //$action = realpath(dirname( __FILE__ ));
            
            $price = str_replace(',', '', $price);
            
            //ThankYouページに飛ばさない時は actionを$actionにする
            ?>

            <form method="post" action="<?php echo $action; ?>">
            	<input type="hidden" name="zip_file" value="<?php echo $file_name; ?>">
                <input type="hidden" name="user_id" value="<?php echo $authId; ?>">
                <input type="hidden" name="report_id" value="<?php echo get_the_id(); ?>">
                <input type="hidden" name="price" value="<?php echo $price; ?>">
				<input type="hidden" name="toThank" value="1">
            	<input type="submit" name="sub" value="このファイルをダウンロードする">
            </form>

            <?php
//            	global $wpdb;
//                $postdata['user_id'] = 67;
//        	$userdata = $wpdb -> get_row("SELECT * FROM auth WHERE id = $postdata[user_id]", ARRAY_A);
//            print_r($userdata);
//            global $wpdb;
//            $table_name = 'purchase_repo';
//        
//            $purchaseObj = $wpdb -> get_row("SELECT * FROM $table_name WHERE user_id = 67 AND report_id = 80", OBJECT);
//            echo count($purchaseObj);
//            
//            if(!$purchaseObj) print_r($purchaseObj);
//            
//            
//            if(count($purchaseObj)) echo 'got data';
//            else echo 'No data';
                
            ?>



	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php //_s_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
