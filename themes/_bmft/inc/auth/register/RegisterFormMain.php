<?php
/* */

//print_r($_SESSION[$slug]);

?>

<tr>
    <th> <?php $mf->e_('username', 0); ?>（ログインID）<em>必須</em></th>
    <td>
        <input type="email" <?php $mf->e_('username'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('username')); ?>" />
    </td>
</tr>
<tr>
    <th><?php $mf->e_('password', 0); ?>（6文字以上）<em>必須</em><?php if(is_page('edit')): ?><br><small>未入力の場合は変更しません</small><?php endif; ?></th>
    <td>
        <input type="password" <?php $mf->e_('password'); ?> value="<?php /*$mf->eh_esc($mf->sessionOrUserdata('password'));*/ ?>" placeholder="6文字以上を入力して下さい" />
    </td>
</tr>
<tr>
    <th><?php $mf->e_('company_name', 0); ?> <em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('company_name'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('company_name')); ?>" />
    </td>
</tr>
<tr>
    <th><?php $mf->e_('department', 0); ?></th>
    <td>
        <input type="text" <?php $mf->e_('department'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('department')); ?>" />
    </td>
</tr>
<tr>
    <th><?php $mf->e_('nick_name', 0); ?> <em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('nick_name'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('nick_name')); ?>" />
    </td>
</tr>
<tr>
    <th><?php $mf->e_('postcode', 0); ?> <em>必須</em></th>
    <td><input type="text" <?php $mf->e_('postcode'); ?> placeholder="例）000-0000" value="<?php $mf->eh_esc($mf->sessionOrUserdata('postcode')); ?>" /></td>
</tr>

<tr>
    <th><?php $mf->e_('address', 0); ?> <em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('address'); ?> value="<?php $mf->eh_esc($mf->sessionOrUserdata('address')); ?>" />
    </td>
</tr>

<tr>
    <th><?php $mf->e_('tel_num', 0); ?> <em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('tel_num'); ?> placeholder="例）00-0000-0000" value="<?php $mf->eh_esc($mf->sessionOrUserdata('tel_num')); ?>" />
    </td>
</tr>

	<input type="hidden" <?php $mf->e_('auth_paystate'); ?> value="<?php $mf->eh_esc($mf->sessionOrUserdata('auth_paystate')); ?>" />





