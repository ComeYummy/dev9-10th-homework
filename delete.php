<?php
//0-1.session準備、session_id取得
session_start();

//0−2.外部ファイル読み込み
include("functions.php");

//0-3.sessionIdと一致確認
checkSessionId();

//データ受け取り
$id = $_GET["id"];

//1.  DB接続します
$pdo = db_con();
  
//２．データ登録SQL作成
$stmt = $pdo->prepare("DELETE FROM photo WHERE id=:id");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  queryError($stmt);
}else{
  //５．index.phpへリダイレクト
  header("Location: manage.php");
  exit();
}

?>