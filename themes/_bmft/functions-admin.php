<?php

/**
 * For AdminPage.
 * 管理画面用の関数
 * 
 */


/* Admin ******** */
//投稿ページへ表示するカスタムボックスを定義する
function add_custom_inputbox() {
    add_meta_box( 'dl','レポートファイル（zipにしてアップロード）', 'dlFileCustomField', 'report', 'normal', 'high' ); //postの部分はpost_typeを指定 カスタム投稿でも可能 paka3id
    //add_meta_box( 'shopName','店名', 'shopNameCustomField', 'attachment', 'normal' );
    
//    $path = ABSPATH . "/this_zip/";
//    $file_name = $_FILES['dl_file']['name'];
//    
//   	if(! move_uploaded_file($_FILES['dl_file']['tmp_name'], $path.$file_name))
//   		echo "error";
//    
//    echo "aaa";
//    print_r($_FILES);
//    
//    $format = '<form method="post" action="'. site_url($_SERVER["REQUEST_URI"]) .'" enctype="multipart/form-data">'
//    		 . "<label>ダウンロードファイル</label>\n"
//            . '<input type="file" name="dl_file">'."\n"
//            . '<input type="submit" name="file_submit" value="UPload"></form>';
//            //. $shop_id;
//    
//    echo $format;
    
}
add_action('admin_menu', 'add_custom_inputbox');


//add_filter('attachment_fields_to_edit', 'add_custom_inputbox');
 
//投稿ページに表示されるカスタムフィールド
function dlFileCustomField(){
    $id = get_the_ID();
    //カスタムフィールドの値を取得
    //$shop_id = get_post_meta($id, 'dl_file', true);

    //$objs = new WP_Query( array('post_type'=>'shop','orderby'=>'ID','order'=>'ASC',)); //or $wpdb->getrow()で
    //$objs = $objs->posts; //WP_Queryのオブジェクトのpostsの中にデータがある
       // '<form method="post" action="" enctype="multipart/form-data">'
    
//    $path = "/this_zip/";
//    $file_name = $_FILES['dl_file']['name'];
//    
//   	if(! move_uploaded_file($_FILES['dl_file']['tmp_name'], $path.$file_name))
//   		echo "error";
//    
//    //DBに登録してあるデータ
//    //$shop_name_org = get_post_meta($post_id, 'shop_id', true);
//    echo "aaa";
//    print_r($_FILES);
	
    $dl_file = get_post_meta(get_the_id(), 'dl_file', true);
	$img = '';
    $display = 'none';
    $uri = get_template_directory_uri();
    
    if($dl_file != '') {
    	//$img = '<img class="zipicon" src="'. $uri .'/images/archive.png">';
    	$display = 'block';
    }
    
$format = <<<EOL
<script src="{$uri}/js/fileform.js"></script>
<style>
#del_form {
	
}
.wrap-first > div {
	display: {$display};
}
.zipicon {
	vertical-align:middle;
    margin-right:0.5em;
}
</style>

<div class="wrap-first">
<div>
<img class="zipicon" src="{$uri}/images/archive.png">
<span>{$dl_file}</span>

<form method="post" action="" id="del_form">
<input type="hidden" name="file_name" value="{$dl_file}" id="file_name">
<input type="hidden" name="post_id" value="{$id}">
<input type="submit" name="del_form" value="delete" id="del_sub">
</form>

</div>

<form method="post" action="" enctype="multipart/form-data">
<input type="file" id="dl_file" name="dl_file">
<input type="hidden" id="p_id" name="p_id" value="{$id}">
<input type="submit" name="file_submit" id="file_submit" value="UPload">
</form>

</div>
    


EOL;
    
    echo $format;
}

function outputCF() {

}

//更新処理
/*投稿ボタンを押した際のデータ更新と保存*/
function saveCustomFieldData($post_id){
	$path = ABSPATH . "/this_zip/";
    //入力した値(postされた値)
    $zip_file = isset($_POST["_zip_file"]) ? $_POST["_zip_file"]: 0;
    
//    if(!$zip_file){
//    	unlink($path.'healty-for-drink_2015-2016.zip');
//    }

    
    //$file_name = $_FILES['dl_file']['name'];
    
//   if(! move_uploaded_file($_FILES['dl_file']['tmp_name'], $path.'aaa.zip'))
//   		echo "error";
    
    //DBに登録してあるデータ
    //$shop_name_org = get_post_meta($post_id, 'shop_id', true);
    //echo "aaa";
    //print_r($_FILES);
    
//    if($dl_file){
//      update_post_meta($post_id, 'dl_file', $file_name);
//    }
//    else {
//      delete_post_meta($post_id, 'dl_file', $file_name);
//    }
}
//入力したデータの更新処理
add_action('save_post', 'saveCustomFieldData');
//add_action('edit_attachment', 'saveCustomFieldData'); //attachment用のsave_post listモードとgridモード両方に効く
//http://wordpress.stackexchange.com/questions/116877/save-post-not-working-with-attachments

//add_filter( 'attachment_fields_to_save', 'saveCustomFieldData', 10, 1 ); //listモードには無効ぽい Gridモードで効くはずだがうまくいかない






