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
        <script src="lib/redirect.js"></script>
        <script>
            $(function () {
                $("#btn-without-js").hide();
            });
            var view = '<?= $view ?>';
            var date = '<?= $date ?>';


            function setPresence(student, presence) {
                $.post("course/set_presence", {occurence:<?= $courseoccurrence->id ?>, student: student, present: presence
                });
            }

            function fullCalendar() {
                $.redirect('user/home', {'view': view, 'date': date});
            }


        </script>
    </head>
    <body>
        <?php include('menu.html'); ?>
        <div class='main'> 
            <p><?= $date ?></p>

            <p><?= $course->title ?></p>
            <div class="conteneur">
                <div class="div_student_list">
                    <form action="user/presence_by_occurrence" method="post">
                        <table class="student_list">
                            <tr>
                                <th>Nom</th>
                                <th>Présent</th>
                                <th>Absent</th>
                            </tr>

                            <?php foreach ($liste_student as $student) : ?>  
                                <tr>
                                    <td> <?= $student->firstName ?> <?= $student->lastName ?></td>    

                                <input type='text' id='student' name="student['<?= $student->id ?>']" value='<?= $student->id ?>'hidden >
                                <input type='text' id='courseselected' name='courseselected' value='<?= $course->id ?>' hidden>
                                <input type='text' id='date' name='date' value='<?= $date ?>' hidden>
                                <?php if ($student->check_certif($courseoccurrence->date)) { ?>
                                    <td colspan="2">
                                        Certificat Medical
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <input type='radio' id='present' name="presence['<?= $student->id ?>']" value='1' 
                                        <?php
                                        if ($student->check_presence($courseoccurrence->id) === '1') {
                                            echo 'checked="checked"';
                                        }
                                        ?> 
                                               onclick="setPresence(<?= $student->id ?>, <?= 1 ?>);"
                                               >
                                    </td>
                                    <td>
                                        <input type='radio' id='absent' name="presence['<?= $student->id ?>']"  value='0' <?php
                                        if ($student->check_presence($courseoccurrence->id) === '0') {
                                            echo 'checked="checked"';
                                        }
                                        ?> 
                                               onclick="setPresence(<?= $student->id ?>, <?= 0 ?>);"
                                               >
                                    </td>
                                    </tr>
                                <?php } ?>
                            <?php endforeach; ?>

                            <td colspan ="3"><input class="btnindex" type="submit" value="Sauver"/> 
                        </table>
                    </form>
                    <a href="user/home" id="btn-without-js"><input  class="btnindex" type="button" value="Back" onclick="fullCalendar();"/></a>
                    <input class="btnindex" type="button" value="Back" onclick="fullCalendar();"/>
                    <p>
                    <form action="user/historique" method="post">
                        <input type='text' id='courseselected' name='historique' value='<?= $course->id ?>' hidden>
                        <input class="btnindex" type="submit" value="Historique"/>   
                    </form></td>   
                </div>
            </div>      
        </div>
        <?php if (strlen($success) != 0): ?>
            <p><span class='success'><?= $success ?></span></p>
            <?php endif; ?>
    </body>
</html>