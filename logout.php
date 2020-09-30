<?php
session_start();

$_SESSION=array();
session_destroy();

?>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_6-2</title>
    </head>
<body>
ログアウトしました<br>
再ログインは<a href=login_form.php>こちら！<a>
</body>
</html>
