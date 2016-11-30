<?php
/* */

?>

<p>新しいパスワードを入力して下さい。<br></p>

<form id="first-form" method="post" autocomplete="on" name="first-form" action="<?php echo $mf->currentUrl; ?>">
    <table class="table table-form">
        <colgroup>
            <col class="cth">
            <col class="ctd">
        </colgroup>
        <tbody>
            <tr>
                <th>新しいパスワード</th>
                <td><input type="password" name="password"></td>
            </tr>
        </tbody>
    </table>

    <input type="hidden" name="thisId" value="<?php $mf->eh_esc($u_id); ?>">
    <input type="hidden" name="sz_ticket" value="<?php $mf->eh_esc($sz_ticket); ?>">
    <input type="hidden" name="toNewPasswd" value="1" />
    <input type="submit" name="submit" value="送信">
</form>


