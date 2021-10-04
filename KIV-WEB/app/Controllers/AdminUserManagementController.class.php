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
class AdminUserManagementController extends PageController {

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

        //// neprisel pozadavek na smazani uzivatele?
        if(isset($_POST['action']) and isset($_POST['id_user'])) {
            if($_POST['action'] == "delete") {
                // provedu smazani uzivatele
                $ok = $this->db->deleteUser(intval($_POST['id_user']));
                if($ok){
                    $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_USER_DELETED;
                } else {
                    $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_ADMIN_USER_NOT_DELETED;
                }
            }
            if($_POST['action'] == "roleSet") {
                if(isset($_POST['adminUserManagementRole'])) {
                    $ok = $this->db->setRole(intval($_POST['id_user']), intval($_POST['adminUserManagementRole']));
                    if($ok) {
                        $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_USER_ROLE_SET;
                    } else {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }
            }
        }

        $upp = 10;

        if(isset($_GET['p'])) {
            $tplData['page'] = htmlspecialchars($_GET['p']);
        } else {
            $tplData['page'] = 1;
        }

        $usersNumber = $this->db->getNumberOfUsers();

        $tplData['pages'] = ceil($usersNumber/$upp);
        if($tplData['pages'] < $tplData['page']) {
            $tplData['page'] = $tplData['pages'];
        }
        if($tplData['page'] < 1) {
            $tplData['page'] = 1;
        }
        //// nactu aktualni data uzivatelu
        $tplData['users'] = $this->db->getAllUsers($tplData['page'], $upp);

        // vratim sablonu naplnenou daty
        return $tplData;
    }

}

?>
