<?php

namespace konference\Views\TemplatePlain;

use konference\Models\Alerts;
use konference\Models\Roles;
use konference\Views\IView;

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 * @package kivweb\Views\TemplatePlain
 */
class TemplatePlain implements IView {

    /** @var string PAGE_INTRODUCTION  Sablona s uvodni strankou. */
    const PAGE_INTRODUCTION = "IntroductionTemplate.tpl.php";
    /** @var string PAGE_USER_MANAGEMENT  Sablona se spravou uzivatelu. */
    const PAGE_INFORMATION = "InformationTemplate.tpl.php";

    const PAGE_USER_REGISTRATION = "UserRegistrationTemplate.tpl.php";
    const PAGE_USER_LOGIN = "UserLoginTemplate.tpl.php";

    const PAGE_USER_PROFILE = "UserProfileTemplate.tpl.php";
    const PAGE_USER_EDIT = "UserEditTemplate.tpl.php";
    const PAGE_USER_ARTICLES = "UserArticlesTemplate.tpl.php";

    const PAGE_ARTICLE_POST = "ArticlePostTemplate.tpl.php";
    const PAGE_ARTICLE_SHOW = "ArticleShowTemplate.tpl.php";

    const PAGE_REVIEWER_ARTICLES_TO_REVIEW = "ReviewerArticlesToReviewTemplate.tpl.php";

    const PAGE_ERROR_404 = "404.tpl.php";


    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $templateData       Data stranky.
     * @param string $pageType          Typ vypisovane stranky.
     */
    public function printOutput(array $templateData, string $pageType = self::PAGE_INTRODUCTION)
    {
        //// vypis hlavicky
        $this->getHTMLHeader($templateData);

        //// vypis sablony obsahu
        // data pro sablonu nastavim globalni
        global $tplData;
        $tplData = $templateData;
        // nactu sablonu
        require_once($pageType);

        //// vypis pacicky
        $this->getHTMLFooter();
    }


    /**
     *  Vrati vrsek stranky az po oblast, ve ktere se vypisuje obsah stranky.
     *  @param string $pageTitle    Nazev stranky.
     */
    public function getHTMLHeader(array $tplData) {
        ?>

        <!doctype html>
        <html lang="cs" class="h-100">
            <head>
                <meta charset='utf-8'>
                <meta name="viewport" content="width=device-width, initial-scale=1">

                <title><?php echo WEB_TITLE; ?></title>


                <link rel="stylesheet" href="../vendor/components/font-awesome/css/all.css">
                <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.css">
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>

            </head>
            <body class="d-flex flex-column h-100">
                <header>
                    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                        <div class="container-fluid">
                            <a class="navbar-brand text-dark" href="index.php?page=uvod"><h1 class="h5"><?= WEB_TITLE; ?></h1></a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Otevřít navigaci">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarToggle">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li class="nav-item">
                                        <a class="nav-link active text-dark" href="index.php?page=uvod"><?= WEB_PAGES['uvod']['title']; ?></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link active text-dark" href="index.php?page=information"><?= WEB_PAGES['information']['title']; ?></a>
                                    </li>
                                    <?php if(isset($tplData['logged']) && $tplData['userInfo']['id_role'] == Roles::ROLE_ADMINISTRATOR) {?>
                                    <li class="nav-item">
                                        <a class="nav-link active text-dark" href="index.php?page=admin-user">Administrace</a>
                                    </li>
                                    <?php } ?>
                                    <?php if(isset($tplData['logged']) && $tplData['userInfo']['id_role'] == Roles::ROLE_REVIEWER) {?>
                                    <li class="nav-item">
                                        <a class="nav-link active text-dark" href="index.php?page=articles-to-review">Články k recenzování</a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <?php
                                if(isset($tplData['logged'])) {
                                ?>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Přihlášený uživatel: <?= $tplData['logged'] ?>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="?page=profile&user=<?= $tplData['logged']; ?>">Můj profil</a></li>
                                            <li><a class="dropdown-item" href="?page=user-articles">Moje příspěvky</a></li>
                                            <li><a class="dropdown-item" href="?page=post">Nový příspěvek</a></li>
                                            <li><a class="dropdown-item" href="?page=useredit">Upravit profil</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="?page=login&action=logout">Odhlásit se</a></li>
                                        </ul>
                                    </div>

                                <?php
                                } else {
                                ?>
                                    <div class="d-flex">
                                        <a class="btn btn-outline-primary me-2" href="?page=login">Přihlášení</a>
                                        <a class="btn btn-outline-danger" href="?page=registration">Registrace</a>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </nav>
                </header>
                <main class="container pt-4 flex-shrink-0">
        <?php
        Alerts::printAllAlerts($tplData);

    }
    
    /**
     *  Vrati paticku stranky.
     */
    public function getHTMLFooter(){
        ?>
                </main>
                <footer class="footer mt-auto py-3 bg-light">
                    <div class="container">
                        <span class="text-muted">Semestrální práce pro předmět KIV/WEB
                            - Webové stránky konferenčního systému</span>
                    </div>
                </footer>

                <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
                <script src="../vendor/components/jquery/jquery.min.js"></script>
                <script src="../vendor/alexandermatveev/popper-bundle/AlexanderMatveev/PopperBundle/Resources/public/popper.min.js"></script>
                <script src="../vendor/ckeditor/ckeditor/ckeditor.js"></script>

                <script>
                    var editor = document.getElementById("editor1");

                    if(editor) {
                        CKEDITOR.replace('editor1');
                    }

                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                </script>

            </body>
        </html>

        <?php
    }
}


?>
