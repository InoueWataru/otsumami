<?php
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

	if(!empty($_POST["Rmail"]) && !empty($_POST["Rname"]) && !empty($_POST["Rpass"]) && isset($_POST["Regi"])){
		$Rmail=$_POST["Rmail"];
		$Rname=$_POST["Rname"];
		$pass=password_hash($_POST["Rpass"], PASSWORD_DEFAULT);

		$sql= "SELECT * FROM tblogin WHERE mail = :mail";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':mail', $Rmail);
        $stmt->execute();
		$member = $stmt->fetch();
		
		if ($member['name'] == $_POST["Rname"]) {
			
			//登録されてなかったら登録
			$sql = $pdo -> prepare("INSERT INTO tblogin (mail, name, pass) VALUES (:mail, :name, :pass)");
			//値を仮止め
			$sql -> bindParam(':name', $Rname, PDO::PARAM_STR);
			$sql -> bindParam(':mail', $Rmail, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			//入力
			$sql -> execute();
			echo "登録完了！";
		}else{
			echo "仮登録時のメールアドレスと名前をご利用ください";
		}
	}elseif(empty($_POST["Rmail"]) || empty($_POST["Rname"]) || empty($_POST["Rpass"]) && isset($_POST["Regi"])){
		echo "空欄があります。";
	}



?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>本登録</title>
    </head>
<body>
    <div align="left"><h1 class="midashi_1">新規登録ページ</h1></div>
        <form action="" method="post">   
            <input type="txt" name="Rmail" placeholder="仮登録のメールアドレス" >
            <input type="txt" name="Rname" size=10 placeholder="仮登録の名前" >
			<input type="txt" name="Rpass" placeholder="パスワード">
            <input type="submit" name="Regi" value="本登録"><br>
            
        </form>

	<p>すでに登録済みの方は<a href='login_form.php'>こちら</a></p>	
</body>
    