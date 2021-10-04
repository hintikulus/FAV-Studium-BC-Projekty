<?php


namespace konference\Controllers;

use konference\Models\Alerts;
use konference\Models\DatabaseModel;
use konference\Models\Utilities;

class ArticlePostController extends PageController {

    public function __construct() {
        parent::__construct();
    }

    public function show(string $pageTitle): array {
        $tplData = parent::show($pageTitle);

        if(!$this->login->isUserLogged()) {
            header('Location: ?page=login');
            return $tplData;
        }

        if(isset($_POST['articlePostSubmit'])) {
            if(isset($_POST['articlePostTitle']) && !empty($_POST['articlePostTitle']
                    && isset($_POST['articlePostAbstract']) && !empty($_POST['articlePostAbstract']))) {

                if(!empty($_FILES['articlePostFile']['name'])) {
                    if($_FILES['articlePostFile']['error'] != 0) {
                        $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_ARTICLE_POST_FILE_ERROR;
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_ARTICLE_POST_NOT_PUBLISHED;
                    } else if($_FILES['articlePostFile']['type'] != "application/pdf") {
                        $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_ARTICLE_POST_BAD_FILE_TYPE;
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_ARTICLE_POST_NOT_PUBLISHED;
                    } else {
                        $title = htmlspecialchars($_POST['articlePostTitle']);
                        $abstract = $_POST['articlePostAbstract'];

                        $file_tmp = $_FILES['articlePostFile']['tmp_name'];
                        if($pdf_blob = fopen($file_tmp, "rb")) {
                            if(($id = $this->db->postArticle($this->login->getUserId(), $title, $abstract, $pdf_blob)) > 0) {
                                Utilities::redirect("article&article=" . $id);
                                return $tplData;
                            }
                        }
                    }
                } else {
                    $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_ARTICLE_POST_NO_FILE;
                    $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_ARTICLE_POST_NOT_PUBLISHED;
                }
            } else {
                $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_FORM_NOT_COMPLETE;
                $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_ARTICLE_POST_NOT_PUBLISHED;
            }
        }


        $tplData['userInfo'] = $this->db->getUserInfo($this->login->getUserId());

        return $tplData;

    }
}