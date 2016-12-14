<?php
/**
 * SZ-Form PlugIn
 * functions and definitions Class for Plugin
 *
 * 
 */

//error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);

require_once get_template_directory() . "/inc/auth/register/AuthRegisterClass.php";
require_once get_template_directory() . "/inc/download/FileDownloadClass.php";
require_once(get_template_directory() . "/inc/contact/ContactFormClass.php");

class SzForm extends AuthRegister {
	
    public $titleName;

	public function __construct($slug = '') {
        
        if($slug == 'userdata') {
    		parent::__construct($slug);
            $this-> titleName = $this-> arTitleName;
         }
         else if($slug == 'report') {
         	$fd = new FileDownload();
            $this->titleName = $fd -> arTitleName;
         }
         elseif($slug == 'contact') {
         	$cf = new ContactForm('', '', $slug);
         	$this->titleName = $cf -> arTitleName;
         }
        
        
        $this->slug = $slug;
        
        $this->pagedCount = 10;
        $this->arPaged = array();
    	
    }


    public function createAllTable() {
        //$this->createInspectTable();
        //$this->createNewshopTable();
        $this->createContactTable();
        $this->createAdminTable();
    }


	//視察募集用テーブル作成
    public function createInspectTable() {
        global $wpdb; //wp-config.phpを読めば$wpdbが使用できる
        /*
            $wpdb->get_results 一般的なSELECT(object or array)
            $wpdb->get_col 列のSELECT
            $wpdb->get_row　１行取得
        */
        
        //$idNum = 1; //wpのユーザーID
        //$userRow = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID = $idNum");
        //$user_info = get_userdata($idNum);

        $charset_collate = $wpdb->get_charset_collate();
        //echo DB_NAME; //定数も読める
        
        $table_name = $wpdb->prefix . 'form_inspect';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nick_name VARCHAR(50),
            mail_add VARCHAR(100),
            belong VARCHAR(255),
            postcode VARCHAR(20),
            address VARCHAR(255),
            tel_num VARCHAR(20),
            first_date_time VARCHAR(100),
            second_date_time VARCHAR(100),
            people_num VARCHAR(20),
            park_num VARCHAR(20),
            bus VARCHAR(10),
            lunch VARCHAR(10),
            purpose TEXT,
            comment TEXT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        echo $sql ? '視察テーブル成功'."<br>" : '視察テーブル失敗'."<br>";

    }

    //出店者募集用テーブル作成
    public function createNewshopTable() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        //echo DB_NAME; //定数も読める
        
        $table_name = $wpdb->prefix . 'form_newshop';

        //郵便番号は本来はCHAR(8)->8桁固定型
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nick_name VARCHAR(50),
            mail_add VARCHAR(100),
            postcode VARCHAR(20),
            address VARCHAR(255),
            tel_num VARCHAR(20),
            first_date_time VARCHAR(100),
            trade_name VARCHAR(255),
            work_type VARCHAR(255),
            history TEXT,
            experience VARCHAR(10),
            concept TEXT,
            main_service TEXT,
            sales_point TEXT,
            hope_size VARCHAR(20),
            worker_num VARCHAR(20),
            comment TEXT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        echo $sql ? '出店者テーブル成功'."<br>" : '出店者テーブル失敗'."<br>";
    }

    //お問い合わせ用テーブル作成
    public function createContactTable() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'form_contact';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nick_name VARCHAR(50),
            mail_add VARCHAR(100),
            comment TEXT,
            create_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        echo $sql ? 'コンタクトテーブル成功'."<br>" : 'コンタクトテーブル失敗'."<br>";
    }


    //管理者設定用テーブル作成
    public function createAdminTable() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        //echo DB_NAME; //定数も読める
        
        $table_name = $wpdb->prefix . 'form_admin';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            admin_name VARCHAR(100),
            admin_email VARCHAR(255),
            subject_newuser VARCHAR(255),
            subject_download VARCHAR(255),
            subject_contact VARCHAR(255),
            head_newuser TEXT,
            head_download TEXT,
            head_contact TEXT,
            bank_account TEXT,
            foot_common TEXT,
            tax_rate INT(3) DEFAULT '1',
            update_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
        echo $sql ? 'Adminテーブル成功' : 'Adminテーブル失敗';
        
        $dbRet = $wpdb->insert( //return false or row-number
            $table_name,
            array(
                'admin_name' => 'プロジェクトネーム',
                'admin_email' => 'bonjour@frank.fam.cx',
                'subject_newuser' => '件名サンプル　ユーザー新規登録',
                'subject_download' => '件名サンプル 出店者',
                'subject_contact' => '件名サンプル お問い合わせ',
                'head_newuser' => 'ヘッドサンプル ユーザー新規登録',
                'head_download' => 'ヘッドサンプル 出店者',
                'head_contact' => 'ヘッドサンプル お問い合わせ',
                'bank_account' => '銀行',
                'foot_common' => 'フッダーサンプル 共通',
                'update_time' => current_time( 'mysql' ),
            )
        );
        
        echo $dbRet ? 'No'.$dbRet . ' インサート成功' : 'インサート失敗';
    }


    public function updateAdminData() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'form_admin';
        
        //admin_formの先頭行を取得してそのidを取る
        $obj = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1");
        
        //print_r($obj);

        $ret = $wpdb->update(
            $table_name,
            array(
                'admin_name' => $_POST['admin_name'],
                'admin_email' => $_POST['admin_email'],
                'subject_newuser' => $_POST['subject_newuser'],
                'subject_download' => $_POST['subject_download'],
                'subject_contact' => $_POST['subject_contact'],
                'head_newuser' => $_POST['head_newuser'],
                'head_download' => $_POST['head_download'],
                'head_contact' => $_POST['head_contact'],
                'bank_account' => $_POST['bank_account'],
                'foot_common' => $_POST['foot_common'],
                'tax_rate' => $_POST['tax_rate'],
                'update_time' => current_time( 'mysql' ),
            ),
            array('id'=>$obj->id)  
        );
        
        return $ret;
        
    }

    public function getAdminData() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'form_admin';
        
        //admin_formの先頭行を取得
        $obj = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1");
        
        return $obj ? $obj : '';
    }
    
    //DBから全てのデータを取得
    public function getAllObject($tableNameArg) {
    	global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
		//$table_name = $wpdb->prefix . 'form_' . $this->slug;
    	$table_name = $tableNameArg;
        
        $allObjs = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);
        
        //ページネーションの実装をここで DBから取得のデータオブジェクトのトータル個数がpagedCountを超えていればarray_chunkする
        if(count($allObjs) > $this->pagedCount) {
        	$this->arPaged = array_chunk($allObjs, $this->pagedCount);
            
            if(isset($_GET['paged']) && $_GET['paged'] > 1) {
            	return $this->arPaged[$_GET['paged']-1];
            }
            else {
            	return $this->arPaged[0];
            }
        }
        else {
        	return $allObjs;
        }
    }
    
    
    /* ページネーションのリンク書き出し */
    public function setPagenation() {
        
        /*getAllObject()で1ページ内に表示するカウント（pagedCount）を超えていれば$this->arPagedが取得されるので、
        取得されていればページネーションを表示する*/
        if(isset($this->arPaged)) { 
            
            $n = 0;
            $array = array();
            
            $selfFormat = '<li class="current">%d</li>';
            $linkFormat = '<li><a href="'. admin_url('admin.php?page=%1$s&paged=%2$d') .'">%2$d</a></li>';
            
            while($n < count($this->arPaged)) {
            	if(isset($_GET['paged']) && $_GET['paged'] > 1) { //2ページ目以降
                	if($_GET['paged'] == ($n+1))
                		$array[] = sprintf($selfFormat, ($n+1));
                    else 
                    	$array[] = sprintf($linkFormat, $this->slug, ($n+1));
                }
                else { //1ページ目の時
                	if($n == 0) 
                    	$array[] = sprintf($selfFormat, ($n+1));
                    else 
                    	$array[] = sprintf($linkFormat, $this->slug, ($n+1));
                }
                
                $n++;
            }
        	
            echo '<ul>'."\n" . implode("\n", $array) . "\n</ul>\n";
        }
    }
    
    //DBから全てのデータを取得
    public function getAllObjForCsv($tableNameArg) {
    	global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
		//$table_name = $wpdb->prefix . 'form_' . $this->slug;
    	$table_name = $tableNameArg;
        
        $allObjs = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);
        
        $csvArray = array();
        
        $csvArray[0][] = 'ID';
        
        foreach($this->titleName as $key => $val) :
            if($val != '' && $key != 'password') { //select_first_m やselect_second_d 項目の回避 DBに登録される値はないため
                $csvArray[0][] = $val;
            }
        endforeach;
        
        if($table_name == 'auth') {
        	$csvArray[0][] = '登録日';
        }
        
//        if($table_name == 'purchase_repo') {
//        	array_splice($csvArray[0], 3, 0, 'DLレポート名');
//        }
        
        $n = 1;
        foreach($allObjs as $obj) :
        	$arr = array();
            //さらにループさせて各項目を表示
            foreach($obj as $key => $val) {
                if($key == 'create_time') {
                    $val = date('Y年n月j日', strtotime($val));
                }
                
                if($key == 'pay_state') {
                	$val = $val ? '済み' : '未納';
                }
                
                if($key != 'password' && $key != 'hash' && $key != 'reset_hash' && $key != 'update_time') {
                    $arr[] = $val;
                }
            }
        
//            if($table_name == 'purchase_repo') {
//                array_splice($arr, 3, 0, get_the_title($obj->report_id));
//            }
        
            $csvArray[$n] = $arr;
            $n++;
        endforeach;
        
        
        //Output CSV ==================================
        $csvFile = plugin_dir_path(__FILE__) . 'csv/userdata.csv';
        $csvData = '';
        
        function addDouble($value) {
        	$value = trim(str_replace("\n", '', $value));
            //return '"'. $value . '"';
            return $value;
        }
        
        //print_r($objs);
        
        if(! file_exists($csvFile)) {
            $ret = touch($csvFile);
            if(!$ret)
                echo 'ファイルが作成されない。アクセス権などを確認。';
        }
        
        $fp = fopen($csvFile, 'w');
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        foreach($csvArray as $lineAr) {
        	$lineAr = array_map('addDouble', $lineAr);
//            $line = implode(',', $lineAr);
//            $csvData .= $line . "\r\n";
            
            fputcsv($fp, $lineAr);
        }
        
        //$csv_data = mb_convert_encoding ( stream_get_contents($fp) , "Shift-JIS" , 'utf-8' );
        fclose($fp);
        
        
        
        //echo $csvData;
        
//        $fp = fopen($csvFile, 'ab');
//        flock($fp, LOCK_EX);
//        ftruncate($fp, 0);
//        $fw = fwrite($fp, $csvData);
//        fclose($fp);
//        
//        if(! $fw)
//	        echo $fw;
        
        //echo "<br><br>".$fp;
        
        //$dl = plugin_dir_path(__FILE__) . 'download.php';
        
        //echo '<a href="'. plugins_url('download.php',__FILE__) .'">ダウンロード</a>';
        
//        $fp = fopen($dl, 'r');
//        flock($fp, LOCK_EX);
//        ftruncate($fp, 0);
//        fread($fp, filesize($dl));
//        fclose($fp);
        
        
//        $fileSize = filesize($csvFile);
//        $mime = 'application/csv';
//        
//        header('Content-Type: "'. $mime .'"');
//        header('Content-Disposition: attachment; filename="'. $csvFile .'"');
//        header('Content-Transfer-Encoding: binary');
//        header('Expires: 0');
//        header('Content-Length: '. $fileSize);
//        
//        readfile($csvFile);
    }

    

    public function aaa() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'form_admin';
        $obj = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1");
        print_r($obj);
    }






}
/* Class END ******************************************************************** */









