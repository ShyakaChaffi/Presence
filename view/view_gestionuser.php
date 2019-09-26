<!DOCTYPE html>
<html class="background_site">
    <head>
        <meta charset="UTF-8">
        <title>Gestion Utilisateurs</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/normalize.css"> 
        <link rel="stylesheet" type="text/css" href="css/style5.css" >
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.3.1.min.js" ></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" ></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js" ></script>
        <script>
// When the user clicks on div, open the popup
            function myFunction(id) {

                var popup = document.getElementById("myPopup" + id);


                popup.classList.toggle("show");
            }

            function easterEgg() {
                $("#easter").attr("hidden", false);
                $('#easter').dialog({
                    resizable: false,
                    height: 700,
                    width: 600,
                    modal: true,
                    autoOpen: true,
                    buttons: {
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <?php include('menu_admin1.html'); ?>
        <div class="conteneur">
            <div class="div_student_list">
                <br>
                <table class="student_list">
                    <tr>
                        <th>Pseudo</th>
                        <th>Nom</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($users as $utilisateur): ?>
                        <?php if ($utilisateur->id != $user->id) : ?>
                            <tr>
                                <td>
                                    <form class='link' action='admin/gestion_user' method='post'>
                                        <input type='text'  name='utilisateurselected' value='<?= $utilisateur->id ?>' hidden>
                                        <input type='submit' value='<?= $utilisateur->pseudo ?>'>
                                    </form>
                                </td>
                                <td><?= $utilisateur->fullname ?></td>
                                <td><?= $utilisateur->role ?></td> 
                                <td class="popup" onclick="myFunction('<?= $utilisateur->id ?>')"><IMG src="img/delete.png" alt="delete" border="0" width="30" height="30">
                                    <div class="popuptext" id="myPopup<?= $utilisateur->id ?>">
                                        <p> Voulez-vous supprimer <?= $utilisateur->fullname ?>?</p>
                                        <form class='btnpopup' action='admin/delete_user' method='post'>
                                            <input type='text' name='deleteuser' value='<?= $utilisateur->id ?>' hidden>
                                            <input type='submit' value='delete'>
                                            <input type='reset' value='cancel'>
                                        </form>
                                    </div>
                                </td>                           
                            </tr>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </table>
                <br>
            </div>



            <div class="div_student_list">
                <?php if ($utilisateurselected != "") { ?>
                    <form id="updateuser" action="admin/update_user" method="post">
                    <?php } else { ?>
                        <form id="insertuser" action="admin/insert_user" method="post">
                        <?php } ?>
                        <input id="olduser" name="olduser" type="text" size="6" value="<?php if ($utilisateurselected != "") echo $utilisateurselected->id; ?>" hidden>

                        <table class="student_list">
                            <tr>
                                <th colspan="2">Modifier utilisateur</th>
                            </tr>
                            <tr>
                                <td style="width: 20px;"><label for="pseudo">Pseudo:</label></td>
                                <td><input id="pseudo" name="pseudo" type="text" size="20" value="<?php if ($utilisateurselected != "") echo $utilisateurselected->pseudo; ?>"></td>
                            </tr>
                            <tr>
                                <td><label for="fullname">Nom:</label></td>
                                <td><input id="fullname" name="fullname" type="text" size="20" value="<?php if ($utilisateurselected != "") echo $utilisateurselected->fullname; ?>"></td>
                            </tr>
                            <tr>
                                <td><label for="role">role:</label></td>
                                <td>
                                    <select id="role" name="role">
                                        <option value="teacher" <?php
                                        if ($utilisateurselected != "")
                                            if ($utilisateurselected->role === "teacher") {
                                                echo "selected";
                                            }
                                        ?>>Teacher</option>
                                        <option value="admin"<?php
                                        if ($utilisateurselected != "")
                                            if ($utilisateurselected->role === "admin") {
                                                echo "selected";
                                            }
                                        ?>>Admin</option>       
                                    </select>
                                </td>
                            </tr>
                            <tr> 
                                <td colspan="2">
                                    <input class="btnindex" type="submit" value="Modifier">
                                </td>
                            </tr>
                        </table>
                    </form>
                    <form class='reset' action='admin/gestion_user' method='post'>
                        <input type='text' id='utilisateurselected' name='utilisateurselected' value="" hidden>
                        <input class="btnindex" type='submit' value='Annuler'>
                    </form>
                    <br>
                    <?php if ($utilisateurselected != "") { ?>
                        <div title="Réinitialise le Password à : Password1,">
                            <form class='reset' action='admin/reset_password' method='post'>
                                <input id="id" name="id" type="text" size="6" value="<?= $utilisateurselected->id ?>" hidden>
                                <input type='text' id='password' name='password' value="reset" hidden>
                                <input class="btnindex" type='submit' value='Reset Password'>
                            </form>
                        </div>
                    <?php } ?>
            </div>    
        </div>
        <?php if (count($errors) != 0): ?>
            <div class='errors'>
                <p>Please correct the following error(s) :</p>
                <ul>

                    <li><?= $errors ?></li>

                </ul>
            </div>
        <?php endif; ?>
        <div id="easter" title="" hidden>
            <iframe src="https://giphy.com/embed/Lny6Rw04nsOOc" width="480" height="360" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/code-404-javascript-Lny6Rw04nsOOc">via GIPHY</a></p>        </div>
    </body>
</html>