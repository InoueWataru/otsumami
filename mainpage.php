<?php
session_start();
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
//PDOオブジェクトの生成(DB接続)
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


$sql = "CREATE TABLE IF NOT EXISTS otsumami"//テーブル作成
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "reshi TEXT,"
. "zai TEXT,"
. "tsu TEXT,"
. "pass char(12)"
.");";
$stmt = $pdo->query($sql);


if(!empty($_POST["name"]) && !empty($_POST["reshi"]) && !empty($_POST["zai"]) && !empty($_POST["tsu"]) && !empty($_POST["pass"]) && empty($_POST["lognumber"]) && isset($_POST["Ssubmit"])){ //入力
    $name=$_POST["name"];
    $reshi=$_POST["reshi"];
    $zai=$_POST["zai"];
    $tsu= $_POST["tsu"];
    $pass=$_POST["pass"];

    $sql = $pdo -> prepare("INSERT INTO otsumami (name, reshi, zai, tsu, pass) VALUES (:name, :reshi, :zai, :tsu, :pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':reshi', $reshi, PDO::PARAM_STR);
    $sql -> bindParam(':zai', $zai, PDO::PARAM_STR);
    $sql -> bindParam(':tsu', $tsu, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

    $sql -> execute();
}
    
 //削除機能
 if(isset($_POST["Dsubmit"])){
    $Dpass=$_POST["Dpass"];
    $Did =$_POST["delete"];//フォーム内の投稿番号とパスワードを定義


    $sql = 'SELECT * FROM otsumami WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $Did, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $check = $row["pass"];
    
            if($check == $Dpass){//パスワード一致したら
                $sql = 'delete from otsumami where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $Did, PDO::PARAM_INT);
                $stmt->execute();
            }	
        }	
}


	//編集機能準備
if(isset($_POST["Esubmit"])){
    $Eid=$_POST["edit"];
    $Epass=$_POST["Epass"];
    $sql = 'SELECT * FROM otsumami';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($row["id"]==$Eid && $row["pass"]==$Epass){
            $Elognum=$row["id"];
            $Ename=$row["name"];
            $Ereshi=$row["reshi"];
            $Ezai=$row["zai"];
            $Etsu=$row["tsu"];
            $Epassword=$row["pass"];
        }
    }
}

//編集機能
if(!empty($_POST["name"]) && !empty($_POST["reshi"]) && !empty($_POST["zai"]) && !empty($_POST["tsu"]) && !empty($_POST["pass"]) && isset($_POST["lognumber"]) && isset($_POST["Ssubmit"])){
    $Eid2=$_POST["lognumber"];
    $name=$_POST["name"];
    $reshi=$_POST["reshi"];
    $zai=$_POST["zai"];
    $tsu= $_POST["tsu"];
    $pass=$_POST["pass"];

    $sql = 'UPDATE otsumami SET name=:name,reshi=:reshi,zai=:zai,tsu=:tsu,pass=:pass WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':reshi', $reshi, PDO::PARAM_STR);
    $stmt->bindParam(':zai', $zai, PDO::PARAM_STR);
    $stmt->bindParam(':tsu', $tsu, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':id', $Eid2, PDO::PARAM_INT);
    $stmt->execute();
}




?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>おつまみ一覧</title>
    </head>
<body>
  <h1>おつまみ一覧へようこそ！</h1>
      <p>ここではあなたの気分に合わせて気になったおつまみを検索できるようにする予定！
      <br>当面はみなさんの自慢のおつまみを投稿してください </p>
      ログアウトは<a href='logout.php'>こちら</a><br><br>


      <form action="" method="post">
            名前
            <input type="txt" name="name" size="5" placeholder="名前" value="<?php if(isset($Ename)){echo $Ename;}else{echo $_SESSION["name"];} ?>"> <br>
            レシピ名
            <input type="txt" name="reshi" size="10" placeholder="レシピ名" value="<?php if(isset($Ereshi)){echo $Ereshi;}?>"><br>
            材料
            <TEXTAREA name="zai"  placeholder="材料"><?php if(isset($Ezai)){echo $Ezai;}else{echo "・";} ?></TEXTAREA><br>
            作り方
            <TEXTAREA name="tsu" rows="4" cols="40" placeholder="作り方"><?php if(isset($Etsu)){echo $Etsu;}else{echo "1.";} ?></textarea> <br>
            パスワード
            <input type="txt" name="pass" size=5 placeholder="パスワード" value="<?php if(isset($Epassword)){echo $Epassword;} ?>"> <br>
            <input type="submit" name="Ssubmit">
                       
           
            <br>

            <input type="hidden" name="lognumber" placeholder="見えません" value="<?php if(isset($Elognum)){echo $Elognum;} ?>">
            <input type="txt" name="edit" size="5" placeholder="編集番号">
            <input type="txt" name="Epass" size="5" placeholder="パスワード">
            <input type="submit" name="Esubmit" value="編集">
           <br>
            
            <input type="txt" name="delete" size="5" placeholder="削除番号">
            <input type="txt" name=Dpass size="5" placeholder="パスワード">
            <input type="submit" name="Dsubmit" value="削除"><br><br>
            
                </form>


</body>
    
<?php
	$sql = 'SELECT * FROM otsumami';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo "<hr>";
		echo $row['id'].":".$row['name']."さんのレシピ"."  「".$row['reshi']."」".'<br>'."<hr>";
        echo "材料"."<br>".nl2br($row['zai'])."<hr>";
        echo "作り方"."<br>".nl2br($row['tsu'])."<hr>";
	}


?>




