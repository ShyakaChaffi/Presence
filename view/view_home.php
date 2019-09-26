<!DOCTYPE html>
<html class="background_site">
    <head>
        <meta charset="UTF-8">
        <title><?= $user->pseudo ?>'s Planning!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="css/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="css/style5.css"/>
        <link rel="stylesheet" href="css/fullcalendar.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
        <script src="lib/jquery-3.3.1.min.js"  ></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js"></script>
        <script src="lib/jquery.min.js"></script>
        <script src="lib/jquery-ui.min.js"></script>
        <script src="lib/moment.min.js"></script>
        <script src="lib/fullcalendar.min.js"></script>
        <script src="lib/redirect.js"></script>
        <script>
            var view = '<?= $view ?>';
            var date = '<?= $date ?>';
            $(function () {
                $("#planning").hide();
                showCalendar();
            });

            function showCalendar() {
                $('#calendar').fullCalendar({
                    editable: false,
                    defaultView: view,
                    defaultDate: setDate(),
                    locale: 'fr',
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    events: {
                        url: 'Course/get_event',
                        type: 'POST',
                        cache: true,
                    },
                    viewRender: function () {
                        view = $('#calendar').fullCalendar('getView');
                        date = $('#calendar').fullCalendar('getDate');
                    },
                    eventClick: function (eventObj) {
                        $.redirect('Course/presence_by_occurrence', {'idoccurrence': eventObj.id, 'view': view.name, 'date': date.toString()});
                    }
                });
                $('#calendar').fullCalendar('changeView', 'agendaWeek');
            }

            function setDate() {
                if (date === "") {
                    return moment();
                } else {
                    return date;
                }
            }


        </script>
    </head>
    <body class="calendar">
        <?php include('menu.html'); ?>

        <div class="main" id="planning"> 
            <p> <?= $user->fullname; ?> </p>
            <p> Semaine du : 
                <?= $firstdayofweek ?>
                au 
                <?= $lastdayofweek ?></p>
            <div class="div_student_list">
                <?php for ($i = 0; $i < 6; ++$i) : ?> 
                    <table  class="student_list"> 
                        <?php $jourprec = null; ?> 
                        <?php foreach ($occurrences as $occurence): ?>
                            <?php if ($occurence->get_jour() == $i) : ?> 
                                <?php if ($occurence->get_jour() !== $jourprec) : ?>
                                    <tr><th colspan="2"><?= CourseOccurrence::get_day_of_week_in_french($i) ?></th></tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="colo_date"><?= $occurence->date ?></td>
                                    <td>
                                        <form class='link' action='user/presence_by_occurrence' method='post'>
                                            <input type='text' name='courseselected' value='<?= $occurence->course ?>' hidden>
                                            <input type='text' name='date' value='<?= $occurence->date ?>' hidden>
                                            <input type='submit' value='<?= $occurence->get_name_course() ?>'>
                                        </form>
                                    </td>   
                                </tr>
                                <?php $jourprec = $occurence->get_jour(); ?>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </table>
                    <br>
                <?php endfor; ?>

            </div>
            <div class="div_course_list">
                <table class="courses_list"> 
                    <tr>
                        <td><form  action='User/home' method='post'>
                                <input type='date' name='nextdate' value='<?= date("Y-m-d", strtotime(date("Y-m-d", strtotime($firstdayofweek)) . " -7 day")) ?>' hidden/>
                                <input class="btnindex" type='submit' value='<<' />
                            </form></td>
                        <td style="text-align: center;">
                            <form action='User/home' method='post'>
                                <input type='date' name='nextdate' value='<?= date("Y-m-d") ?>' hidden/>
                                <input class="btnindex" type='submit' value="Aujourd'hui" />
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <form action='User/home' method='post'>
                                <input type='date' name='nextdate' value='<?= date("Y-m-d", strtotime(date("Y-m-d", strtotime($firstdayofweek)) . " +7 day")) ?>' hidden/>
                                <input class="btnindex" type='submit' value='>>' />
                            </form>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        <div class="main"> 
            <div class="div_course_list">
                <div id="calendar"></div>
            </div>
        </div>
    </body>
</html>
