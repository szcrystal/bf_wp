<?php
require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-load.php');

	$path = $_SERVER['DOCUMENT_ROOT'] ."/this_zip/";
    $file_name = $_FILES['file']['name'];
    
    $post_id = isset($_POST['p_id']) ? $_POST['p_id'] : NULL;
    
    if($_FILES['file']['type'] != 'application/zip') {
    	//return array('aaacc','bbbbbc'));
    	echo 'ZIP-Error';
    }
    else {
        if(! move_uploaded_file($_FILES['file']['tmp_name'], $path. $file_name))
            echo 'Error';
        else {
            //$dl_file = isset($_POST['dl_file']) ? $_POST['dl_file']: true;
            update_post_meta($post_id, 'dl_file', $file_name);
            echo $file_name;
        }
    }
    
    //print_r($_FILES);
    
