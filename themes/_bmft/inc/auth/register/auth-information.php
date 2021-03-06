<?php 

//require_once('CustomAuthClass.php');
require_once('AuthRegisterClass.php');
//require_once('RegisterFormError.php');
require_once(realpath(dirname(__FILE__)) . '/../../download/FileDownloadClass.php');

//$slug = 'information';
$ar = new AuthRegister($slug); //slugを入れる inspect or newshop or contact
$fd = new FileDownload();

?>

<div id="main-form" class="clear">

	<div class="confFin clear">
    	<h3><?php echo $ar->getData('nick_name', $authId); ?>さんのユーザー情報</h3>

        <a href="/userinfo/edit/" style="float: right;">ユーザー情報を編集する <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
    </div>


    <?php //Object取得
        //echo $authId;
        $objs = $ar->getAllDatas($authId); //echo $mf->format_func($value="\n")
    ?>

        
    <table class="table table-form">
        <colgroup>
            <col class="cth">
            <col class="ctd">
        </colgroup>
        
        <tbody>
            <tr>
                <th><?php $ar->e_('username', 0); ?>（ログインID）</th>
                <td><?php $ar->eh_esc($ar->sessionOrUserdata('username')); ?></td>
            </tr>
			<tr>    
                <th><?php $ar->e_('company_name', 0); ?></th>
                <td><?php $ar->eh_esc($ar->sessionOrUserdata('company_name')); ?></td>
            </tr>
            <tr>    
                <th><?php $ar->e_('department', 0); ?></th>
                <td><?php $ar->eh_esc($ar->sessionOrUserdata('department')); ?></td>
            </tr>
            <tr>    
                <th><?php $ar->e_('nick_name', 0); ?></th>
                <td><?php $ar->eh_esc($ar->sessionOrUserdata('nick_name')); ?></td>
            </tr>
            <tr>
                <th><?php $ar->e_('postcode', 0); ?></th>
                <td><?php $ar->eh_esc($ar->sessionOrUserdata('postcode')); ?></td>
            </tr>

            <tr>
                <th><?php $ar->e_('address', 0); ?></th>
                <td><?php $ar->eh_esc($ar->sessionOrUserdata('address')); ?></td>
            </tr>

            <tr>
                <th><?php $ar->e_('tel_num', 0); ?></th>
                <td><?php $ar->eh_esc($ar->sessionOrUserdata('tel_num')); ?></td>
            </tr>

        </tbody>
    </table>

    <?php
    	global $wpdb;
    	$purObj = $wpdb -> get_results("SELECT * FROM purchase_repo WHERE user_id = $authId", OBJECT);
        
        if($purObj):
        	//echo date('Y年n月d日', strtotime($fd->arTitleName['create_time']));
        ?>

        <h3>購入履歴</h3>
		<table class="table table-form table-float">

        <?php
        $array = array(
        	'report_id' => $fd->arTitleName['report_id'],
            'report_name' => 'レポート名',
            'file_name' => $fd->arTitleName['file_name'],
            'create_time' => $fd->arTitleName['create_time'],
            'price' => $fd->arTitleName['price'],
        	'pay_state' => $fd->arTitleName['pay_state'],
        );

        //print_r($fd->arTitleName);
        ?>
        
            <thead>
            	<tr>
            	<?php
                	foreach($array as $key => $val) {
                    	if($key != 'file_name') {
                    ?>

                        <th class="<?php echo $key; ?>"><?php echo $val; ?></th>

				<?php }
                	} ?>

                <th class="dl-btn">ダウンロード</th>
                </tr>
        	</thead>
        	<tbody>

            <?php
            foreach($purObj as $purVal) { ?>
            	<tr>
            	<?php foreach($array as $key => $val) {
                	if($key == 'create_time')
                    	echo "<td>". date('Y年n月d日', strtotime($purVal->$key)) ."</td>";
                    else if($key == 'report_name')
                    	echo "<td>". get_the_title($purVal->report_id) ."</td>";
                    else if($key == 'pay_state') {
                    	$state = $purVal->$key ? '済' : '未';
                    	echo "<td>". $state ."</td>";
                    }
                    else if($key == 'price') {
                    	echo "<td>" .number_format($purVal->$key)."円</td>";
                        $price = $purVal->$key;
                    }
                    else if($key == 'file_name') {
                    	$file_name = $purVal->$key;
                    }
                    else
	            		echo "<td>" . $purVal->$key . "</td>";

                }
                
                $slug = get_page_slug($purVal->report_id);
                
                
                $path = ABSPATH . "/this_zip/" . $file_name;
                $action = get_template_directory_uri() . "/inc/handle-file/dl-zipfile.php";
                //$action = realpath(dirname( __FILE__ ));
                
                $price = str_replace(',', '', $price);
                
                ?>

                <td>
                <form method="post" action="<?php echo $action; ?>">
                    <input type="hidden" name="zip_file" value="<?php echo $file_name; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $authId; ?>">
                    <input type="hidden" name="report_id" value="<?php echo $purVal->report_id; ?>">
                    <input type="hidden" name="price" value="<?php echo $price; ?>">
                    <input type="submit" name="sub" value="DLする">
                </form>
				</td>

				<!--
                <td>
					<form method="get" action="<?php echo '/report/'.$slug.'/'; ?>" class="clear">
                        <input type="hidden" name="file_name" value="<?php echo $file_name; ?>">
                        <input type="hidden" name="toDl" value=1>
                        <input type="submit" name="sub" value="DLページ">
                    </form>
                </td>
                -->
                </tr>

        <?php } //$purObj foreach
            
        ?>

        </tbody>
        </table>

        <?php endif; ?>


</div>
    
    
    
