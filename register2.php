<?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	$sql = "CREATE TABLE IF NOT EXISTS tblogin"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "mail TEXT,"
	. "name char(32),"
	. "pass TEXT"
	.");";
	$stmt = $pdo->query($sql);


	//メールが既に登録されてるか調べる
	if(!empty($_POST["Nmail"]) && !empty($_POST["Nname"]) && isset($_POST["Regi"])){
			//フォームから取得
		$mailadd=$_POST["Nmail"];
   		$name=$_POST["Nname"];

		$sql= "SELECT * FROM tblogin WHERE mail = :mail";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':mail', $mailadd);
		$stmt->execute();
		$member = $stmt->fetch();
            if ($member['mail'] == $mailadd) {
                echo '同じメールアドレスが存在します。';
            }else{
                //登録されてなかったら登録
                $sql = $pdo -> prepare("INSERT INTO tblogin (mail, name) VALUES (:mail, :name)");
                //値を仮止め
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':mail', $mailadd, PDO::PARAM_STR);
                //入力
                $sql -> execute();
                echo "仮登録完了！メールから本登録をお願いします！";
                

                require 'src/Exception.php';
                require 'src/PHPMailer.php';
                require 'src/SMTP.php';
                require 'setting.php';

                // PHPMailerのインスタンス生成
                    $mail = new PHPMailer\PHPMailer\PHPMailer();

                    $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
                    $mail->SMTPAuth = true;
                    $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
                    $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
                    $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
                    $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
                    $mail->Port = SMTP_PORT; // 接続するTCPポート

                    // メール内容設定
                    $mail->CharSet = "UTF-8";
                    $mail->Encoding = "base64";
                    $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
                    $mail->addAddress($mailadd, $name.'さん'); //受信者（送信先）を追加する
                //    $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
                //    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
                //    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
                    $mail->Subject = MAIL_SUBJECT; // メールタイトル
                    $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
                    $body = 'ようこそ、'.$name.'さん！登録はまだ完了してません。'."<br>"."以下のフォームから本登録をどうぞ！"."<br>"."https://tb-220241.tech-base.net/mission_6-2/hon-regi.php";

                    $mail->Body  = $body; // メール本文
                    // メール送信の実行
                    if(!$mail->send()) {
                        echo 'メッセージは送られませんでした！';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } 


            
		}
	}elseif(empty($_POST["Nmail"]) || empty($_POST["Nname"]) && !empty($_POST["submit"])){
		echo "空欄があります";
	}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>仮登録</title>
    </head>
<body>
    <div align="left"><h1 class="midashi_1">仮登録ページ</h1></div>
        <form action="" method="post">   
            <input type="txt" name="Nmail" placeholder="メールアドレス" >
            <input type="txt" name="Nname" size=10 placeholder="名前" >
            <input type="submit" name="Regi" value="新規登録"><br>
            
        </form>

	<p>すでに登録済みの方は<a href='login_form.php'>こちら</a></p>	
</body>
    