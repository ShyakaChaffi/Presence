<!DOCTYPE html>
<html class="background_site">
    <head>
        <meta charset="UTF-8">
        <title>Gestion Cours</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/normalize.css"> 
        <link rel="stylesheet" type="text/css" href="css/style5.css" >
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <script src="lib/jquery-3.3.1.min.js" ></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js"></script>
    </head>
    <body>
        <?php include('menu_admin1.html'); ?>
        <div class="div_student_list">
            <p> Etes-vous sur de vouloir supprimer le cours : </p>
            <p> <?= $course->id ?> </p>
            <p> <?= $course->title ?> </p>
            <form class='btnpopup' action='admin/delete_course' method='post'>
                <input type='hidden' name='deletecourse' value='<?= $course->id ?>' >
                <input class="btnindex" type='submit' value='delete' >
            </form>
            <a href="admin/index" id="btn-without-js"><input  class="btnindex" type="button" value="Back"/></a>
        </div>
    </body>
</html>
