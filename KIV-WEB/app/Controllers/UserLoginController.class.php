<?php


namespace konference\Controllers;

use konference\Models\Alerts;
use konference\Models\DatabaseModel;
use konference\Models\Utilities;

class UserLoginController extends PageController {

    public function __construct() {
        parent::__construct();
    }

    public function show(string $pageTitle): array {
        $tplData = parent::show($pageTitle);

        if(isset($_GET['action']) && $_GET['action'] == 'logout') {
            $this->login->logout();
            Utilities::redirect();
        }

        if($this->login->isUserLogged()) {
            Utilities::redirect();
            return [];
        }

        if(isset($_POST['loginSubmit'])) {
            if(DatabaseModel::isset($_POST, "loginUserName", "loginPassWord")) {

                $data = $this->db->loginUser($_POST['loginUserName'], $_POST['loginPassWord']);

                if($data) {
                    $this->login->login($data['id_user'], $data['login']);
                    $tplData['logged'] = $data['login'];
                    Utilities::redirect();
                } else {
                    $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_LOGIN_INVALID_CREDENTIALS;
                }
            } else {
                $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_FORM_NOT_COMPLETE;
            }
        }

        return $tplData;

    }
}