<!DOCTYPE html>
<html class="background">

    <head>
        <meta charset="UTF-8">
        <title>Welcome to Moodle 2.0!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body >
        <div class="index">
            <div class="blocindex"><!--uniquement pour le css (former un bloc)-->
                <div class="titleindex">Welcome to Moodle 2.0!</div>
                <div class="menuindex">
                    <form class="formindex" method="POST" action="main/login">
                        <p><input class="btnindex" type="submit" name="login" value="login" ></p>
                    </form>
                </div>
            </div>
            <div class="blocindex">
                <h2>Première visite sur ce site ?</h2>
                <p>Bienvenue sur la plateforme pédagogique de l’Epfc communément appelé Moodle.</p>

                <p>Ce site web est destiné aux étudiants et professeurs de l’Epfc.</p>

                <p>Son contenu est uniquement accessible avec un identifiant et un mot de passe.</p>

                <p>Vous avez reçu par mail votre identifiant et mot de passe. Il a été envoyé le lendemain de votre inscription.
                    L’adresse email utilisée, pour l’envoi, est celle fournie lors de votre inscription.</p>
                <p>En cas de problème :</p>
                <p>Vérifier votre adresse email auprès de votre secrétariat</p>
                <p>Vous avez perdu vos informations de connexions (identifiant et/ou mot de passe), utilisez le lien situé à gauche de ce texte “Mot de passe/identifiant oublié ? Modification de votre mot de passe ?”</p>
                <p>Contacter le support informatique à support@epfc.eu</p>
                <p>Merci de mentionner votre nom, prénom et matricule étudiant</p>
                <p>Bon travail,
                    L’équipe du service Informatique</p>
            </div>
        </div>
    </body>
</html>
