<?php

namespace konference\Controllers;

use konference\Models\DatabaseModel;
use konference\Models\UserLoginModel;

require_once(DIRECTORY_MODELS.'/UserLoginModel.class.php');
// nactu rozhrani kontroleru
//require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 * @package kivweb\Controllers
 */
class IntroductionController extends PageController {


    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array {
        $tplData = parent::show($pageTitle);

        $app = 5;
        $search = "";

        if(isset($_GET['p'])) {
            $tplData['page'] = htmlspecialchars($_GET['p']);
        } else {
            $tplData['page'] = 1;
        }

        if(!empty($_GET['search'])) {
            $search = htmlspecialchars($_GET['search']);
            $tplData['pages'] = ceil($this->db->getNumberOfArticles("state=1", $search)/$app);
            if($tplData['pages'] < $tplData['page']) {
                $tplData['page'] = $tplData['pages'];
            }
            if($tplData['page'] < 1) {
                $tplData['page'] = 1;
            }
            $tplData['articles'] = $this->db->getAllArticles($tplData['page'], $app, 100, "state=1", $search);
        } else {
            $tplData['pages'] = ceil($this->db->getNumberOfArticles("state=1")/$app);
            if($tplData['pages'] < $tplData['page']) {
                $tplData['page'] = $tplData['pages'];
            }
            if($tplData['page'] < 1) {
                $tplData['page'] = 1;
            }
            $tplData['articles'] = $this->db->getAllArticles($tplData['page'], $app, 100, "state=1");
        }

        // vratim sablonu naplnenou daty
        return $tplData;
    }
    
}

?>
