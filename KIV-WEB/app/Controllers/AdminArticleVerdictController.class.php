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
class AdminArticleVerdictController extends PageController {

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
            return [];
        }

        if($tplData['userInfo']['id_role'] != Roles::ROLE_ADMINISTRATOR) {
            header('Location: ?page=404');
            return $tplData;
        }

        if(isset($_POST['action'])) {
            if($_POST['action'] == "removeArticle") {
                if(isset($_POST['id_article'])) {
                    if($this->db->removeArticle($_POST['id_article'])) {
                        $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_ARTICLE_REMOVED;
                    } else {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }
            }

            if($_POST['action'] == "passArticle") {
                if($this->db->passArticle($_POST['id_article'])) {
                    $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_ARTICLE_PASSED;
                } else {
                    $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                }
            }

            if($_POST['action'] == "rejectArticle") {
                if($this->db->rejectArticle($_POST['id_article'])) {
                    $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_ARTICLE_REJECTED;
                } else {
                    $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                }
            }
        }

        $articlesNumber = $this->db->getNumberOfArticlesToVerdict();

        $app = 10;

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

        $tplData['articles'] = $this->db->getArticlesToVerdict($tplData['page'], $app);

        return $tplData;
    }

}

?>
