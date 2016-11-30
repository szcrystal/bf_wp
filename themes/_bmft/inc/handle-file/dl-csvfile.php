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

	

	$zipFile = 'csv/userdata.csv';
    $fileName = 'site_userdata_'.date('Y-n-j', time()).'.csv';
    
    $fileSize = filesize($zipFile);
    $mime = 'application/zip';
    
    header('Content-Type: "'. $mime .'"');
    header('Content-Disposition: attachment; filename="'. $fileName .'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Content-Length: '. $fileSize);
    
    readfile($csvFile);
    
    exit();


