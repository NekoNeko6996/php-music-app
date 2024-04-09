<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/myInfo.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>My Info</title>
</head>

<?php
include 'database/connect.php';
include 'library/library.php';

session_start();

if (isset($_GET["uid"]) && isset($_SESSION['token'])) {
    $auth = Auth($_SESSION['token'], $connect);
    if ($auth) {
        $user = query("SELECT userName, email FROM user WHERE loginToken = ?", [$_SESSION['token']], $connect)['result'];
    } else {
        header("Location: home.php");
        exit();
    }

    if (isset($_SERVER['REQUEST_METHOD']) == "GET" && isset($_POST['userName'])) {
        echo $_POST['userName'];
    }
} else {
    header("Location: home.php");
    exit();
}
?>

<body>
    <nav>
        <div>
            <img src="assets/logo/logo.png" alt="logo">
            <p>𝓜𝓾𝓼𝓲𝓬 𝓐𝓹𝓹</p>
        </div>
        <div>
            <a href="home.php">Home</a>
        </div>
    </nav>
    <section>
        <div class="avatar-container">
            <img src="assets/img/default.jpg" alt="avatar">
        </div>

        <h2>User Information</h2>

        <div>
            <p>Email </p>
            <input type="text" name="email" id="email" class="no-bor" value="<?php echo $user[0]['email'] ?>" readonly>
        </div>


        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get">
            <input type="text" name="uid" value="<?php if (isset($_GET['uid']))
                echo $_GET['uid'] ?>" class="hidden">
                <span>
                    <p>User Name </p>
                    <input type="text" name="userName" id="userName" value="<?php echo $user[0]['userName'] ?>"
                    oninput="disabledOff('change-name-btn')" required>
            </span>
            <button type="submit" id="change-name-btn" disabled>Change User Name</button>
        </form>



        <h2>Change Password</h2>
        <form action="" method="post">
            <span>
                <p>Password</p>
                <input type="password" name="password" id="password"> <br>
            </span>
            <span>
                <p>New Password</p>
                <input type="password" name="new-password" id="new-password"> <br>
            </span>
            <button type="submit" disabled id="change-pass-btn">Change Password</button>
        </form>
    </section>


    <script>
        function disabledOff(id) {
            $(`#change-name-btn`).removeAttr("disabled");
        }


    </script>
</body>

</html>