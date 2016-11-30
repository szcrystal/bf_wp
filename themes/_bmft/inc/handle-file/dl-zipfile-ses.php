<?php
//echo ABSPATH;
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);


//require_once('../../../wp-config.php'); //'../../../../../wp-config.php'でも可 >>wp-configを読まなくても$wpdbが使える


/******/
//このdownload.phpに対してリンクを貼るか、formのactionに指定でダウンロードできる
//ダウンロード前にcsv作成のメソッドを入れても可能:
//require_once 'SzFormClass.php';
//$szform = new SzForm();
//$szform -> getAllObjForCsv('auth');

session_start();

	$dir = $_SERVER['DOCUMENT_ROOT'] . "/this_zip/"; // ルート前にするなら -> "/../this_zip/"
    $zipFile = isset($_SESSION['dl-finish']['zip_file']) ? $_SESSION['dl-finish']['zip_file'] : NULL;
    $dir = $dir . $zipFile;
    
    //print_r($_SESSION);
    
    $fileSize = filesize($dir);
    $mime = 'application/zip';
    
    header('Content-Type: "'. $mime .'"');
    header('Content-Disposition: attachment; filename="'. $zipFile .'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Pragma: no-cache');
    header('Content-Length: '. $fileSize);
    
    readfile($dir);
    

    
// --------------------------------------------

//$directory = realpath(dirname( __FILE__ )) . "/../auth/CustomAuthClass.php";
//echo $_SERVER['DOCUMENT_ROOT'];
require_once(realpath(dirname( __FILE__ )) . "/../auth/CustomAuthClass.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-config.php");

	
    $ca = new CustomAuth();
    
    $user_id = isset($_SESSION['dl-finish']['user_id']) ? $_SESSION['dl-finish']['user_id'] : NULL;
    $price = isset($_SESSION['dl-finish']['price']) ? $_SESSION['dl-finish']['price'] : 0;
    $report_id = isset($_SESSION['dl-finish']['report_id']) ? $_SESSION['dl-finish']['report_id'] : NULL;
    //$user_id = 23;
    $ud = $ca->getAllDatas($user_id);
    
    $setArr = array(
    	'user_id' => $user_id,
        'report_id' => $report_id,
        'user_name' => $ud['nick_name'],
        'user_email' => $ud['username'],
        'file_name' => $zipFile,
        'price' => $price,
        'pay_state' => 0,
        'create_time' => current_time( 'mysql' ),
    );
    
    global $wpdb;
        
    $table_name = 'purchase_repo';
    
    $dbRet = $wpdb->insert( //return false or row-number
        $table_name,
        $setArr //Array
    );
    
	$_SESSION['dl-finish'] = array();
        
    //DBInsert失敗時でもadminとuserへのメールは送信させ、自分宛にのみメールを送る
    if(! $dbRet) {
        echo "DB Failed";
    }
    else {
        echo "DB Success";
    }
    
    
    //exit();


