<?php
highlight_file(__FILE__);
if(isset($_GET['code'])){
    if(';' === preg_replace('/[^\W]+\((?R)?\)/', '', $_GET['code'])) {
        eval($_GET['code']);}
    else
        die('nonono');}
else
    echo('please input code');
?>