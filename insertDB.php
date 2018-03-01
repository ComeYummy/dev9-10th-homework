<?php
//0-1.session準備、session_id取得
session_start();

//0−2.外部ファイル読み込み
include("functions.php");

//0-3.sessionIdと一致確認
checkSessionId();

//session変数からtitle取り出し
$title = $_SESSION["title"] ;

var_dump($_FILES['upfile']);

for ($i=0; $i<count($_FILES['upfile']['name']); $i++) {
    //フォームのデータ受け取り
    $name = $_FILES ['upfile']['name'][$i];
    $type = $_FILES ['upfile']['type'][$i];
    $error = $_FILES ['upfile']['error'][$i];
    $size = $_FILES ['upfile']['size'][$i];
    echo "name: ".$name;

    $msg = null;

    // もし$_FILES['upfile']があって、一時的なファイル名の$_FILES['upfile']が
    // POSTでアップロードされたファイルだったら
    if (isset ( $_FILES ['upfile'] ) && is_uploaded_file ( $_FILES ['upfile'] ['tmp_name'][$i] )) {
        $old_name = $_FILES ['upfile'] ['tmp_name'][$i];
        //  もしuploadというフォルダーがなければ
        if (! file_exists ( $title)) {
            mkdir ( $_SESSION["title"] );
        }
        $new_name = date ( "YmdHis" );
        $new_name .= mt_rand ();
        switch (exif_imagetype ( $_FILES ['upfile'] ['tmp_name'][$i] )) {
            case IMAGETYPE_JPEG :
                $new_name .= '.jpg';
                break;
            case IMAGETYPE_GIF :
                $new_name .= '.gif';
                break;
            case IMAGETYPE_PNG :
                $new_name .= '.png';
                break;
            default :
                header ( 'Location: index2.php' );
                exit ();
        }
        $imageName = basename ( $_FILES ['upfile'] ['name'][$i] );
        echo "imageName: ".$imageName;
        
    

        //  もし一時的なファイル名の$_FILES['upfile']ファイルを
        //  upload/basename($_FILES['upfile']['name'])ファイルに移動したら
        if (move_uploaded_file ( $old_name, $title."/".$new_name )) {
            $msg = $imageName . 'のアップロードに成功しました';
            echo "msg: ".$msg;

            // ファイルの取り込み。データベースへ保存。
            $url = $title."/".$new_name;
            chmod( $url, 0644 );//画像表示権限を与える
            var_dump($url);
            insertDB();

        } else {
            $msg = 'アップロードに失敗しました';
        }
    }


    if(isset($msg) && $msg == true){
        echo '<p>'. $msg . '</p>';
        // echo '<p><img src='.$title."/".$new_name.'></p>';
        // var_dump($_POST["formTitle"]);
    }
}


function insertDB(){
        //PDOでデータベース接続
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=photoBucket_db;charset=utf8","root",""); 
        //XAMPPは最後(password)のrootはいらない。MAMPはいる。
    }catch (PDOException $e) {
        exit( 'DbConnectError:' . $e->getMessage());
    }

    //変数を関数外のグローバル変数を利用
    global $name;
    global $type;
    global $error;
    global $size;
    global $url;
    global $title;
    // var_dump($name);
    // var_dump($url);

    // 実行したいSQL文
    $sql = "INSERT INTO photo(id,url,title,name,type,error,size,time) VALUES(NULL, :url, :title, :name, :type, :error, :size, sysdate())"; 

    //MySQLで実行したいSQLセット。プリペアーステートメントで後から値は入れる
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':url', $url, PDO::PARAM_STR); // 最後の引数はデータの型。INTなら別のもの
    $stmt->bindValue(":title", $title, PDO::PARAM_STR);
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":type", $type, PDO::PARAM_STR);
    $stmt->bindValue(":error", $error, PDO::PARAM_INT);
    $stmt->bindValue(":size", $size, PDO::PARAM_INT);

    //実際に実行
    $flag = $stmt->execute();
    //$flagに成功失敗のbool値が入る。

    //失敗した場合はエラーメッセージ表示
    if($flag==false){
        $error = $stmt->errorInfo();
        exit("ErrorQuery:".$error[2]);
    }else{
        header ('Location: index2.php');
        // exit();
    }
}

?>

