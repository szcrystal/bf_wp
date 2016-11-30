<?php
//require_once('mail_form-functions.php');
//require_once(ABSPATH . 'wp-config.php'); //'../../../../../wp-config.php'でも可
//require_once($_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/kaibara/functions.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/sue-blog/wp-load.php');



	global $wpdb;
       
    $charset_collate = $wpdb->get_charset_collate();
        
    $table_name = $wpdb->prefix . 'form_newshop';
    
    $objs = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);
    

	//現在のユーザー
	global $current_user;
	get_currentuserinfo();

	//現在のURL
	$thisUrl = home_url() . $_SERVER['REQUEST_URI'];

?>

<link rel="stylesheet" href="<?php echo plugins_url('style.css', __FILE__); ?>" media="all">
<div class="sz-form">
<h2>出店者募集一覧</h2>
<div>
<table class="table">
    <thead>
        <tr>
        	<th style="min-width:2em;"></th>
            <th style="min-width:4em;">ID</th>
            <th style="min-width:15em;">名前</th>
            <th style="min-width:15em;">メールアドレス</th>
            <th style="min-width:10em;">郵便番号</th>
            <th style="min-width:18em;">住所</th>
            <th style="min-width:6em;">TEL</th>
            <th style="min-width:9em;">屋号</th>
            <th style="min-width:12em;">業種</th>
            <th style="min-width:7em;">経歴</th>
            <th style="min-width:4em;">事業の経営経験の有無</th>
            <th style="min-width:4em;">コンセプト</th>
            <th style="min-width:10em;">主要な取り扱い商品・サービス</th>
            <th style="min-width:4em;">セールスポイント</th>
            <th style="min-width:10em;">希望面積</th>
            <th style="min-width:10em;">予定従業員数</th>
            <th style="min-width:10em;">コメント</th>
            <th style="min-width:2em;"></th>
        </tr>
    </thead>
                      
<tbody>
<?php foreach($objs as $obj) { ?>
<tr>
<td>
	<form method="get" action="">
    	<input type="hidden" name="page" value="newshop">
    	<input type="hidden" name="id" value="<?php echo $obj->id; ?>">
    	<input type="submit" value="詳細"> 
    </form> 
    
    <!--<a href="<?php echo $thisUrl.'/'.$obj->id; ?>" class="btn btn-primary btn-sm center-block">詳細</a>-->
</td>
<td><?php echo $obj->id; ?></td>
<td><?php echo $obj->nick_name; ?></td>
<td><a href="mailto:<?php echo $obj->s_mail_add; ?>"><?php echo $obj->s_mail_add; ?></a></td>
<td><?php echo $obj->postcode; ?></td>
<td><?php echo $obj->address; ?></td>
<td><?php echo $obj->tel_num; ?></td>
<td><?php echo $obj->trade_name; ?></td>
<td><?php echo $obj->work_type; ?></td>
<td><?php echo $obj->history; ?></td>
<td><?php echo $obj->experience; ?></td>
<td><?php echo $obj->concept; ?></td>
<td><?php echo $obj->main_service; ?></td>
<td><?php echo $obj->sales_point; ?></td>
<td><?php echo $obj->hope_size; ?></td>
<td><?php echo $obj->worker_num; ?></td>
<td><?php echo $obj->comment; ?></td>
<td><a href="{{url('dashboard/show-profile/'. $user->id)}}" class="btn">詳細</a></td>

<!--
    <td><a href="{{url('dashboard/admin-edit/'. $user->id)}}" class="btn btn-danger btn-sm center-block">削除</a></td>
-->
  
</tr>
<?php } /* end foreach */ ?>
</tbody>
</table>
</div>


</div>

<?php










