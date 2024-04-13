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
        $user = query("SELECT userName, email, avatar FROM user WHERE loginToken = ?", [$_SESSION['token']], $connect)['result'];
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
    <div class="message-container">
        <p class="message-box"></p>
    </div>

    <div class="confirm-layer">
        <div class="confirm-box">
            <h2 class="confirm-title">Notification</h2>
            <p class="confirm-text"></p>
            <div>
                <button id="confirm-btn-yes">Yes</button>
                <button id="confirm-btn-no">No</button>
            </div>
        </div>
    </div>

    <div class="your-img-upload-layer">
        <div class="content-box">
            <h1>Preview New Avatar</h1>
            <img src="assets/img/default.jpg" alt="" class="preview-new-avatar">
            <form action="server/uploadFile.php" id="avatar-form" method="post" enctype="multipart/form-data">
                <input type="file" name="new-avatar" id="new-avatar-input" accept=".jpg, .jpeg, .png">
                <button type="submit" title="upload avatar">Upload Avatar</button>
            </form>
        </div>
    </div>

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
            <label for="new-avatar-input" class="img-label">
                <svg width="35px" height="35px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 16V3M12 3L16 7.375M12 3L8 7.375" stroke="white" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </label>
            <img src="<?php if (!empty($user[0]['avatar']))
                echo $user[0]['avatar'];
            else
                echo "assets/img/default.jpg" ?>" alt="avatar">
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
                <input type="password" name="password" id="password" oninput="changePassBtn()" autocomplete="on"> <br>
            </span>
            <span>
                <p>New Password</p>
                <input type="password" name="new-password" id="new-password" oninput="changePassBtn()"
                    autocomplete="on">
                <br>
            </span>
            <button type="submit" disabled id="change-pass-btn">Change Password</button>
        </form>
    </section>


    <script>
        var currentUserName = "<?php echo $user[0]['userName'] ?>";


        function callMessageBox(message, status, timeLife, callBack) {
            $(".message-container").css("display", "block");
            $(".message-container").css("animation-name", "show");

            $(".message-container").css("border-color", "rgb(56, 255, 56)");
            if (!status)
                $(".message-container").css("border-color", "red");

            $(".message-box").text(message);
            setTimeout(() => {
                if (callBack) callBack();
                $(".message-container").css("display", "none");
            }, timeLife);
        }

        function disabledOff(id) {
            $(`#change-name-btn`).removeAttr("disabled");
        }

        // ------------------------------------------------------------- //
        $("#new-avatar-input").on("change", (event) => {
            const newAvatarFile = event.target.files[0];
            if (!newAvatarFile) {
                callMessageBox("ERROR Load Avatar File!");
                return;
            }
            const src = URL.createObjectURL(newAvatarFile);

            $(".preview-new-avatar").attr("src", src);
            $(".your-img-upload-layer").css("display", "flex");
        })

        $(".your-img-upload-layer").on("click", (event) => {
            if (event.target.className == "your-img-upload-layer") {
                $(".your-img-upload-layer").css("display", "none");
            }
        })

        // ------------------------------------------------------------- //

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
                            callMessageBox("Change User Name Success!", true, 3000, () => window.location.reload());
                        }
                        else {
                            callMessageBox("Change User Name ERROR", false, 3000);
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

        // -------------------------------------------------------- //
        function cleanup() {
            $("#confirm-btn-yes").off("click");
            $("#confirm-btn-no").off("click");
        }

        function confirmMessage(message, callBack) {
            $(".confirm-layer").css("display", "flex");
            $(".confirm-text").text(message);

            $("#confirm-btn-yes").on("click", () => {
                if (callBack) callBack(true);
                $(".confirm-layer").css("display", "none");
                cleanup();
            })
            $("#confirm-btn-no").on("click", () => {
                if (callBack) callBack(false);
                $(".confirm-layer").css("display", "none");
                cleanup();
            })
        }

        $(".confirm-layer").on("click", (event) => {
            if (event.target.className == "confirm-layer") {
                $(".confirm-layer").css("display", "none");
            }
        })

        function requestChangePassword(event) {
            event.preventDefault();

            confirmMessage("Do you really want to change your password?", (confirm) => {
                if (confirm) {
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
                                    callMessageBox("Change Password Success!", true, 3000, () => window.location.reload());
                                }
                                else {
                                    callMessageBox(objResponse.message, false, 3000);
                                }
                            },
                        })
                    }
                }
            });
        }
    </script>
</body>

</html>