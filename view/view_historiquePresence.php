<!DOCTYPE html>
<html class="background_site">
    <head>
        <meta charset="UTF-8">
        <title>Historique Cours</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="css/style5.css"/>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript" ></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js" type="text/javascript"></script>
    </head>
    <body >
        <?php include('menu.html'); ?>

        <div class='main'> 
            <p> Cours:<?= $course->title; ?> </p>
            <p>Professeur:<?= $user->fullname; ?></p>
            <p> horaire: de <?= $course->startTime ?> à <?= $course->finishTime ?></p>
            <p> du <?= $course->startDate ?> au <?= $course->finishDate ?></p>
            <div class="div_course_list">
                <table class="student_list">
                    <tr><th >DATE/ETUDIANT</th>
                        <?php foreach ($courseoccurrence as $occurences): ?>
                            <td class="incline"><?= $occurences->date ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($student as $s): ?>
                        <tr><td><?= $s->lastName ?> <?= $s->firstName ?> </td>
                            <?php foreach ($courseoccurrence as $occ): ?>
                                <?php if (!$s->check_certificat($occ->date)) : ?>
                                    <?php if ($s->get_presence($occ->id) === false): ?>
                                        <td class="nopresence"> ? </td>
                                    <?php endif; ?>
                                    <?php if ($s->get_presence($occ->id) === '1'): ?>
                                        <td class="presence"> P </td>
                                    <?php endif; ?>
                                    <?php if ($s->get_presence($occ->id) === '0'): ?>
                                        <td class="absent"> A </td>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($s->check_certificat($occ->date)) : ?>
                                    <td class="certificat"> CM </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>            
        </div> 
    </body>
</html>
