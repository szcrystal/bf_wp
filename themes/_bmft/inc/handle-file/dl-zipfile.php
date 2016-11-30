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

//require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-config.php");
require_once(realpath(dirname( __FILE__ )) . "/../download/FileDownloadClass.php");

//session_start();

	$fd = new FileDownload();
    
    //file downLoad
    $fd->fileDL();
    
    //何度もDLした時など、同じデータがpurchase内にあればDBsetしない
    $fd ->sendMailAndSetDB();
    

//	$postdata = $fd->pd;
//	$dir = $_SERVER['DOCUMENT_ROOT'] . "/this_zip/"; // ルート前にするなら -> "/../this_zip/"
//    //$zipFile = isset($_POST['zip_file']) ? $_POST['zip_file'] : NULL;
//	$zipFile = $postdata['zip_file'];
//    $dir = $dir . $zipFile;
//    
//    $fileSize = filesize($dir);
//    $mime = 'application/zip';
//    
//    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE || strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== FALSE) {
//    	$zipFile = mb_convert_encoding($zipFile, 'SJIS-win', 'UTF-8');
//    }
//    
//    header('Content-Type: "'. $mime .'"');
//    header('Content-Disposition: attachment; filename="'. $zipFile .'"');
//    //特殊文字が消えるが文字化けはしない
//    //header('Content-Disposition:attachment; filename*=UTF-8\'\''. rawurlencode($zipFile));
//    header('Content-Transfer-Encoding: binary');
//    header('Expires: 0');
//    header('Pragma: no-cache');
//    header('Content-Length: '. $fileSize);
//    
//    readfile($dir);
    
    


//http://192.168.10.18:8007/wp-content/themes/_bmft/inc/handle-file/dl-zipfile.php
    
    //exit();
    
// --------------------------------------------

//$directory = realpath(dirname( __FILE__ )) . "/../auth/CustomAuthClass.php";
//echo $_SERVER['DOCUMENT_ROOT'];



//require_once(realpath(dirname( __FILE__ )) . "/../auth/CustomAuthClass.php");

//
//	
//    $ar = new CustomAuth();
//    
//    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : NULL;
//    $price = isset($_POST['price']) ? $_POST['price'] : 0;
//    $report_id = isset($_POST['report_id']) ? $_POST['report_id'] : NULL;
//    
//
//    
//    if($postdata['user_id']) : //----------------------
//    
//        //echo $postdata['user_id'];
//    
//    	global $wpdb;
//        $table_name = $fd->table_name;
//    
//        $purchaseObj = $wpdb -> get_row("SELECT * FROM $table_name WHERE user_id = $postdata[user_id] AND report_id = $postdata[report_id]", OBJECT);
//    
//        if(! $purchaseObj) : //データがなければ取得オブジェクトは空なので
//        	$fd ->sendMailAndSetDB();
    
//            $ud = $fd->getAllDatas($postdata['user_id']); //all user data
//            
//            $setArr = array(
//                'user_id' => $postdata['user_id'],
//                'report_id' => $postdata['report_id'],
//                'user_name' => $ud['nick_name'],
//                'user_email' => $ud['username'],
//                'file_name' => $zipFile,
//                'price' => $postdata['price'],
//                'pay_state' => 0,
//                'create_time' => current_time( 'mysql' ),
//            );
//            
//            $dbRet = $wpdb->insert( //return false or row-number
//                $table_name,
//                $setArr //Array
//            );
//    
//            //DBInsert失敗時でもadminとuserへのメールは送信させ、自分宛にのみメールを送る
//            if(! $dbRet)
//                die("DB Failed");
//            else
//                echo "DB Success";


			
    		//$info = $ar->setInfoSendMail($ud['nick_name'], $ud['username'])
			//$ar -> sendMail();


//		
//        endif;
//    
//    
//    
//    endif;
    
    
    //exit();


