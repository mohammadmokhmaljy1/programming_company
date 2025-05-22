<!DOCTYPE html>
<html lang="ar-sy">

<head>
    <?php include_once "includes/head.php"; ?>
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>
    <div class="container f-cen bg-45 login-page">
        <form action="" method="post" autocomplete="on"  class="g24 f-cen f-col p24" action="javascript:void(0);">
            <img src="images/logo.jpg" alt="" style="width: 100px;" class="r50">

            <!-- <h2 class="txt-primary">Login Page</h2> -->

            <div class="input-box">
                <input type="email" name="email" id="email" required tabindex="1" autofocus autocomplete="email">
                <label for="email">Your Email:</label>
            </div>

            <div class="input-box">
                <input type="password" name="password" id="password" required tabindex="2" autocomplete="current-password">
                <label for="password">Your Password:</label>
            </div>

            <button name="login" class="btn w-100" type="submit">Login</button>
        </form>
    </div>

    <?php include_once "./includes/messageModal.php"; ?>

    <script src="scripts/messageModel.js"></script>
    <script src="scripts/validation.js"></script>
</body>

</html>