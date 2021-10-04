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
class ReviewerArticlesToReviewController extends PageController {

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
            header('Location: ?page=404');
            return $tplData;
        }

        if($tplData['userInfo']['id_role'] != Roles::ROLE_REVIEWER) {
            header('Location: ?page=404');
            return $tplData;
        }


        $app = 5;
        if(isset($_GET['p'])) {
            $tplData['page'] = htmlspecialchars($_GET['p']);
        } else {
            $tplData['page'] = 1;
        }

        $number = $this->db->getNumberOfUsersReviews($this->login->getUserId(), "state = 0");
        $tplData['pages'] = ceil($number/$app);
        if($tplData['pages'] < $tplData['page']) {
            $tplData['page'] = $tplData['pages'];
        }
        if($tplData['page'] < 1) {
            $tplData['page'] = 1;
        }

        $tplData['articles'] = $this->db->getUsersReviews($this->login->getUserId(), $tplData['page'], $app, "r.state = 0");

        //$tplData['articles'] = $this->db->getAllArticles($tplData['page'], $app, 100, "state=0 AND id_user = " . $this->login->getUserId());

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}

?>
