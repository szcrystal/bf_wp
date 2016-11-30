<?php

//for User
$context_user = <<<EOL

{$nick_name[1]} 様

{$head_newuser}


EOL;

//for Mater
$context_master = <<<EOL

「{$siteName}」より新規ユーザー登録がありました。
登録された内容は下記となります。


EOL;

//共通
$context_common = <<<EOL

--------　登録内容　-----------

■{$username[0]}（ユーザーID）
{$username[1]}

■{$company_name[0]}
{$company_name[1]}

■{$department[0]}
{$department[1]}

■{$nick_name[0]}
{$nick_name[1]}

■{$postcode[0]}
{$postcode[1]}

■{$address[0]}
{$address[1]}

■{$tel_num[0]}
{$tel_num[1]}




{$foot_common}



EOL;


$context['user'] = $context_user . $context_common;
$context['master'] = $context_master . $context_common;



// END to user mail sentence

//        $context .= $this->format_mail_func($value="\n");
//        $context .= "\n\n\n\n\n";
//        $context .= $this->admin['foot'];
        //$context .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\t\n\t\n\t\n";
