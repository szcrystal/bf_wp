<?php
/* */


?>

<div class="mf-error">
    <p><i class="fa fa-exclamation-circle"></i>ご確認下さい。</p>
    <ul>
    <?php                   
        foreach($errors as $val) {
            echo "<li>" . $val ."</li>";
        }                    
     ?>   
    </ul>
</div>



