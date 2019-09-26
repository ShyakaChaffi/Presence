<!DOCTYPE html>
<html class="background_site">
    <head>
        <meta charset="UTF-8">
        <title>Gestion Presence</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="css/style5.css"/>
        <script src="lib/jquery-3.3.1.min.js"  ></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js" ></script>
    </head>

    <body>
        <?php include('menu.html'); ?>
        <div class='main'> 
            <div class="div_course_list">
                <table class="student_list">
                    <tr>
                        <th>Cours</th>
                    </tr>
                    <?php foreach ($courses as $cours): ?>
                        <tr>        
                            <td>
                                <form class="link" action="user/student_presence" method="post">
                                    <input type='text' name='courseselected' value='<?= $cours->id ?>' hidden>
                                    <input type='submit' value='<?= $cours->title ?>'>
                                </form>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </table>   
                <?php if (!empty($studentsin)) : ?>
                    <br>
                    <table class="student_list">
                        <tr>
                            <th>Etudiant pour le cours de <?= $courseselected->title ?></th>
                        </tr>
                        <?php foreach ($studentsin as $student): ?>
                            <tr>        
                                <td>
                                    <form class="link" action="user/student_presence" method="post">
                                        <input type='text' id='courseselected' name='courseselected' value='<?= $courseselected->id ?>' hidden>
                                        <input type='text' id='studentselected' name='studentselected' value='<?= $student->id ?>' hidden>
                                        <input type='submit' value='<?= $student->firstName ?> <?= $student->lastName ?>'>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                <?php endif; ?>

                <?php if (!empty($presences)) : ?>
                    <br>
                    <table class="student_list">
                        <tr>
                            <th>Presence de <?= $studentselected->firstName ?> <?= $studentselected->lastName ?></th>
                            <th> Present </th>
                            <th> Absent </th>
                        </tr>
                        <form action="user/student_presence" method="post">
                            <input type='text' id='courseselected' name='courseselected' value='<?= $courseselected->id ?>' hidden>
                            <input type='text' id='studentselected' name='studentselected' value='<?= $studentselected->id ?>' hidden>
                            <?php foreach ($courseoccurrence as $occurence): ?>
                                <tr>        
                                    <td>
                                        <?= $occurence->date ?>
                                    </td>
                                    <td>
                                        <input type="radio" name="presence['<?= $occurence->id ?>']" value="1"
                                        <?php
                                        foreach ($presences as $presence):
                                            if (!empty($presence['present']) && $presence['idoccurrence'] == $occurence->id && $presence['present'] == 1) :
                                                ?>
                                                <?= "checked " ?>                                              
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                               >
                                    </td>
                                    <td>
                                        <input type='radio'  name="presence['<?= $occurence->id ?>']" value='0' 
                                        <?php
                                        foreach ($presences as $presence):
                                            if ($presence['idoccurrence'] == $occurence->id && $presence['present'] == 0 && $presence['present'] != null) :
                                                ?>
                                                <?= "checked " ?>                                              
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                               >
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                    </table>

                <?php endif; ?>
                <br>
            </div>
            <br>
            <div class="div_student_list">
                <input class="btnindex" type="submit" value="Sauver"/>   <a href="user/student_presence" ><input class="btnindex" type="button" value="Back"/> </a>

            </div>
        </div>





    </body>

</html>