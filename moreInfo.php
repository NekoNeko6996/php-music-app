<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/moreInfo.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/moreInfo.js" defer></script>
    <title>More Info</title>
</head>

<?php
include 'database/connect.php';
session_start();
if (!isset($_SESSION['token'])) {
    header("Location: home.php");
    exit();
}

function getMusicById($connect, $id)
{
    $stmt = $connect->prepare("SELECT musicName, musicPath, author, imgPath, gifPath, tag FROM music_source_path WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!isset($result)) {
        die("[SQL ERROR] query error");
    }
    return $result;
}

$token = $_SESSION['token'];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $musicResult = getMusicById($connect, $id)[0];
}

function checkImg($src)
{
    if (empty($src)) {
        return 'music/img/default.jpg';
    } else
        return $src;
}
?>

<body>
    <nav>
        <a href="admin.php">Back To Admin Page</a>
    </nav>
    <section>
        <h2>Information</h2>
        <form method="POST" id="update-music-form-data">
            <table cellspacing="20">
                <tr>
                    <td colspan="2">
                        <label for="update-music-name">Music Name</label>
                        <input type="text" name="musicName" id="update-music-name" class="update-input"
                            value="<?php echo $musicResult['musicName'] ?>" placeholder="input here..." />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="update-music-author">Music Author</label>
                        <input type="text" name="musicAuthor" id="update-music-author" class="update-input"
                            value="<?php echo $musicResult['author'] ?>" placeholder="input here..." />
                    </td>
                    <td>
                        <label for="update-music-tag">Music Tag</label>
                        <input type="text" name="update-music-tag" id="update-music-tag" class="update-input"
                            value="<?php echo $musicResult['tag'] ?>" placeholder="input here..." />
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <button type="submit" class="normal-btn" onclick="updateMusicRequest(event)">
                            Update Music
                        </button>
                        <button class="normal-btn delete-btn">Delete Music</button>
                    </td>
                </tr>
            </table>
        </form>

        <!-- upload img -->
        <h2>Image Source</h2>
        <div class="media-upload-container">
            <span class="img-preview">
                <h3>Current</h3>
                <img src="<?php echo checkImg($musicResult['imgPath']) ?>" alt="no-img" id="music-img">
            </span>
            <span class="img-preview" id="new-img-preview">

            </span>
            <form action="server/uploadFile.php" method="post" enctype="multipart/form-data">
                <label for="imageFile">Choose file
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M3 13V11C3 7.22876 3 5.34315 4.17157 4.17157C5.34315 3 7.22876 3 11 3H13C16.7712 3 18.6569 3 19.8284 4.17157C21 5.34315 21 7.22876 21 11V13C21 16.7712 21 18.6569 19.8284 19.8284C18.6569 21 16.7712 21 13 21H12"
                            stroke="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M18.9976 14.2904L18.4033 13.6961L18.3931 13.6858L18.393 13.6858C18.3245 13.6173 18.2784 13.5712 18.2394 13.5353C17.0477 12.4403 15.1454 12.749 14.3611 14.1647C14.3354 14.2111 14.3062 14.2694 14.2628 14.3561L14.2564 14.369C14.227 14.4278 14.22 14.4416 14.2161 14.4486C14.0513 14.7448 13.6458 14.7948 13.4142 14.5474C13.4087 14.5415 13.3985 14.5298 13.3557 14.4799L8.37962 8.67449C8.19991 8.46483 7.88426 8.44055 7.6746 8.62026C7.46494 8.79997 7.44065 9.11562 7.62037 9.32528L12.5964 15.1307L12.6038 15.1393L12.6038 15.1393C12.6352 15.1759 12.6614 15.2066 12.6842 15.2309C13.3793 15.9731 14.5957 15.8233 15.09 14.9348C15.1062 14.9056 15.1242 14.8695 15.1458 14.8263L15.1508 14.8162C15.203 14.712 15.2218 14.6746 15.2358 14.6493C15.7064 13.7999 16.8478 13.6147 17.5628 14.2717C17.5841 14.2912 17.6138 14.3208 17.6962 14.4032L18.9755 15.6825C18.9887 15.2721 18.9948 14.812 18.9976 14.2904Z"
                            fill="white" />
                        <circle cx="16.5" cy="7.5" r="1.5" fill="white" />
                        <path
                            d="M8 16V15.5H8.5V16H8ZM3.31235 20.3904C3.09672 20.5629 2.78207 20.528 2.60957 20.3123C2.43706 20.0967 2.47202 19.7821 2.68765 19.6096L3.31235 20.3904ZM7.5 21V16H8.5V21H7.5ZM8 16.5H3V15.5H8V16.5ZM8.31235 16.3904L3.31235 20.3904L2.68765 19.6096L7.68765 15.6096L8.31235 16.3904Z"
                            fill="white" />
                    </svg>
                </label>
                <input type="text" name="musicID" class="dis-none" value="<?php echo $id ?>">
                <input type="file" name="imageFile" accept=".jpg, .jpeg, .png" id="imageFile">
                <input type="submit" value="UPLOAD IMAGE" class="upload-file-btn"
                    onclick="return confirm('[Warning] Upload this Img? This action can\'t undo!')">
            </form>
        </div>


        <!-- upload music -->
        <h2>Music Source</h2>
        <div class="media-upload-container">
            <input type="text" name="musicPath" id="musicPath" value="<?php echo $musicResult['musicPath'] ?>"
                class="dis-none">
            <span>
                <h3>Current</h3>
                <audio controls>
                    <source src="<?php echo $musicResult['musicPath'] ?>">
                </audio>
                <span class="new-source-span">

                </span>
            </span>
            <form action="server/uploadFile.php" method="post" enctype="multipart/form-data">
                <label for="audioFile">Choose music Source</label>
                <input type="text" name="musicID" class="dis-none" value="<?php echo $id ?>">
                <input type="file" name="audioFile" id="audioFile" class="dis-none" accept=".mp3" />
                <input type="submit" value="UPLOAD AUDIO" class="upload-file-btn"
                    onclick="return confirm('[Warning] Upload this file .mp3? This action can\'t undo!')">
            </form>
        </div>
    </section>
</body>

</html>