<?php 

//<em>必須</em>
?>

<tr>
    <th> <?php $mf->e_('username', 0); ?></th>
    <td>
        <input type="email" <?php $mf->e_('username'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('username')); ?>" />
    </td>
</tr>
<tr>
    <th><?php $mf->e_('password', 0); ?></th>
    <td>
        <input type="password" <?php $mf->e_('password'); ?> value="" />
    </td>
</tr>
<tr>
	<th>次回から自動で<br>ログインする</th>
	<td><input type="checkbox" name="autoLogin"></td>
</tr>
<tr>
	<th></th>
    <td><a href="/reset-passwd/">パスワードを忘れた方は <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></td>
</tr>

    
    
