<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

//// Pripojeni k databazi ////

/** Adresa serveru. */
define("DB_SERVER","db.example.com"); // https://students.kiv.zcu.cz lze 147.228.63.10, ale musite byt na VPN
/** Nazev databaze. */
define("DB_NAME","KIVWEB");
/** Uzivatel databaze. */
define("DB_USER","web");
/** Heslo uzivatele databaze */
define("DB_PASS","kivwebheslo");

//// Nazvy tabulek v DB ////

/** Tabulka s pohadkami. */
define("TABLE_USERS", "users");
/** Tabulka s uzivateli. */
define("TABLE_ROLES", "roles");

define("TABLE_ARTICLES", "articles");

define("TABLE_REVIEWS", "reviews");

//// Informace o webu ////
define("WEB_TITLE", "Konference TechMasters");

define("RECAPTCHA_SECRET", "6Ld7FxcaAAAAACK5_5dMQpRoB16DL-qfD6HVkO_Q");

/// Nastvení systému ///
define("NUMBER_OF_REVIEWS_TO_VERDICT", 3);

define("RECAPTCHA_VALIDATION", true);

//// Dostupne stranky webu ////

/** Adresar kontroleru. */
const DIRECTORY_CONTROLLERS = "../app/Controllers";
/** Adresar modelu. */
const DIRECTORY_MODELS = "../app/Models";
/** Adresar sablon */
const DIRECTORY_VIEWS = "../app/Views";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "uvod";

const ERROR_404_PAGE_KEY = "404";

/** Dostupne webove stranky. */
const WEB_PAGES = array(//// Uvodni stranka ////
    "uvod" => array(
        "title" => "Úvodní stránka",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \konference\Controllers\IntroductionController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        //"view_class_name" => \konference\Views\ClassBased\IntroductionTemplate::class,

        // TemplatePlain sablona
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_INTRODUCTION,
    ),
    //// KONEC: Uvodni stranka ////

    //// Sprava uzivatelu ////
    "admin-user" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        "controller_class_name" => \konference\Controllers\AdminUserManagementController::class,

        // TemplatePlain sablona
        "view_class_name" => \konference\Views\TemplateAdministration\TemplateAdministration::class,
        "template_type" => \konference\Views\TemplateAdministration\TemplateAdministration::PAGE_ADMIN_USER_MANAGEMENT,
    ),

    "admin-reviews" => array(
        "title" => "Správa recenzí",
        "controller_class_name" => \konference\Controllers\AdminArticleReviewAssignController::class,
        "view_class_name" => \konference\Views\TemplateAdministration\TemplateAdministration::class,
        "template_type" => \konference\Views\TemplateAdministration\TemplateAdministration::PAGE_ADMIN_REVIEW_MANAGEMENT,
    ),

    "articles-to-review" => array(
        "title" => "Články k recenzování",
        "controller_class_name" => \konference\Controllers\ReviewerArticlesToReviewController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_REVIEWER_ARTICLES_TO_REVIEW,
    ),
    //// KONEC: Sprava uzivatelu ////

    "information" => array(
        "title" => "Informace",
        "controller_class_name" => \konference\Controllers\PageController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_INFORMATION,
    ),

    "admin-verdict" => array(
        "title" => "Schvalování příspěvků",
        "controller_class_name" => \konference\Controllers\AdminArticleVerdictController::class,
        "view_class_name" => \konference\Views\TemplateAdministration\TemplateAdministration::class,
        "template_type" => \konference\Views\TemplateAdministration\TemplateAdministration::PAGE_ADMIN_ARTICLE_VERDICT,
    ),

    "admin-articles" => array(
        "title" => "Správa příspěvků",
        "controller_class_name" => \konference\Controllers\AdminArticleManagementController::class,
        "view_class_name" => \konference\Views\TemplateAdministration\TemplateAdministration::class,
        "template_type" => \konference\Views\TemplateAdministration\TemplateAdministration::PAGE_ADMIN_ARTICLE_MANAGEMENT,
    ),

    "post" => array(
        "title" => "Publikování článku",
        "controller_class_name" => \konference\Controllers\ArticlePostController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_ARTICLE_POST,

    ),

    "article" => array(
        "title" => "Zobrazení článku",
        "controller_class_name" => \konference\Controllers\ArticleShowController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_ARTICLE_SHOW,
    ),

    "articlecreate" => array(
        "title" => "Vytvoření příspěvku"

    ),

    "registration" => array(
        "title" => "Registrace nového uživatele",
        "controller_class_name" => \konference\Controllers\UserRegistrationController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_USER_REGISTRATION,

    ),

    "login" => array(
        "title" => "Přihlášení uživatele",
        "controller_class_name" => \konference\Controllers\UserLoginController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_USER_LOGIN,
    ),

    "useredit" => array(
        "title" => "Úprava profilu",
        "controller_class_name" => \konference\Controllers\UserEditController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_USER_EDIT,
    ),

    "profile" => array(
        "title" => "Profil uživatele",
        "controller_class_name" => \konference\Controllers\UserProfileController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_USER_PROFILE,
    ),

    "user-articles" => array(
        "title" => "Články uživatele",
        "controller_class_name" => \konference\Controllers\UserArticlesController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_USER_ARTICLES,
    ),

    "404" => array(
        "title" => "Error 404",
        "controller_class_name" => \konference\Controllers\PageController::class,
        "view_class_name" => \konference\Views\TemplatePlain\TemplatePlain::class,
        "template_type" => \konference\Views\TemplatePlain\TemplatePlain::PAGE_ERROR_404
    )



);

?>
