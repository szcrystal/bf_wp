<?php

//to Reset mail sentence /* ★ */
$context = <<<EOL

{$nick_name} 様

パスワードをリセットするリンクは下記となります。
24時間以内にクリックをして手続きを進めて下さい。
尚、有効期限の24時間を過ぎますと、手続きが出来なくなりますので
その場合は、再度やり直して下さい。

{$url}


{$foot_common}

EOL;
// END to user mail sentence
