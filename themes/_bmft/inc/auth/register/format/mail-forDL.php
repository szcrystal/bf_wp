<?php

//for User
$context_user = <<<EOL

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　レポート購入完了のお知らせ。
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

{$nick_name} 様

{$head_download}


EOL;

//for Mater
$context_master = <<<EOL

「{$file_title}」よりダウンロードがされました。
ダウンロードされた内容は下記となります。



EOL;

//共通
$context_common = <<<EOL

--------　ダウンロードファイル詳細とお振込先　-----------


■会社名
{$company_name}

■氏名
{$nick_name}

■メールアドレス
{$username}


■購入ファイル
{$file_title}

■購入日
{$dl_time}

■料金
{$price} 円


■振込先
{$bank_account}




{$foot_common}



EOL;


$context['user'] = $context_user . $context_common;
$context['master'] = $context_master . $context_common;



// END to user mail sentence

//        $context .= $this->format_mail_func($value="\n");
//        $context .= "\n\n\n\n\n";
//        $context .= $this->admin['foot'];
        //$context .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\t\n\t\n\t\n";
