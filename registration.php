<?php
//0-1.session準備、session_id取得
session_start();

//0.外部ファイル読み込み
include("functions.php");

//1.  DB接続します
$pdo = db_con();

//2. データ登録SQL作成
$title = $_POST["title"];
$password = $_POST["password"];

$stmt = $pdo->prepare("INSERT INTO title_table(id,title,password,manager_flg,life_flg) VALUES(NULL, :title, :password, 0, 0)");
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$res = $stmt->execute();

//3. SQL実行時にエラーがある場合
if($res==false){
    queryError($stmt);
}else{
    //4. SESSION IDの取得
    $_SESSION["chk_ssid"]  = session_id();
    $_SESSION["title"] = $_POST["title"];
    var_dump($_SESSION["title"]);
    //5. 画面遷移
    header("Location: index2.php");
}

?>