<?php
/* */

//print_r($_SESSION[$slug]);

?>

<tr>
    <th><?php $mf->e_('title', 0); ?><em>必須</em></th>
    <td>
		<select <?php $mf->e_('title'); ?>>
        	<?php $mf->selectBoxAndSession("--"); ?>
			<?php $mf->selectBoxAndSession("「おいしいを感じる言葉」について"); ?>
            <?php $mf->selectBoxAndSession("「シズルワードに結び付く食べ物」について"); ?>
            <?php $mf->selectBoxAndSession("「飲食するヘルシー」について"); ?>
            <?php $mf->selectBoxAndSession("「スイーツテイスト」について"); ?>
            <?php $mf->selectBoxAndSession("その他のお問い合わせ"); ?>
        </select>
    </td>

</tr>

<tr>
    <th><?php $mf->e_('company_name', 0); ?><em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('company_name'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('company_name')); ?>" />
    </td>
</tr>

<tr>
    <th> <?php $mf->e_('department', 0); ?></th>
    <td>
        <input type="email" <?php $mf->e_('department'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('department')); ?>" />
    </td>
</tr>

<tr>
    <th><?php $mf->e_('nick_name', 0); ?><em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('nick_name'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('nick_name')); ?>" />
    </td>
</tr>

<tr>
    <th> <?php $mf->e_('mail_add', 0); ?><em>必須</em></th>
    <td>
        <input type="email" <?php $mf->e_('mail_add'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('mail_add')); ?>" />
    </td>
</tr>

<tr>
    <th><?php $mf->e_('post_code', 0); ?><em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('post_code'); ?> placeholder="例 000-0000" value="<?php $mf->eh_esc($mf->sessionOrUserdata('post_code')); ?>" />
    </td>
</tr>

<tr>
    <th><?php $mf->e_('address', 0); ?><em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('address'); ?> placeholder="" value="<?php $mf->eh_esc($mf->sessionOrUserdata('address')); ?>" />
    </td>
</tr>

<tr>
    <th><?php $mf->e_('tel_num', 0); ?><em>必須</em></th>
    <td>
        <input type="text" <?php $mf->e_('tel_num'); ?> placeholder="例 00-0000-0000" value="<?php $mf->eh_esc($mf->sessionOrUserdata('tel_num')); ?>" />
    </td>
</tr>


<tr>
    <th><?php $mf->e_('comment', 0); ?></th>
    <td>
        <textarea rows="3" cols="8" <?php $mf->e_('comment'); ?>><?php $mf->eh_esc($mf->sessionOrUserdata('comment')); ?></textarea>
    </td>
</tr>



