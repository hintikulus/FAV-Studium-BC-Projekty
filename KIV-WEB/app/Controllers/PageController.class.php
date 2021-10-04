<?php


namespace konference\Controllers;
use konference\Models\Alerts;
use konference\Models\DatabaseModel;
use konference\Models\UserLoginModel;


class PageController implements IController {

    protected $login;
    protected $db;

    public function __construct() {
        $this->login = new UserLoginModel();
        $this->db = DatabaseModel::getDatabaseModel();
    }

    public function show(string $pageTitle): array {
        $tplData = Alerts::alertArray();

        $tplData['title'] = $pageTitle;

        if($this->login->isUserLogged()) {
            $tplData['logged'] = $this->login->getUserName();
            $tplData['userInfo'] = $this->db->getUserInfo($this->login->getUserId());
        }

        return $tplData;
    }
}