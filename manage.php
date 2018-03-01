<?php
//0-1.session準備、session_id取得
session_start();

//0−2.外部ファイル読み込み
include("functions.php");

//0-3.sessionIdと一致確認
checkSessionId();

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM photo");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  queryError($stmt);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .= '<div class="menu-datail">'; 

    $view .= '<a href="detail.php?id='.$result["id"].'">'; 
    $view .= '<img src="'.$result["url"].'">'; 
    $view .= '</a>';
    $view .= '&nbsp;';

    $view .= '<a href="detail.php?id='.$result["id"].'">'; 
    $view .= "<p>".$result["id"]."&nbsp;".$result["title"]."&nbsp;".$result["name"]."&nbsp;".$result["tag01"]."&nbsp;".$result["time"]."</p>";
    $view .= '</a>';
    $view .= '&nbsp;';

    $view .= '<a href="detail.php?id='.$result["id"].'">'; 
    $view .= '<p style="color:blue" >[編集]</p>';
    $view .= '</a>';

    $view .= '<a href="delete.php?id='.$result["id"].'">'; 
    $view .= '<p style="color:red" >[削除]</p>';
    $view .= '</a>';

    $view .= '</div>'; 
    }
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>

    <!-- header -->
    <header>
        <div class="header">
            <a href="index.html">
            <img src="img/logo2.png" alt="" class="header-logo">
            </a>
            <ul class="header-title">
                <!-- <li class="header-item">
                <a href="#about">ABOUT</a>
            </li>
            <li class="header-item">
                <a href="#course-navi">COURSE</a>
            </li>
            <li class="header-item">
                <a href="#news">NEWS</a>
            </li>
            <li class="header-item">
                <a href="#access">ACCESS</a>
            </li> -->
            </ul>
        </div>
    </header>

    <div class="space"></div>



    <!-- Main[Start] -->
    <div>
    <div class=""><?=$view?></div>
    </div>
    </div>
    <!-- Main[End] -->

    <!-- 以下JavaScript領域 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- <script src="main.js"></script> -->



</body>

</html>
