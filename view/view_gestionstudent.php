<!DOCTYPE html>
<html class="background_site">
    <head>
        <meta charset="UTF-8">
        <title>Gestion Etudiant</title>
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
                <table class="student_list">
                    <tr>
                        <th>Nom</th>
                        <th>Sexe</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td>
                                <form class='link' action='admin/gestion_student' method='post'>
                                    <input type='text' name='studentselected' value='<?= $student->id ?>' hidden>
                                    <input type='submit' value='<?= $student->firstName ?> <?= $student->lastName ?>'>
                                </form>
                            </td>   
                            <td><?= $student->sexe ?></td> 
                            <td class="popup" onclick="myFunction('<?= $student->id ?>')"><IMG src="img/delete.png" alt="delete" width="30" height="30">
                                <div class="popuptext" id="myPopup<?= $student->id ?>">
                                    <span> Voulez-vous supprimer <?= $student->firstName ?> <?= $student->lastName ?> ?</span>
                                    <form class='btnpopup' action='admin/delete_student' method='post'>
                                        <input type='text' name='deletestudent' value='<?= $student->id ?>' hidden>
                                        <input type='submit' value='delete'>
                                        <input type='reset' value='cancel'>
                                    </form>
                                </div>
                            </td>                           
                        </tr>
                    <?php endforeach; ?>
                </table>
                <br>
            </div>
        </div>


        <div class="div_student_list">
            <?php if ($studentselected != "") { ?>
                <form id="updatestudent" action="admin/updatestudent" method="post">
                <?php } else { ?>
                    <form id="insertstudent" action="admin/insert_student" method="post">
                    <?php } ?>

                    <input id="oldstudent" name="oldstudent" type="text" size="6" value="<?php if ($studentselected != "") echo $studentselected->id; ?>" hidden>

                    <table class="student_list">
                        <tr>
                            <th colspan="2">Modifier etudiant</th>
                        </tr>
                        <tr>
                            <td><label for="code">Prenom:</label></td>
                            <td><input id="code" name="firstname" type="text" size="20" value="<?php if ($studentselected != "") echo $studentselected->firstName; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="title">Nom:</label></td>
                            <td><input id="title" name="lastname" type="text" size="20" value="<?php if ($studentselected != "") echo $studentselected->lastName; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="sexe">Sexe:</label></td>
                            <td>
                                <select id="sexe" name="sexe">
                                    <option value="M" <?php
                                    if ($studentselected != "")
                                        if ($studentselected->sexe === "M") {
                                            echo "selected";
                                        }
                                    ?>>M</option>
                                    <option value="F"<?php
                                    if ($studentselected != "")
                                        if ($studentselected->sexe === "F") {
                                            echo "selected";
                                        }
                                    ?>>F</option>       
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
                <form class='reset' action='admin/gestion_student' method='post'>
                    <input type='text' id='studentselected' name='studentselected' value="" hidden>
                    <input class="btnindex" type='submit' value='Annuler'>
                </form>
        </div>

        <?php if (count($errors) != 0): ?>
            <div class='errors'>
                <p>Please correct the following error(s) :</p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>

                </ul>
            </div>
        <?php elseif (strlen($success) != 0): ?>
            <p><span class='success'><?= $success ?></span></p>
        <?php endif; ?>

        <div id="easter" title="" hidden>
            <iframe src="https://giphy.com/embed/11vhCpFcD3um7m" width="480" height="451" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/ram-oneplus-lpddr4-11vhCpFcD3um7m">via GIPHY</a></p>
        </div>
    </body>
</html>