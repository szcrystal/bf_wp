<?php
require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-load.php');

	$path = $_SERVER['DOCUMENT_ROOT'] ."/this_zip/";
    $file_name = $_POST['file_name'];
    $post_id = $_POST['post_id'];
    
    unlink($path . $file_name);
    
    //$dl_file = isset($_POST['dl_file']) ? $_POST['dl_file']: true;
    //$post_id = 20;
    delete_post_meta($post_id, 'dl_file', $file_name);
    
    //DBに登録してあるデータ
    //$shop_name_org = get_post_meta($post_id, 'shop_id', true);
    //echo '<span style="color:red;">' . $file_name . "</span>";
    
    //print_r($_FILES);
    
