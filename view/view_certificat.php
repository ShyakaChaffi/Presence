<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html class="background_site">
    <head>

        <meta charset="UTF-8">
        <title>Gestion Certificat!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/normalize.css"> 
        <link rel="stylesheet" type="text/css" href="css/style5.css" >
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.17.0/jquery.validate.min.js" type="text/javascript"></script>
        <script src="lib/redirect.js"></script>
        <script>

            var certifs;
            $(function () {
                $(".btn-without-js").hide();
            });
            $.validator.addMethod("finishdate", function (value) {
                return $("#startdate").val() <= value;
            }, "End Date should be greater than Start Date.");

            $.validator.addMethod("startdate", function (value) {
                return $("#finishdate").val() >= value;
            }, "End Date should be greater than Start Date.");
            $(function () {
                $('#updateuser').validate({
                    rules: {
                        dateDebut: {
                            required: true,
                            startdate: $("#startdate")
                        },
                        dateFin: {
                            required: true,
                            finishdate: $("#finishdate")
                        }
                    },
                    messages: {
                        startdate: {
                            required: 'required',
                        },
                        finishdate: {
                            required: 'required',
                        }
                    }
                });
                getCertifs();
            });


            function getCertifs() {
                $.get("course/get_certifs_service/", function (data) {
                    certifs = data;
                    displayAll();
                }, "json").fail(function () {
                    $("#certifs").html("<tr><td>Error certifs !</td></tr>");
                });
            }

            function filtrer() {
                if ($("#studentselected").val() != "allStudent") {
                    displayByStudent($("#studentselected").val());
                } else {
                    displayAll();
                }
            }

            function displayByStudent(id) {
                var html = "<thead><tr><th>Etudiants</th>" +
                        "<th>Début</th>" +
                        "<th>Fin</th>" +
                        "<th>Actions</th></tr></thead>";
                for (var c of certifs) {
                    if (c.student === id) {
                        html += "<tr>";
                        html += "<td>" + c.firstname + ", " + c.lastname + "</td>";
                        html += "<td>" + c.startdate + "</td>";
                        html += "<td>" + c.finishdate + "</td>";
                        html += "<td><input class='btnindex' type='button' value='delete' onclick='PopUp(\"" + c.id + "\")' ></td>"
                        html += "</tr>";
                    }
                }
                $("#certifs").html(html);
            }

            function displayAll() {
                var html = "<thead><tr><th>Etudiants</th>" +
                        "<th>Début</th>" +
                        "<th>Fin</th>" +
                        "<th>Actions</th></tr></thead>";
                for (var c of certifs) {
                    html += "<tr>";
                    html += "<td>" + c.firstname + ", " + c.lastname + "</td>";
                    html += "<td>" + c.startdate + "</td>";
                    html += "<td>" + c.finishdate + "</td>";
                    html += "<td> <input class='btnindex' type='button' value='delete' onclick='PopUp(\"" + c.id + "\")' ></td>"
                    html += "</tr>";
                }
                $("#certifs").html(html);
            }


            function deleteCertificate(id) {
                $.post("admin/delete_certificat/",
                        {"param": id}
                ).fail(function () {
                    alert("<tr><td>Error!</td></tr>");
                });
                displayAll();
            }

            function PopUp(id) {
                var toDelete = certifs.find(function (element) {
                    return element.id === id;
                });
                if (toDelete !== undefined) {
                    $("#confirm_dialog").attr("hidden", false);
                    $('#certif_to_delete_Firstname').text(toDelete.firstname);
                    $('#certif_to_delete_Lastname').text(toDelete.lastname);
                    $('#certif_to_delete_datestart').text(toDelete.startdate);
                    $('#certif_to_delete_enddate').text(toDelete.finishdate);
                    $('#confirm_dialog').dialog({
                        resizable: false,
                        height: 300,
                        width: 600,
                        modal: true,
                        autoOpen: true,
                        buttons: {
                            Confirm: function () {
                                deleteCertificate(id);
                                $(this).dialog("close");
                            },
                            Cancel: function () {
                                $(this).dialog("close");
                            }
                        }
                    });

                }
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
            <br>

            <div class="div_student_list">
                <form method="post" action="admin/gestion_Certificat">
                    <select id="studentselected" name='recharger' onchange="filtrer();">
                        <option value='allStudent' <?php if ($studentselected === "") echo "selected"; ?> >Tout les étudiants</option>
                        <?php foreach ($etudiant as $etudiants): ?>
                            <option value=<?= $etudiants->id ?> <?php if ($studentselected !== "" && $studentselected->id == $etudiants->id) echo "selected"; ?> ><?= $etudiants->firstName ?> <?= $etudiants->lastName ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type='submit'  value='Actualiser'>
                </form>
                <form method="post" action="admin/delete_certificat">
                    <table id="certifs" class="student_list">
                        <tr>
                            <th>Etudiant</th>
                            <th>Début</th>
                            <th>fin</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($studentcertif as $list): ?>
                            <tr>
                                <td><?= $list->get_name_student() ?></td>
                                <td><?= $list->startdate ?></td>
                                <td><?= $list->finishdate ?></td>
                                <td><input type="hidden" name="effacer" value='<?= $list->id ?>' ><input type="submit" value='Delete'></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </form>
            </div>
        </div>

        <div class="div_student_list">
            <form id="updateuser" method="post" action="admin/add_certificat">
                <table class="student_list">
                    <tr>
                        <th>Ajouter Certificat</th>
                    </tr>
                    <tr>
                        <td> <select name='choixetudiant'>
                                Etudiants:
                                <?php foreach ($etudiant as $choixetudiant): ?>
                                    <option value='<?= $choixetudiant->id ?>'><?= $choixetudiant->firstName ?> <?= $choixetudiant->lastName ?></option>
                                <?php endforeach; ?>
                            </select></td></tr>
                    <tr><td>Début:<input id="startdate" type="date"  name="dateDebut"></td></tr>
                    <tr><td>Fin: <input id="finishdate" type="date"  name="dateFin"></td></tr>
                    <td><input type="submit" value='ajouter'> <input type="reset" value='annuler'></td>  
                </table>
            </form> 
        </div>
        <div id="confirm_dialog" title="Confirmation de la suppresion" hidden>
            <p>Veuillez confirmer la suppression du certificat de <b><span id="certif_to_delete_Firstname"></span></b>
                , <b><span id="certif_to_delete_Lastname"></span></b>
                couvrant la période du <b><span id="certif_to_delete_datestart"></span></b> au 
                <b><span id="certif_to_delete_enddate"></span></b>.</p>
        </div>
        <div id="easter" title="" hidden>
            <iframe src="https://giphy.com/embed/11jacPItBsJDLa" width="480" height="360" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/coding-11jacPItBsJDLa">via GIPHY</a></p>
        </div>    
    </body>
</html>
