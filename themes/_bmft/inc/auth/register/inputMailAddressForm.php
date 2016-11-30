<?php
//

?>

<p>登録されているメールアドレスを入力して下さい。<br>パスワードリセット用のリンクを記載したメールを送信します。</p>

    <form id="first-form" method="post" autocomplete="on" name="first-form" action="<?php echo $mf->currentUrl; ?>">
        <table class="table table-form">
            <colgroup>
                <col class="cth">
                <col class="ctd">
            </colgroup>
            <tbody>
                <?php //include_once('RegisterFormMain.php'); ?>

                <tr>
                    <th>メールアドレス（ログインID）</th>
                    <td><input type="email" name="username" value="<?php $mf->eh_esc($mf->sessionOrUserdata('username')); ?>"></td>
                </tr>
            
            </tbody>
        </table>

        <?php
            $hash = hash('sha256', $mf->generateRandomString(8));
        ?>

        <input type="hidden" name="queryHash" value="<?php $mf->eh_esc($hash); ?>" />
        <input type="hidden" name="sz_ticket" value="<?php $mf->eh_esc($sz_ticket); ?>">
        <input type="hidden" name="toReset" value="1" />
        <input type="submit" name="submit" value="送信">
</form>



