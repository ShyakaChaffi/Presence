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
    <body >

        <?php include('menu_admin1.html'); ?>

        <div class="conteneur">
            <div class="div_student_list">
                <table class="courses_list">
                    <tr>
                        <th colspan ="6">Cours sélectionné:</th>
                    </tr>
                    <tr>
                        <td>Code:</td>
                        <td colspan="5"><?= $course->id ?></td>
                    </tr>
                    <tr>
                        <td>Titre:</td>
                        <td colspan="5"><?= $course->title ?></td>
                    </tr>
                    <tr>
                        <td>Jour:</td>
                        <td><?= $course->get_jour() ?> </td>
                        <td>De:
                        <td><?= $course->startTime ?></td>
                        <td>A :
                        <td><?= $course->finishTime ?></td>
                    </tr>
                    <tr>
                        <td>Debut :</td>
                        <td colspan="5"><?= $course->startDate ?></td>
                    </tr>
                    <tr>
                        <td>Fin :</td>
                        <td colspan="5"><?= $course->finishDate ?></td>
                    </tr>
                    <tr>
                        <td>Professeur:</td>
                        <td colspan="5"><?= $course->get_Teacher_Name() ?></td>
                    </tr>
                </table>
            </div>
            <br>
            <form action="admin/delete_student_by_course" method="post">
                <input  name="studentbycourse" type="text" size="6" value="<?= $course->id ?>" hidden>
                <table class="courses_list">
                    <tbody> 
                        <tr>
                            <th style="text-align: center;">Etudiant inscrit:</th>
                            <th></th>
                            <th style="text-align: center;">Etudiant non-inscrit:</th>
                        </tr>
                        <tr>

                            <td rowspan="2" style="text-align: center;">
                                <span title="Maintenez la touche CTRL pour sélectionner plusieurs étudiants">
                                    <select name="studentin[]" size ="10" multiple="multiple">
                                        <?php foreach ($studentsin as $student): ?>
                                            <option value="<?= $student->id ?>"><?= $student->lastName ?> <?= $student->firstName ?> </option>
                                        <?php endforeach; ?>
                                    </select></span>
                            </td>
                            <td style="text-align: center;">
                                <input class="btnindex" type="submit" value=">">
                                </form>

                                <form  action="admin/delete_all_student_by_course" method='post'>
                                    <input  name="studentbycourse" type="text" size="6" value="<?= $course->id ?>" hidden>
                                    <input type='text' id='allstudent' name='allstudent' value="delete" hidden>
                                    <input class="btnindex" type="submit" value=">>">
                                    </td>
                                </form>


                                <form action="admin/add_student_by_course" method="post">
                                    <input name="studentbycourse" type="text" size="6" value="<?= $course->id ?>" hidden>
                                    <td rowspan="2" style="text-align: center;">
                                        <span title="Maintenez la touche CTRL pour sélectionner plusieurs étudiants">
                                            <select name="studentnotin[]" size ="10" multiple="multiple">
                                                <?php foreach ($studentsnotin as $student): ?>
                                                    <option value="<?= $student->id ?>"><?= $student->lastName ?> <?= $student->firstName ?> </option>
                                                <?php endforeach; ?>
                                            </select></span>
                                    </td>
                        </tr>
                        <tr>
                            <td style="
                                background-color:  lightgrey; text-align: center;"
                                >
                                <input class="btnindex" type="submit" value="<">
                                </form>
                                <form  action="admin/add_student_by_course" method='post'>
                                    <input id="studentbycourse" name="studentbycourse" type="text" size="6" value="<?= $course->id ?>" hidden>
                                    <input type='text' id="allstudent" name="allstudent" value="add" hidden>
                                    <input class="btnindex" type="submit" value="<<">
                                    </td>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <form  action="admin/home" method='post'>
                    <input class="btnindex" type="submit" value="Back">
                </form>
                <?php if (count($errors) != 0): ?>
                    <div class='errors'>

                        <p>Please correct the following error(s) :</p>
                        <ul>
                            <?php foreach ($errors as $t): ?>
                                <li><?= $t ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>  

                <?php endif; ?>
            </form>
        </div>
     <div id="easter" title="EPFChaffi" hidden>
           <img src="img/easter2.jpg" alt="" /></b>
        </div>  

</body>
</html>