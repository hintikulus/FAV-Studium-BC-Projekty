<?php


namespace konference\Controllers;

use konference\Models\DatabaseModel;

class UserProfileController extends PageController {

    public function __construct() {
        parent::__construct();
    }

    public function show(string $pageTitle): array {
        $tplData = parent::show($pageTitle);

        if(isset($_GET['user'])) {
            $userInfo = $this->db->getUserInfoByUserName($_GET['user']);

            if($userInfo == null) {
                header("Location: ?page=404");
            }

            $articleNumber = $this->db->getNumberOfUsersArticles($userInfo['id_user'], "state=1");
            $tplData['profileInfo'] = $userInfo;
            $tplData['userNumberArticles'] = $articleNumber;

            $app = 2;

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

            $tplData['articles'] = $this->db->getUsersArticles($userInfo['id_user'], $tplData['page'], $app, 100, "state=1");
        }

        return $tplData;

    }
}