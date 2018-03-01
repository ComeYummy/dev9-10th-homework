<?php
//0-1.session準備、session_id取得
session_start();

//0−2.外部ファイル読み込み
include("functions.php");

//0-3.sessionIdと一致確認
checkSessionId();

//session変数からtitle取り出し
$title = $_SESSION["title"] ;


//フォームのデータ受け取り
$tag = $_POST ['tag'];
$id = $_POST ['dataId'];
var_dump("title: ".$title);
var_dump("id: ".$id);


//PDOでデータベース接続
try {
    $pdo = new PDO("mysql:host=localhost;dbname=photoBucket_db;charset=utf8","root",""); 
    //XAMPPは最後(password)のrootはいらない。MAMPはいる。
}catch (PDOException $e) {
    exit( 'DbConnectError:' . $e->getMessage());
}

var_dump($tag);

// 実行したいSQL文
$sql = "UPDATE photo SET tag01=:tag01 WHERE id=:id"; 

//MySQLで実行したいSQLセット。プリペアーステートメントで後から値は入れる
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':tag01', $tag, PDO::PARAM_STR); 
$stmt->bindValue(':id', $id, PDO::PARAM_INT); 

//実際に実行
$flag = $stmt->execute();
//$flagに成功失敗のbool値が入る。

//失敗した場合はエラーメッセージ表示
if($flag==false){
    $error = $stmt->errorInfo();
    exit("ErrorQuery:".$error[2]);
}else{
    $url = 'index2.php?title='.$title;
    var_dump($url);
    header ('Location: index2.php');
    exit();
}


?>
