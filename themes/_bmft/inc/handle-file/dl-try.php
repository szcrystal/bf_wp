<?php

//$directory = realpath(dirname( __FILE__ )) . "/../auth/CustomAuthClass.php";
echo $_SERVER['DOCUMENT_ROOT'];
require_once(realpath(dirname( __FILE__ )) . "/../auth/CustomAuthClass.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-config.php");

//echo 'aaa';
	
    $ca = new CustomAuth();
    
    //$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : NULL;
    $user_id = 23;
    $ud = $ca->getAllDatas($user_id);
    
    $setArr = array(
    	'user_id' => $user_id,
        'user_name' => $ud['nick_name'],
        'user_email' => $ud['username'],
        'file_name' => $ud['username'],
        'price' => $ud['username'],
        'create_time' => current_time( 'mysql' ),
    );
    
    global $wpdb;
        
    $table_name = 'purchase_repo';
    
    $dbRet = $wpdb->insert( //return false or row-number
        $table_name,
        $setArr //Array
    );
        
        //DBInsert失敗時でもadminとuserへのメールは送信させ、自分宛にのみメールを送る
        if(! $dbRet) {
        	echo "DB Failed";
        }
        else {
        	echo "DB Success";
        }
    
    
    
    
    
    
    
    
    
    //exit();


