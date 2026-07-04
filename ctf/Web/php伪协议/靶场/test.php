<?php
highlight_file(__FILE__);
if (isset($_GET['page'])) {
    include $_GET['page'];
} else {
    echo "Welcome to the PHP pseudo-protocol lab!";
}
?>
