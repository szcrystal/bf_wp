<?php
//require_once('mail_form-functions.php');
//require_once(ABSPATH . 'wp-config.php'); //'../../../../../wp-config.php'でも可
//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/kaibara/functions.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/sue-blog/wp-load.php');


require_once('SzFormClass.php');


global $wpdb; //wp-config.phpを読めば$wpdbが使用できる
$slugname = isset($_GET['page']) ? $_GET['page'] : NULL;


$szform = new SzForm($slugname);


/*
    $wpdb->get_results 一般的なSELECT(object or array)
    $wpdb->get_col 列のSELECT
    $wpdb->get_row　１行取得
*/

//$idNum = 1; //wpのユーザーID
//$userRow = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID = $idNum");
//$user_info = get_userdata($idNum);

$charset_collate = $wpdb->get_charset_collate();
//$table_name = $wpdb->prefix . 'form_' . $slugname; //kbr_form_inspect など..


if($slugname == 'userdata') {
    $h_title = '会員一覧';
    $table_name = 'auth';
    $table_purchase = 'purchase_repo';
    //$mf = new AuthRegister('', '', $slugname);
}
elseif($slugname == 'report') {
    $h_title = 'DLレポート一覧';
    $table_name = 'purchase_repo';
}
else {
    $h_title = 'お問い合わせ一覧';
    $table_name = $wpdb->prefix . 'form_contact';
    //$mf = new ContactForm('', '', $slugname);
}



//現在のユーザー
//global $current_user;
//get_currentuserinfo();

$admin_user = wp_get_current_user();
$current_user = $admin_user -> display_name;
//echo $current_user;

//現在のURL
$thisUrl = admin_url('admin.php?page=userdata');

?>

<link rel="stylesheet" href="<?php echo plugins_url('style.css', __FILE__); ?>" media="all">
<div class="sz-form">

<?php
	?>

<?php 
	//Allデータの表示 --------------------------------------------
 
    //$objs = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);

    if(isset($_GET['u_s'])) { //検索ワードがあるなら
    	$search = $_GET['u_s'];
    	$search = str_replace('　', ' ', $search);
        
        $search = explode(' ', $search);
        //print_r($search);
        
        $arr = $szform->titleName;
        
        $keyArray = array('id');
        //$keyArray[] = 'id'; //array_unshift($keyArray,'id');
        foreach($arr as $key => $val) {
        	if($key != 'password')
        		$keyArray[] = $key;
        }
        
        //sql構文のWHERE以降の文字列を作る
        $sql_all = array();
    
        foreach($search as $searchVal) {
            $searchVal = '\'%'. $searchVal . '%\''; //値にクォーテーションをつける必要がある
            
            $sql = array();
            foreach($keyArray as $keyVal) {
                $sql[] = $keyVal . ' LIKE '. $searchVal; //id=valの組み合わせをkeyごとに配列に入れる
            }
        
            $sql_st = implode(' OR ', $sql); //( id=val OR name=val OR mai=val)の塊を作る
            $sql_st = '('.$sql_st .')';
            $sql_all[] = $sql_st;
        
        }
    
        $query = implode(' AND ', $sql_all);
        //echo $sqls;
    	/* 最終的に以下の構文になる
        (id='山形県' OR username='山形県' OR company_name='山形県' OR department='山形県' OR nick_name='山形県' OR postcode='山形県' OR address='山形県' OR tel_num='山形県' OR auth_paystate='山形県')
         AND (id='4' OR username='4' OR company_name='4' OR department='4' OR nick_name='4' OR postcode='4' OR address='4' OR tel_num='4' OR auth_paystate='4')
        */

        $objs = $wpdb->get_results("SELECT * FROM $table_name WHERE $query", OBJECT);

    }
    else {
	    $objs = $szform ->getAllObject($table_name);

    }
?>

<h2><?php echo $h_title; ?></h2>
<div>

	<form method="get" action="<?php echo admin_url('admin.php'); ?>">
    	<input type="hidden" name="page" value="report">
		<input type="text" name="u_s" value="<?php echo @$_GET['u_s']; ?>" placeholder="Search...">
        <input type="submit" name="us_sub" value="検索">
    </form>

	<div class="pagenation clear">
		<?php $szform->setPagenation(); ?>
    </div>
    
    <table class="table wp-list-table widefat fixed striped pages">
        <thead>

            <!--
            <tr>
            	<th></th>
            	<th>ID</th>
                <th>レポートID</th>
                <th>DLレポート名</th>
				<th>会員ID</th>
                <th>氏名</th>
                <th>メールアドレス</th>
                <th>ファイル名</th>
                <th>料金</th>
                <th>お支払い状況</th>
                <th>DL日</th>
                <th></th>
            </tr>
            -->

        	</thead>
        <tbody>
            <tr>
                <th></th>
                <th class="main_id">ID</th>
                <?php
                
                //$csvArray[0][] = 'DLレポート名';
                
                foreach($szform->titleName as $key => $val) :
                	if($val != '' && $key != 'password') { //select_first_m やselect_second_d 項目の回避 DBに登録される値はないため
                    	echo '<th class="'.$key.'">' . $val .'</th>';
                        $csvArray[0][] = $val;
                	}
                endforeach; 
                ?>
                
            	<th></th><?php /* 詳細ボタン用 */ ?>
                <?php //$csvArray[0][] = '登録日'; ?>


    	<tbody>

            <?php
            	//$n = 1;
                foreach($objs as $obj) : ?>
            <tr>
                <td>
                	<a href="<?php echo $thisUrl . '&id=' .$obj->user_id; ?>" class="btn">詳細</a>
                </td>
                <td><?php echo $obj->id; ?></td>

                <!--
                <td><?php echo $obj->id; ?></td>
                <td><?php echo $obj->report_id; ?></td>
                <td><?php echo get_the_title($obj->report_id);?></td>
                <td><?php echo $obj->user_id; ?></td>
				<td><?php echo $obj->user_name; ?></td>
                <td><?php echo $obj->user_email; ?></td>
                <td><?php echo $obj->file_name; ?></td>
                <td><?php echo $obj->price; ?></td>
                <td><?php echo $obj->pay_state ? '<span class="mosgreen">済み</span>' : '<span class="orange">未納</span>'; ?></td>
                <td><?php echo date('Y年n月j日', strtotime($obj->create_time)); ?></td>
				-->

                <?php
                	//$arr = array();
                	//さらにループさせて各項目を表示
                    foreach($obj as $key => $val) {
                        if($key == 'username' || $key == 'user_email') {
                            $val = '<a href="mailto:'.$val.'">' .$val .'</a>';
                        }
                        elseif($key == 'pay_state') {
                        	$val = $val ? '<span class="mosgreen">済み</span>' : '<span class="orange">未納</span>';
                        }
                        elseif($key == 'create_time') {
                            $val = date('Y年n月j日 H:i', strtotime($val));
                        }
                        
                        if($key != 'id' && $key != 'password' && $key != 'hash' && $key != 'reset_hash' && $key != 'update_time') {
	                        echo "<td>".nl2br($val)."</td>";
                            //$arr[] = $val;
                        }
                    }
                    
                    //$csvArray[$n] = $arr;
                    //$n++;
                ?>

                <td>
                    <a href="<?php echo $thisUrl . '&id=' .$obj->user_id; ?>" class="btn">詳細</a>
                </td>
            </tr>
            <?php endforeach; ?>
            
        </tbody>
	</table>
    
    <div class="pagenation clear">
		<?php $szform->setPagenation(); ?>
    </div>
</div>

<?php if(! isset($_GET['u_s'])) { ?>
<form method="post" action="" style="margin-top: 2em; font-size: 1.1em;">
	<input type="hidden" name="csvDl" value="1">
	<input type="submit" value="CSV DownLoad">
</form>

<?php
}
	$csvDl = isset($_POST['csvDl']) ? $_POST['csvDl'] : NULL;
	if($csvDl) {
    	
		$szform->getAllObjForCsv($table_name);
    	?>
		<script>
			//jQuery(document).ready(function(){
            location.href = "<?php echo plugins_url('download.php', __FILE__); ?>";
            //});
        </script>
    <?php
    }
    
    //リンクを張る場合だと、あらかじめcsvデータ・ファイルを作成しておかなければならない、だったか
    //echo '<a href="'. plugins_url('download.php',__FILE__) .'">csvをダウンロード</a>';
    
    //form postでactionにdownload.phpを指定する方法もあり
    
    //echo date('Y年n月j日', time());
	
?>
<?php //endif; ?>

</div>


