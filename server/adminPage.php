
<?php
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database = "music_app_db";

    $connect = mysqli_connect($server_name, $username, $password, $database);

    if (!$connect) { 
        die("[SQL] Error". mysqli_connect_error());
    }


    $insertQuery =  "INSERT INTO music_source_path (musicName, musicPath, author, imgPath, gifPath) VALUES (?, ?, ?, ?, ?)";


    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST["name-music"]) && isset($_POST["path-music"])) {
            $nameMusic = $_POST["name-music"];
            $pathMusic = $_POST["path-music"];
            $author = $_POST["author-music"];
            $imgPath = $_POST["img-path-music"];
            $gifPath = $_POST["gif-path-music"];
            
            $stmt = mysqli_prepare($connect, $insertQuery);
            mysqli_stmt_bind_param( $stmt,"sssss", $nameMusic, $pathMusic, $author, $imgPath, $gifPath);

            $uploadResult = mysqli_stmt_execute($stmt);
            if(!$uploadResult) {
                echo "[SQL] Insert ERROR". mysqli_error($connect);
            }

            echo json_encode(["status"=>true]);
        }
    }

?>