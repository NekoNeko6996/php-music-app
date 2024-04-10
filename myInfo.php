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
} else {
    header("Location: home.php");
    exit();
}
?>

<body>
    <div class="message-container"></div>
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


        <form action="" method="post" onsubmit="sendRequestChangeName(event)">
            <input type="text" name="uid" value="<?php if (isset($_GET['uid']))
                echo $_GET['uid'] ?>" class="hidden">
                <span>
                    <p>User Name </p>
                    <input type="text" name="userName" id="userName" value="<?php echo $user[0]['userName'] ?>"
                    oninput="disabledOff('change-name-btn')" required pattern="[A-Za-z0-9À-ỹ ]{1,}">
            </span>
            <button type="submit" id="change-name-btn" disabled>Change User Name</button>
        </form>



        <h2>Change Password</h2>
        <form action="" method="post" onsubmit="requestChangePassword(event)">
            <span>
                <p>Password</p>
                <input type="password" name="password" id="password" oninput="changePassBtn()"> <br>
            </span>
            <span>
                <p>New Password</p>
                <input type="password" name="new-password" id="new-password" oninput="changePassBtn()">
                <br>
            </span>
            <button type="submit" disabled id="change-pass-btn">Change Password</button>
        </form>
    </section>


    <script>
        var currentUserName = "<?php echo $user[0]['userName'] ?>";

        function callMessageBox(message, timeLife) {
            $(".message-container").css("display", "block");
            $(".message-container").text(message);
            setTimeout(() => {
                $(".message-container").css("display", "none");
            }, timeLife);
        }

        function disabledOff(id) {
            $(`#change-name-btn`).removeAttr("disabled");
        }

        function sendRequestChangeName(event) {
            event.preventDefault();
            var newUserName = event.target[1].value;
            if (newUserName != currentUserName) {
                $.ajax({
                    url: "server/changeUserInfo.php",
                    type: "post",
                    data: {
                        requestCode: 1,
                        newUserName
                    },
                    success: (response) => {
                        var objResponse = JSON.parse(response);
                        if (objResponse.status) {
                            callMessageBox("Change User Name Success!")
                        }
                        else {
                            alert("[Change User Name ERROR]");
                        }
                    },
                })
            }
        }

        function changePassBtn() {
            if ($("#password").val() != "" && $("#new-password").val() != "") {
                $("#change-pass-btn").removeAttr("disabled");
            }
            else {
                $("#change-pass-btn").attr("disabled", "disabled");
            }
        }
        //pattern=".{8,}"
        function requestChangePassword(event) {
            event.preventDefault();

            if (event.target[0].value && event.target[1].value) {
                var password = event.target[0].value;
                var newPassword = event.target[1].value;
                $.ajax({
                    url: "server/changeUserInfo.php",
                    type: "post",
                    data: {
                        requestCode: 2,
                        password,
                        newPassword
                    },
                    success: (response) => {
                        var objResponse = JSON.parse(response);
                        if (objResponse.status) {
                            // window.location.reload();

                        }
                        else {
                            alert("[Change password ERROR]");
                        }
                    },
                })
            }

        }
    </script>
</body>

</html>