<?php
//0-1.session準備、session_id取得
session_start();

//0−2.外部ファイル読み込み
include("functions.php");

//0-3.sessionIdと一致確認
checkSessionId();

//index.php（登録フォームの画面ソースコードを全コピーして、このファイルをまるっと上書き保存）
$id = $_GET["id"];
// echo $id;


//1.  DB接続します
$pdo = db_con();
  
//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM photo WHERE id=:id");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  queryError($stmt);
}else{
    //1行だけとる
    $row = $stmt->fetch();
}
?>







<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>POSTデータ登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="manage.php">データ一覧</a></div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="post" action="update.php">
  <div class="jumbotron">
   <fieldset>
    <legend>情報の編集</legend>
     <label>title：<input type="text" name="title" value="<?=$row["title"]?>"></label><br>
     <label>タグ：<input type="text" name="tag01" value="<?=$row["tag01"]?>"></label><br>
     <input type="submit" value="送信">
     <input type="hidden" name="id" value="<?=$id?>"> 
    </fieldset>
  </div>
</form>
<!-- Main[End] -->


</body>
</html>
