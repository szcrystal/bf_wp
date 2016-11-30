<?php 

require_once('register/AuthRegisterClass.php');
$slug = 'login';
$mf = new AuthRegister($slug);

$sz_ticket = md5(uniqid(mt_rand(), TRUE));
$_SESSION[$slug]['sz_ticket'][] = $sz_ticket;

?>
    
    <div class="head-form clear">

        <form method="post" autocomplete="on" name="first-form" action="">
            <table class="table table-form login-table">
                <colgroup>
                    <col class="cth">
                    <col class="ctd">
                </colgroup>
                <tbody>
                    <?php
                    	include('loginForm.php'); ?>
                
                </tbody>
            </table>

            <input type="hidden" name="sz_ticket" id="sz_ticket" value="<?php $mf->eh_esc($sz_ticket); ?>" />
            <!-- <input type="hidden" name="toLogin" value="1" /> -->
            <input type="hidden" name="fromHead" value="1" />
            <input type="submit" name="submit" value="ログイン">
        </form>

</div>
    
    
    
