<?php
session_start();
if (isset($_SESSION['newUploadMusicID'])) {
    unset($_SESSION['newUploadMusicID']);
}
header("Location: ../admin.php");
exit();
?>