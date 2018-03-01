<?php
session_start();

//0.外部ファイル読み込み
include("functions.php");

//1.  DB接続します
$pdo = db_con();

//2. データ登録SQL作成
$title = $_POST["title"];
$password = $_POST["password"];

$stmt = $pdo->prepare("SELECT * FROM title_table WHERE title=:title AND password=:password");
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$res = $stmt->execute();

//3. SQL実行時にエラーがある場合
if($res==false){
    queryError($stmt);
}

//4. 抽出データ数を取得
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
$val = $stmt->fetch(); //1レコードだけ取得する方法, 配列化する。

//5. 該当レコードがあればSESSIONに値を代入
if( $val["id"] != "" ){
  $_SESSION["chk_ssid"]  = session_id();
  $_SESSION["title"] = $val['title'];
  $_SESSION["manager_flg"] = $val['manager_flg'];
  echo $_SESSION["chk_ssid"];
  echo "title:".$_SESSION["title"];
  echo "manager_flg:".$_SESSION["manager_flg"];

  //管理者フラグの分岐
  if($val['manager_flg']==0){
    header("Location: index2.php");
  }else if($val['manager_flg']!=0){
    header("Location: manage.php");
  }

}else{
  //logout処理を経由して全画面へ
  // header("Location: index.html");
  echo "ERROR!!";
}

exit();
?>

