<?php


namespace konference\Controllers;

use konference\Models\Alerts;
use konference\Models\DatabaseModel;
use konference\Models\Utilities;

class UserArticlesController extends PageController {

    public function __construct() {
        parent::__construct();
    }

    public function show(string $pageTitle): array {
        $tplData = parent::show($pageTitle);

        if(!$this->login->isUserLogged()) {
            Utilities::redirect("login");
            return [];
        }

        $articleNumber = $this->db->getNumberOfUsersArticles($this->login->getUserId());

        $app = 6;

        if(isset($_GET['p'])) {
            $tplData['page'] = htmlspecialchars($_GET['p']);
        } else {
            $tplData['page'] = 1;
        }

        $tplData['pages'] = ceil($articleNumber/$app);
        if($tplData['pages'] < $tplData['page']) {
            $tplData['page'] = $tplData['pages'];
        }
        if($tplData['page'] < 1) {
            $tplData['page'] = 1;
        }

        $tplData['articles'] = $this->db->getUsersArticles($this->login->getUserId(), $tplData['page'], $app,100);

        return $tplData;

    }
}