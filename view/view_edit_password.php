<!DOCTYPE html>
<html class="background_site">
    <head>
        <meta charset="UTF-8">
        <title><?= $user->pseudo ?>'s Profile!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="css/style5.css"/>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript" ></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="conteneur">
            <div class="div_student_list">
                <div class="title">Modifier Password</div>
                <div class="menu">
                    <?php include('menu.html'); ?>
                </div>
                <div class="main">

                    <br><br>
                    <form id="editPasswordForm" action="user/edit_password" method="post">
                        <table class="student_list">
                            <tr>
                                <td>Ancien Password:</td>
                                <td><input id="oldPassword" name="oldPassword" type="password" size="16" value=""></td>
                                <td class="errors" id="errOldPassword"></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><input id="newPassword" name="newPassword" type="password" size="16" value=""></td>
                                <td class="errors" id="errPassword"></td>
                            </tr>
                            <tr>
                                <td>Confirm Password:</td>
                                <td><input id="newPasswordConfirm" name="newPassword_confirm" size="16" type="password" value=""></td>
                                <td class="errors" id="errPasswordConfirm"></td>
                            </tr>
                        </table>
                        <br>
                        <input class="btnindex" id="btn" type="submit" value="edit password">
                        <input class="btnindex"  type="reset" value="cancel">
                    </form>
                    <?php if (count($errors) != 0): ?>
                        <div class='errors'>
                            <br><br><p>Please correct the following error(s) :</p>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php elseif (strlen($success) != 0): ?>
                        <p><span class='success'><?= $success ?></span></p>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>