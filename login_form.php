<?php
    session_start();
    
 

	// DB接続設定
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	//PDOオブジェクトの生成(DB接続)
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	$sql = "CREATE TABLE IF NOT EXISTS tblogin"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "mail TEXT,"
	. "name char(32),"
	. "pass TEXT"
	.");";
	$stmt = $pdo->query($sql);

    if(isset($_POST["submit"])){

        $mail=$_POST["mail"];

        $sql= "SELECT * FROM tblogin WHERE mail = :mail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();
        $member = $stmt->fetch();
        //ハッシュチェック
        if(password_verify($_POST["pass"],PASSWORD_DEFAULT)==$member["pass"]){
            //セッション関連
            $_SESSION["id"]=$member["id"];
            $_SESSION["name"]=$member["name"];
            $msg="ログインしました！ようこそ".$_SESSION["name"]."さん。";
            $link="<a href='mainpage.php'>ホーム</a>";

        }else{
            $msg="メールアドレスもしくはパスワードが間違っています。";
        }
    }




?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン</title>
    </head>
<body>
    <div align="left"><h1 class="midashi_1">ログインページ</h1></div>
    
        <form action="" method="post">
            <input type="txt" name="mail" placeholder="メールアドレス" >   
            <input type="txt" name="pass" size=5 placeholder="パスワード" ><br>
            <input type="submit" name="submit" value="ログイン"><br>
        </form>

    <p><?php if(isset($msg)){ echo $msg;} ?></p>
    <p><?php if(isset($link)){ echo $link;} ?></p>
    
    <p>新規登録は<a href='register2.php'>こちら</a></p>

</body>
    