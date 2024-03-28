<?php
include '../database/connect.php';
include '../library/library.php';

// folder upload
$folder = "\\music\\";

// cập nhật đường dẫn ở database
function updateMusicSource($status, $type, $newSrc, $id)
{
    $sqlUpdateImg = "UPDATE music_source_path SET imgPath = ? WHERE id = ?";
    $sqlUpdateMusic = "UPDATE music_source_path SET musicPath = ? WHERE id = ?";
    if ($status) {
        if ($type == 'img') {
            return query($sqlUpdateImg, [$newSrc, $id], $GLOBALS['connect'])['stmt'];
        } else {
            return query($sqlUpdateMusic, [$newSrc, $id], $GLOBALS['connect'])['stmt'];
        }
    }
}

// xóa file cũ
function deleteFile($type, $musicID)
{
    // query tìm file và music liên kết 
    $sql_find_source = "SELECT musicPath, imgPath FROM music_source_path WHERE id = ?";
    $sql_find_same_source_img = "SELECT id FROM music_source_path WHERE imgPath = ?";
    $sql_find_same_source_music = "SELECT id FROM music_source_path WHERE musicPath = ?";
    $result = query($sql_find_source, [$musicID], $GLOBALS['connect'])['result'][0];

    // folder chứa file
    $path = realpath(dirname(getcwd())) . $GLOBALS['folder'];

    if ($type == 'img' && !empty($result['imgPath'])) {
        if (query($sql_find_same_source_img, [$result['imgPath']], $GLOBALS['connect'])['numRow'] == 1) {
            // kiểm tra xem nếu có 2 nguồn cùng sữ dụng 1 source thì sẽ không xóa
            $fileName = explode('/', $result['imgPath'])[2];

            if (file_exists($path . 'img\\' . $fileName) && unlink($path . 'img\\' . $fileName)) {
                echo '<p style="color: green;">[DELETE] Successfully deleted old files</p>';
                return true;
            }
        }
        echo '<p style="color: red;">[DELETE] Deleting the old file failed or the file does not exist</p>';
        return false;

    } else
        if ($type == 'audio' && !empty($result['musicPath'])) {
            if (query($sql_find_same_source_music, [$result['musicPath']], $GLOBALS['connect'])['numRow'] == 1) {

                $fileName = explode('/', $result['musicPath'])[1];

                if (file_exists($path . $fileName) && unlink($path . $fileName)) {
                    echo '<p style="color: green;">[DELETE] Successfully deleted old files</p>';
                    return true;
                }
            }
            echo '<p style="color: red;">[DELETE] Deleting the old file failed or the file does not exist</p>';
            return false;
        } else {
            echo '<p style="color:red;">[DELETE ERROR]</p>';
            return false;
        }
}

// tải file lên 
function uploadFile($name, $type)
{
    $file = $_FILES[$name];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    $path = realpath(dirname(getcwd())) . $GLOBALS['folder'];

    if ($type == 'img') {
        $path = $path . "img\\";
    }

    if ($fileError === UPLOAD_ERR_OK) {
        $uploadDir = $path;
        $uploadPath = $uploadDir . $fileName;
        move_uploaded_file($fileTmpName, $uploadPath);

        $target = strpos($path . $fileName, $GLOBALS['folder']);
        $relativePath = str_replace('\\', '/', substr($path . $fileName, $target + 1));

        echo '<p style="color:green;">[FILE UPLOADED] successfully at: ' . $relativePath . '</p>';
        return $relativePath;
    } else {
        echo '<p style="color:red;">[FILE UPLOADED ERROR] An error occurred while uploading the file</p>';
        return false;
    }
}


//  route 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_FILES['imageFile']) && isset($_POST['musicID'])) {
        $status_0 = deleteFile('img', $_POST['musicID']);
        $newSrc = uploadFile("imageFile", 'img');
        updateMusicSource($newSrc, 'img', $newSrc, $_POST['musicID']);

    } else if (isset($_FILES['audioFile']) && isset($_POST['musicID'])) {
        $status_0 = deleteFile('audio', $_POST['musicID']);
        $newSrc = uploadFile("audioFile", "audio");
        updateMusicSource($newSrc, 'audio', $newSrc, $_POST['musicID']);
    }
}
?>