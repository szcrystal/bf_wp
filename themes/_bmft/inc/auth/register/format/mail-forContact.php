<?php

//for User
$context_user = <<<EOL

{$nick_name[1]} 様

{$head_contact}


EOL;

//for Mater
$context_master = <<<EOL

「{$siteName}」よりお問い合わせがありました。
送信された内容は下記となります。


EOL;

//共通
$context_common = <<<EOL

--------　登録内容　-----------

■お名前
{$nick_name[1]}


■{$mail_add[0]}
{$mail_add[1]}


■{$comment[0]}
{$comment[1]}



{$foot_common}



EOL;


$context['user'] = $context_user . $context_common;
$context['master'] = $context_master . $context_common;



// END to user mail sentence

//        $context .= $this->format_mail_func($value="\n");
//        $context .= "\n\n\n\n\n";
//        $context .= $this->admin['foot'];
        //$context .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\t\n\t\n\t\n";
