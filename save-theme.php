<?php
if (isset($_POST['theme'])) {
    $theme = $_POST['theme'];
    setcookie('theme', $theme, time() + 365 * 24 * 3600, '/');
    echo 'ok';
}
?>