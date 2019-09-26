<!DOCTYPE html>
<html class="background">
    <head>
        <meta charset="UTF-8"> 
        <title>Log In</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js" type="text/javascript"></script>
        <script>
            $(function () {
                $('#loginForm').validate({
                    rules: {
                        pseudo: {
                            remote: {
                                url: 'user/pseudo_available_service',
                                type: 'post',
                                data: {
                                    pseudo: function () {
                                        return $("#pseudo").val();
                                    }
                                }
                            },
                        },
                    },
                    messages: {
                        pseudo: {
                            remote: 'Ce pseudo nexiste pas',
                        },
                    }
                });
                $("input:text:first").focus();
            });
        </script>
    </head>
    <body >
        <div class="index">
            <div class="blocindex">
                <div class="titleindex">Log In</div>

                <div class="menuindex">
                    <form id="loginForm" action="main/login" method="post">
                        <table >
                            <tbody>
                                <tr>
                                    <td class="login"><label for="pseudo">Pseudo:</label></td>
                                    <td><input class="entrer" id="pseudo" name="pseudo" type="text" size="20" value="<?= $pseudo ?>"></td><td></td>
                                </tr>
                                <tr >
                                    <td class="login"><label for="password">Password:</label></td>
                                    <td><input class="entrer" id="password" name="password" type="password" size="20" value="<?= $password ?>"></td>
                                </tr>
                            </tbody>
                        </table>
                        <p><input class="btnindex" type="submit" value="Validate"></p>
                        <p><input class="btnindex" type="reset" value="Cancel" ></p>
                    </form>
                    <?php if (count($errors) != 0): ?>
                        <div class='errors'>
                            <p>Please correct the following error(s) :</p>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>
