<?php
//0-1.session準備、session_id取得
session_start();

//0−2.外部ファイル読み込み
include("functions.php");

//0-3.sessionIdと一致確認
checkSessionId();

//入力チェック(受信確認処理追加)
if(
  !isset($_POST["id"]) || $_POST["id"]=="" || 
  !isset($_POST["title"]) || $_POST["title"]=="" || //先に入れ物があるか確認、後で空チェック。この順番でないとエラー
  !isset($_POST["tag01"]) || $_POST["tag01"]=="" 
){
  exit('ParamError');
}

//1. POSTデータ取得
$id = $_POST["id"];
$title   = $_POST["title"];
$tag01  = $_POST["tag01"];


//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE photo SET title=:title, tag01=:tag01 WHERE id=:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':tag01', $tag01, PDO::PARAM_STR);
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header("Location: manage.php");
  exit;
}
?>

