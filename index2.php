<?php
//0-1.session準備、session_id取得
session_start();

//0−2.外部ファイル読み込み
include("functions.php");

//0-3.sessionIdと一致確認
checkSessionId();

//session変数からtitle取り出し
$title = $_SESSION["title"] ;

//PDOでデータベース接続
$pdo = db_con();

// 実行したいSQL文
$sql = "SELECT * FROM photo WHERE title =:title ORDER BY id ASC";

//MySQLで実行したいSQLセット。プリペアーステートメントで後から値は入れる
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$flag = $stmt->execute();

if($flag==false){
    $error = $stmt->errorInfo();
    exit("ErrorQuery:".$error[2]);
}else{
	//html全体が含まれている。
}

// 実行したいSQL文（最新順番3つ取得）
$sql_tag = "SELECT * FROM photo WHERE tag01 IS NOT NULL AND tag01 != '' AND title=:title GROUP BY tag01 ORDER BY id ASC";

//MySQLで実行したいSQLセット。プリペアーステートメントで後から値は入れる
$stmt_tag = $pdo->prepare($sql_tag);
$stmt_tag->bindValue(':title', $title, PDO::PARAM_STR);
$flag_tag = $stmt_tag->execute();

if($flag_tag==false){
    $error = $stmt_tag->errorInfo();
    exit("ErrorQuery:".$error[2]);
}else{
	//html全体が含まれている。
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
            <img src="img/icon_23.png" alt="" class="hamburger">
            <p id="title"><?=$title?></p>
            <!-- <img src="img/logo2.png" alt="" class="header-logo"> -->
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
                <div id="fileSelect">
                    <form name="myform" action="insertDB.php?title=<?=$title?>" method="post" enctype="multipart/form-data" class="upload-wrapper" >
                        <input type="file" id="btnUpload" name="upfile[]" accept="image/jpeg" multiple="multiple" style="display:none">
                        <input type="text" id="formTitle" name="formTitle" style="display:none" value="title">
                        <img src="img/upload.png" alt="" class="upload-icon">
                        <input type="submit" value="UPLOAD" class="upload-button" id="upload">
                    </form>
                </div>
            </ul>
        </div>
    </header>

    <div class="space"></div>


    <!-- サイドメニュー -->
    <div class='content'>
        <div class='menu-panel'>
            <div class="menu-wrapper">
                <div class="menu-datail" id="" data-key="">
                    <img src="img/image3.jpg">
                    <p>ALL</p>
                </div>
                <!-- ここにコンテンツを追加 -->
                <?php
			        while($result_tag = $stmt_tag->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div class="menu-datail" id="" data-key="">
                    <img src="<?=$result_tag["url"]?>">
                    <p><?=$result_tag["tag01"]?></p>
                </div>
                <?php
                    // var_dump($result_tag);
                    }
                ?>    
                <div class="menu-datail" id="logout" data-key="">
                    <img src="img/logout.png">
                    <p>LOG OUT</p>
                </div>
                <div class="space"></div>

            </div>
        </div>
    </div>

    <!-- コンテンツ表示画面 -->
    <div class="img-list">
        <!-- サンプル -->
        <div>
                <img class="thumbnail" src="img/gari_oshare.jpg" alt="">
        </div>
        <div>
                <img class="thumbnail" src="img/IMG_0707.jpeg" alt="">
        </div>
        <div>
                <img class="thumbnail" src="img/IMG_1116.jpeg" alt="">
        </div>
        <div>
                <img class="thumbnail" src="img/IMG_4290.jpeg" alt="">
        </div>
        <div>
                <img class="thumbnail" src="img/IMG_4878.jpeg" alt="">
        </div>

        <!-- ココにコンテンツを追加してゆく -->
        <?php
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        ?>
        
        <div>
            <img class="thumbnail" src="<?php echo $result["url"]; ?>" data-id="<?php echo $result["id"]?>" alt="">
        </div>

        <?php 
			}
		?>

    </div>

    <div class="space"></div>

    <!-- 画面下部アップロードボタン -->
    <div class="upload-wrapper2" id="fileSelect2">
    <form name="myform2" action="insertDB.php?title=<?=$title?>" method="post" enctype="multipart/form-data" class="upload-wrapper2" >
            <input type="file" id="btnUpload2" name="upfile[]" accept="image/jpeg" capture="camera" multiple="multiple" style="display:none"> 
            <input type="text" id="formTitle2" name="formTitle" style="display:none" value="title">
            <img src="img/upload.png" alt="" class="upload-icon2">
            <input type="submit" value="UPLOAD" class="upload-button2" id="upload2">
        </form>
    </div>


    <!-- 以下JavaScript領域 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script> 
    

    //サイドメニューコンテンツ追加
        // サイドメニュークリック時の挙動
        $().on("click", function (e) {
            // const t = $(e.target).closest(".menu-detail");
            const t = $(e.target).parent('.menu-datail');
            const k = t.data("key");

        })

            .appendTo(".menu-wrapper");


    


    </script>
    <script src="main.js"></script>



</body>

</html>
