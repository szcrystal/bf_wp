<?php
//require_once('mail_form-functions.php');
//require_once(ABSPATH . 'wp-config.php'); //'../../../../../wp-config.php'でも可
//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/kaibara/functions.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/sue-blog/wp-load.php');

//require_once(get_template_directory() . "/inc/auth/register/AuthRegisterClass.php");
//require_once(get_template_directory() . "/inc/contact/ContactFormClass.php");
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
elseif($slugname == 'newshop') {
    $h_title = '出店者募集一覧';
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
$thisUrl = home_url() . $_SERVER['REQUEST_URI'];

?>

<link rel="stylesheet" href="<?php echo plugins_url('style.css', __FILE__); ?>" media="all">
<div class="sz-form">

<?php
	//シングルデータの表示 -------------
	if(isset($_GET['id'])) :
    
//    	echo $_POST['pay_id']."<br><br>";
//        echo $_POST['pay_state'];
    
    	if(isset($_POST['pay_id']) && $_POST['pay_id']) {
        	$state_val = isset($_POST['pay_state']) ? 1 : 0;
        	$wpdb->update(
                $table_purchase,
                array(
                    'pay_state' => $state_val,
                ),
                array(
                	'id' => $_POST['pay_id'],
                )
            );
            
            
            
        }
    
    
    	$g_id = $_GET['id'];
    
    	//会員通常データ
		$singleObj = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $g_id");
    
    if($slugname != 'contact') {
    	//ダブリのないpurchaseデータ
        $payObj = $wpdb -> get_results("SELECT DISTINCT report_id FROM $table_purchase WHERE user_id = $g_id", OBJECT); //DISTINCT report_id ダブルのデータを除外する SELECTに*は指定できない
        //print_r($payObj);
        $payArray = array();
        
        foreach($payObj as $val) {
            $payArray[] = $wpdb -> get_row("SELECT * FROM $table_purchase WHERE user_id = $g_id AND report_id = $val->report_id", OBJECT);
        }
    }
?>

<div class="back">
    <!-- <a href="<?php echo admin_url('admin.php?page='. $slugname); ?>" class="btn"><< 戻る</a> -->
    <a href="#" onclick="javascript:window.history.back(-1); return false;" class="btn"><< 戻る</a>
</div>

<h2><?php echo '会員 '. $singleObj->nick_name . " さんの詳細"; ?></h2>
	<div>
    	<table class="table single-table">
        	<colgroup>
                <col class="cth">
                <col class="ctd">
            </colgroup>
        	<tbody>
            	<tr>
                	<th>ID</th>
                    <td><?php echo $singleObj->id; ?></td>
            	<tr>
            	<?php 
                	//Objectの値をarTitleNameのキーから取得し、arTitleNameの値もキーとして利用し、別の配列に入れてそれをループ表示させる
                	$array = array();
                    foreach($szform->titleName as $key => $val) {
                    	if($val != '' && $key != 'password' && $key != 'hash' && $key != 'reset_hash' && $key != 'update_time') { //select_first_m やselect_second_d 項目の回避 DBに登録される値はないため
                            if($key == 'username') {
                                $singleObj->$key = '<a href="mailto:'.$singleObj->$key.'">' .$singleObj->$key .'</a>';
                            }
                            
                            if($key != 'auth_paystate')
                            	$array[$val] = nl2br($singleObj->$key);
                        }
                	}
                    
                    
                	//ループhtml表示 ---------------
                	foreach($array as $key => $val) {
                    ?>
                        <tr>
                            <th><?php echo $key; ?></th>
                            <td><?php echo $val; ?></td>
                        </tr>
                <?php } ?>
                
                <tr>
                	<th>登録日</th>
                    <td><?php echo date('Y年n月j日', strtotime($singleObj->create_time)); ?></td>
                </tr>

            </tbody>
        </table>


		<h3>購入レポート</h3>
        <?php
        if(count($payArray) > 0) {
        
        
        	if(isset($_POST['repo_id']))
            	echo '<span class="green">ID:'.$_POST['repo_id'].'の入金情報が更新されました</span>';
        ?>
        <table class="table wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th>レポートID</th>
                <th>レポート名</th>
                <th>ファイル名</th>
                <th>DL日</th>
                <th>金額</th>
                <th>入金状況</th>
                <th>入金状況の編集</th>
            </tr>
        </thead>
        <tbody>

        <?php
            $pay_state = array();
            foreach($payArray as $val) { ?>
                <tr>
                <td><?php echo $val->report_id; ?></td>
                <td><?php echo get_the_title($val->report_id); ?></td>
                <td><?php echo $val->file_name; ?></td>
                <td><?php echo date('Y年n月j日', strtotime($val->create_time)); ?></td>
                <td><?php echo $val->price; ?></td>
                <td><?php echo $val->pay_state ? '<span class="mosgreen">済み</span>' : '<span class="orange">未納</span>'; ?></td>
                <td><form method="post" action="">
                    <input type="checkbox" name="pay_state" value="1" <?php echo $val->pay_state ? 'checked="checked"' : ''; ?>>
                    <input type="hidden" name="pay_id" value="<?php echo $val->id; ?>">
                    <input type="hidden" name="repo_id" value="<?php echo $val->report_id; ?>">
                    <input type="submit" name="up_pay" value=" 更　新 ">
                    </form></td>
                </tr>
                
        <?php
                $pay_state[] = $val->pay_state;
            }
                
            $aps = $singleObj->auth_paystate; //authデータの入金状況
            
            //print_r($pay_state);
            if(count($pay_state) > 0) : //まだダウンロードをしていない場合もあるので
                if(in_array(0, $pay_state)) { //pay_stateに0が一つでもあれば、authのstateを未納にする
                    
                    if($aps == '--' || $aps == '済み') {
                        $wpdb->update(
                            $table_name,
                            array(
                                'auth_paystate' => '未納',
                            ),
                            array('id'=>$singleObj->id)
                        );
                    }
                }
                else { //0がなければ、authのstateを済みにする
                    if($aps == '--' || $aps == '未納') {
                        $wpdb->update(
                            $table_name,
                            array( 'auth_paystate' => '済み'),
                            array('id'=>$singleObj->id)
                        );
                    }
                }
            endif;
        ?>

        </tbody>
    </table>

    <?php }
     else {
     	echo "まだダウンロードされていません。";
     }

    ?>

    </div>
    
    <div class="back">
    	<!-- <a href="<?php echo admin_url('admin.php?page='. $slugname); ?>" class="btn"><< 戻る</a> -->
        <a href="#" onclick="javascript:window.history.back(-1); return false;" class="btn"><< 戻る</a>
    </div>

<?php 
	//Allデータの表示 --------------------------------------------
	else : 
    //$objs = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);

    if(isset($_GET['u_s'])) { //検索ワードがあるなら
    	$search = $_GET['u_s'];
    	$search = str_replace('　', ' ', $search);
        
        $search = explode(' ', $search);
        //print_r($search);
        
        $arr = array(
            'username' => 'メールアドレス',
            //'password' => 'パスワード',
            'company_name' => '社名',
            'department' => '所属部署',
            'nick_name' => '氏名',
            'postcode' => '郵便番号',
            'address' => '住所',
            'tel_num' => '電話番号',
            'auth_paystate' => '入金状況',
        );
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
    	<input type="hidden" name="page" value="userdata">
		<input type="text" name="u_s" value="<?php echo @$_GET['u_s']; ?>" placeholder="Search...">
        <input type="submit" name="us_sub" value="検索">
    </form>

	<div class="pagenation clear">
		<?php $szform->setPagenation(); ?>
    </div>
    
    <table class="table wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th></th>
                <th class="main_id">ID</th>
                <?php
                
                
                foreach($szform->titleName as $key => $val) :
                	if($val != '' && $key != 'password') { //select_first_m やselect_second_d 項目の回避 DBに登録される値はないため
                    	echo '<th class="'.$key.'">' . $val .'</th>';
                        $csvArray[0][] = $val;
                	}
                endforeach; 
                ?>
                
                <th class="create_time">登録日</th>
                <th></th> <?php /* 詳細ボタン用 */ ?>
                <?php //$csvArray[0][] = '登録日'; ?>

            </tr>
        </thead>
    	<tbody>

            <?php
            	//$n = 1;
                foreach($objs as $obj) : ?>
            <tr>
                <td>
                	<a href="<?php echo $thisUrl . '&id=' .$obj->id; ?>" class="btn">詳細</a>
                </td>

                <?php
                	//$arr = array();
                	//さらにループさせて各項目を表示
                    foreach($obj as $key => $val) {
                        if($key == 'username' || $key == 'mail_add') {
                            $val = '<a href="mailto:'.$val.'">' .$val .'</a>';
                        }
                        elseif($key == 'auth_paystate') {
                        	if($val == '済み' )
                            	$val = '<span class="mosgreen">済み</span>';
                            elseif($val == '未納')
                            	$val = '<span class="orange">未納</span>';
                        }
                        elseif($key == 'create_time') {
                            $val = date('Y年n月j日', strtotime($val));
                        }
                        
                        if($key != 'password' && $key != 'hash' && $key != 'reset_hash' && $key != 'update_time') {
	                        echo "<td>".nl2br($val)."</td>";
                            //$arr[] = $val;
                        }
                    }
                    
                    
                    
                    //$csvArray[$n] = $arr;
                    //$n++;
                ?>

                <td>
                    <a href="<?php echo $thisUrl . '&id=' .$obj->id; ?>" class="btn">詳細</a>
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
<?php endif; ?>

</div>


