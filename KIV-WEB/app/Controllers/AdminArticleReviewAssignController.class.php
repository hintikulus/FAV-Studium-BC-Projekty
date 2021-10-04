<?php

namespace konference\Controllers;

// ukazka aliasu
use konference\Models\Alerts;
use konference\Models\DatabaseModel as MyDB;
use konference\Models\Roles;

// nactu rozhrani kontroleru
//require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");


/**
 * Ovladac zajistujici vypsani stranky se spravou uzivatelu.
 * @package kivweb\Controllers
 */
class AdminArticleReviewAssignController extends PageController {

    /** @var MyDB $db  Sprava databaze. */

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Vrati obsah stranky se spravou uzivatelu.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = parent::show($pageTitle);

        if(!$this->login->isUserLogged()) {
            header("?page=404");
            return $tplData;
        }
        if($tplData['userInfo']['id_role'] != Roles::ROLE_ADMINISTRATOR) {
            header('Location: ?page=404');
            return $tplData;
        }

        if(isset($_POST['action'])) {
            if($_POST['action'] == "addReviewer") {
                if(isset($_POST['id_article']) && isset($_POST['id_user'])) {
                    if($this->db->assignArticleToReview($_POST['id_article'], $_POST['id_user'])) {
                        $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_REVIEW_ASSIGNED;
                    } else {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }
            }

            if($_POST['action'] == "removeReviewAssignment") {
                if(isset($_POST['id_review'])) {
                    if($this->db->removeReview($_POST['id_review'])) {
                        $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_REVIEW_REMOVED;
                    } else {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }
            }

            if($_POST['action'] == "removeArticle") {
                if(isset($_POST['id_article'])) {
                    if($this->db->removeArticle($_POST['id_article'])) {
                        $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_ARTICLE_REMOVED;
                    } else {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }
            }
        }

        $articlesNumber = $this->db->getNumberOfArticles("state=0");

        $app = 6;

        if(isset($_GET['p'])) {
            $tplData['page'] = htmlspecialchars($_GET['p']);
        } else {
            $tplData['page'] = 1;
        }

        $tplData['pages'] = ceil($articlesNumber/$app);
        if($tplData['pages'] < $tplData['page']) {
            $tplData['page'] = $tplData['pages'];
        }
        if($tplData['page'] < 1) {
            $tplData['page'] = 1;
        }

        $tplData['articles'] = $this->db->getAllArticles($tplData['page'], $app, 0, "state=0");

        foreach ($tplData['articles'] as &$article) {
            $article['reviewers'] = $this->db->getReviewsAssignment($article['id_article']);
        }

        $tplData['reviewers'] = $this->db->getAllReviewers();

        return $tplData;
    }

}

?>
