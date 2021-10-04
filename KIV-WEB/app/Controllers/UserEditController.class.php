<?php


namespace konference\Controllers;

use konference\Models\Alerts;
use konference\Models\DatabaseModel;
use konference\Models\Utilities;

class UserEditController extends PageController {


    public function __construct() {
        parent::__construct();
    }

    public function show(string $pageTitle): array {
        $tplData = parent::show($pageTitle);

        if(!$this->login->isUserLogged()) {
            header('Location: ?page=login');
            return [];
        }

        if(isset($_POST['userEditInfoSubmit'])) {
            if(isset($_POST['userEditInfoName']) && isset($_POST['userEditInfoSurName']) && (!empty($_POST['userEditInfoName']) && !empty($_POST['userEditInfoSurName']))) {
                $name = htmlspecialchars($_POST['userEditInfoName']);
                $surname = htmlspecialchars($_POST['userEditInfoSurName']);
                $this->db->execute("UPDATE " . TABLE_USERS . " SET name = ?, surname = ? WHERE id_user = ?",
                    $name, $surname, $this->login->getUserId());

                $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_USER_EDIT_INFO;
            } else {
                $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_EDIT_NOT_FILL;
                $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_USER_EDIT_INFO_NOT_CHANGED;
            }
        }


        if(isset($_POST['userEditBioSubmit'])) {
            $bio = "";
            if(isset($_POST['userEditBio'])) {
                $bio = htmlspecialchars($_POST['userEditBio'], ENT_QUOTES,'UTF-8',false);;
            }


            if($this->db->execute("UPDATE " . TABLE_USERS . " SET bio = ? WHERE id_user = ?", $bio, $this->login->getUserId())) {
                $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_USER_EDIT_BIO;
            } else {
                $tplDAta[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
            }
        }


        if(isset($_POST['userEditPassSubmit'])) {
            if(isset($_POST['userEditPassOld']) && isset($_POST['userEditPass1'])
                && isset($_POST['userEditPass2']) && !empty($_POST['userEditPassOld'])) {

                $pass1 = $_POST['userEditPass1'];
                $pass2 = $_POST['userEditPass2'];

                if($pass1 == $pass2) {
                    if (Utilities::passWordValidation($pass1)) {
                        $errorCode = $this->db->changeUserPassword($this->login->getUserId(), $_POST['userEditPassOld'], $pass1);

                        if($errorCode == 1) {
                            $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_USER_EDIT_PASS;
                        } else if($errorCode == 2) {
                            $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_EDIT_PASS_OLD_NOT_MATCH;
                            $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_USER_EDIT_PASS_NOT_CHANGED;
                        } else if($errorCode == 0) {
                            $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                        }
                    } else {
                        $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_EDIT_PASS_VALIDATION;
                        $tplData['danger'][] = Alerts::DANGER_USER_EDIT_PASS_NOT_CHANGED;
                    }
                } else {
                    $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_EDIT_PASS_NOT_MATCH;
                    $tplData['danger'][] = Alerts::DANGER_USER_EDIT_PASS_NOT_CHANGED;
                }
            } else {
                $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_EDIT_NOT_FILL;
                $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_USER_EDIT_INFO_NOT_CHANGED;
            }
        }

        $tplData['userInfo'] = $this->db->getUserInfo($this->login->getUserId());

        return $tplData;

    }
}