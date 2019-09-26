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
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.3.1.min.js" ></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" ></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js" ></script>
        <script src="lib/redirect.js"></script>
        <script>
            $(function () {
                $(".btn-without-js").hide();
            });
            $(function () {

                $.validator.addMethod("finishdate", function (value) {
                    return $("#startdate").val() <= value;
                }, "End Date should be greater than Start Date.");

                $.validator.addMethod("startdate", function (value) {
                    return $("#finishdate").val() >= $("#startdate").val();
                }, "End Date should be greater than Start Date.");

                $.validator.addMethod("endtime", function (value) {
                    return $("#starttime").val() <= value;
                }, "End Time should be greater than Start Time.");

                $.validator.addMethod("starttime", function (value) {
                    return $("#endtime").val() >= value;
                }, "End Time should be greater than Start Time.");

                $.validator.addMethod("regex", function (value, pattern) {
                    if (pattern instanceof Array) {
                        for (p of pattern) {
                            if (!p.test(value))
                                return false;
                        }
                        return true;
                    } else {
                        return pattern.test(value);
                    }
                }, "Please enter a valid input.");
                $('#addCourse').validate({
                    rules: {
                        code: {
                            remote: {
                                url: 'course/code_available_service',
                                type: 'post',
                                data: {
                                    pseudo: function () {
                                        return $("#code").val();
                                    }
                                }
                            },
                            required: true,
                            regex: [/[0-9]+/]
                        },
                        title: {
                            required: true,
                        },
                        dayofweek: {
                            required: true,
                        },
                        startdate: {
                            required: true,
                            startdate: $("#startdate")
                        },
                        finishdate: {
                            required: true,
                            finishdate: $("#finishdate")
                        },
                        starttime: {
                            required: true,
                            starttime: $("#starttime")
                        },
                        endtime: {
                            required: true,
                            endtime: $("#endtime")
                        },
                        teacher: {
                            required: true,
                        }

                    },
                    messages: {
                        code: {
                            required: 'required',
                            remote: 'this code is already taken',
                            regex: 'Only numbers',
                        },
                        title: {
                            required: 'required',
                        },
                        dayofweek: {
                            required: 'required',
                        },
                        startdate: {
                            required: 'required',
                        },
                        finishdate: {
                            required: 'required',
                        },
                        starttime: {
                            required: 'required',
                        },
                        endtime: {
                            required: 'required',
                        },
                        teacher: {
                            required: 'required',
                        }
                    }
                });

                $("input:text:first").focus();
            });

            function deleteCoursePopUp(id) {
                var toDelete = id;
                $("#confirm_dialog").attr("hidden", false);
                $('#course_code').text(toDelete);
                $('#confirm_dialog').dialog({
                    resizable: false,
                    height: 300,
                    width: 600,
                    modal: true,
                    autoOpen: true,
                    buttons: {
                        Confirm: function () {
                            $.redirect('admin/delete_course', {'deletecourse': toDelete});
                            $(this).dialog("close");
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    }
                });
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
        <div class="div_student_list">
            <table class="student_list">
                <tr>
                    <th>Code</th>
                    <th>Titre</th>
                    <th>Jour</th>
                    <th>Debut</th>
                    <th>Fin</th>
                    <th>Professeur</th>
                    <th colspan="2" >Actions</th>
                </tr>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td ><?= $course->id ?></td>
                        <td>
                            <form class='link' action='admin/home' method='post'>
                                <input type='hidden' name='courseselected' value='<?= $course->id ?>' >
                                <input type='submit' value='<?= $course->title ?>'>
                            </form>
                        </td>   
                        <td><?= $course->get_jour() ?></td>
                        <td><?= $course->startTime ?></td>
                        <td><?= $course->finishTime ?></td>
                        <td><?= $course->get_teacher_name() ?></td>
                        <td>
                            <form class='link' action='admin/student_by_course' method='post'>
                                <input type='hidden' name='studentbycourse' value='<?= $course->id ?>' >
                                <input type='submit' value='étudiants'>
                            </form>
                        </td>
                        <td >
                            <form  class='btnpopup' action='admin/view_delete_course' method='post'>
                                <input type='hidden' name='deletecourse' value='<?= $course->id ?>' >
                                <input class="btn-without-js" type='submit' value='delete' >

                            </form>
                            <input class="btnindex" type='submit' value='delete' onclick="deleteCoursePopUp(<?= $course->id ?>);" >
                        </td>                           
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="div_student_list">
            <?php if (!$newcourse) { ?>
                <form id='updateCourse' action='admin/update_course' method='post'>
                <?php } else { ?>
                    <form id='addCourse' action='admin/add_course' method='post'>
                    <?php } ?>
                    <table class="student_list">
                        <tr>
                            <?php if ($newcourse) { ?>
                                <th colspan="4"><p>Ajouter Cours</p></th>
                            <?php } else { ?>   
                                <th colspan="4">Modifier Cours</th> <?php } ?>
                        </tr>
                        <tr>
                            <td><label for="code">Code:</label></td>
                            <td colspan="3"><?php if (!$newcourse) { ?><input name="code" value=" <?php echo $courseselected->id; ?>" hidden> <?php } ?><input id="code" name="code" type="text" size="6" value="<?php if ($courseselected != "") echo $courseselected->id; ?>" <?php if (!$newcourse) echo "disabled=disabled"; ?>></td>
                        </tr>
                        <tr>
                            <td><label for="title">Titre:</label></td>
                            <td colspan="3"><input id="title" name="title" type="text" size="40" value="<?php if ($courseselected != "") echo$courseselected->title; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="dayofweek">Jour:</label></td>
                            <td>
                                <select id="dayofweek" name="dayofweek"  <?php if (!$newcourse) echo "disabled=disabled"; ?>>
                                    <?php for ($i = 0; $i < 5; ++$i) { ?>
                                        <option value="<?= $i ?>" <?php
                                        if ($courseselected != "" && $courseselected->dayOfWeek == $i) {
                                            echo "selected";
                                        }
                                        ?>><?php
                                                    echo CourseOccurrence::get_day_of_week_in_french($i);
                                                }
                                                ?></option>                       
                                </select>
                            </td>
                            <td><label for="starttime">De:</label>
                                <input id="starttime" name="starttime" type="time" size="10" value="<?php if ($courseselected != "") echo $courseselected->startTime; ?>">
                            </td>
                            <td><label for="endtime">A :</label>
                                <input id="endtime" name="endtime" type="time" size="10" value="<?php if ($courseselected != "") echo $courseselected->finishTime; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="startdate">Debut :</label></td>
                            <td colspan="3"><input id="startdate" name="startdate" type="date" size="10" value="<?php if ($courseselected != "") echo $courseselected->startDate; ?>"  <?php if (!$newcourse) echo "disabled=disabled"; ?>></td>
                        </tr>
                        <tr>
                            <td><label for="finishdate">Fin :</label></td>
                            <td colspan="3"><input id="finishdate" name="finishdate" type="date" size="10" value="<?php if ($courseselected != "") echo $courseselected->finishDate; ?>"  <?php if (!$newcourse) echo "disabled=disabled"; ?>></td>
                        </tr>
                        <tr>
                            <td><label for="teacher">Professeur:</label></td>
                            <td colspan="3">
                                <select id="teacher" name="teacher">
                                    <?php foreach ($teachers as $teacher) { ?>
                                        <?php if ($teacher->role === "teacher") { ?>
                                            <option value="<?= $teacher->id ?>"<?php
                                            if ($courseselected != "" && $teacher->id === $courseselected->teacher) {
                                                echo "selected";
                                            }
                                            ?>><?= $teacher->fullname ?> </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                </select>
                            </td>
                        </tr>
                        <tr> 
                            <td colspan="4">
                                <input class="btnindex" type="submit" value=<?php
                                if ($newcourse) {
                                    echo "Ajouter";
                                } else {
                                    ?> "Modifier" <?php } ?>>
                            </td>
                        </tr>
                    </table>
                </form>
        </div>
        <form class='reset' action='admin/home' method='post'>
            <input type='hidden' name='courseselected' value="">
            <input class="btnindex" type='submit' value='Annuler'>
        </form>
        <div id="confirm_dialog" title="Confirmation de la suppresion" hidden>
            <p>Veuillez confirmer la suppression du cours : <b><span id="course_code"></span></b>
        </div>
         <div id="easter" title="Mayor Of EPFC" hidden>
           <img src="img/easter.jpg" alt="" /></b>
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
        <?php endif; ?>
    </body>
</html>
