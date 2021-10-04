<?php


namespace konference\Controllers;

use konference\Models\Alerts;
use konference\Models\DatabaseModel;
use konference\Models\Roles;
use konference\Models\Utilities;

class ArticleShowController extends PageController {

    public function __construct() {
        parent::__construct();
    }

    public function show(string $pageTitle):array {
        $tplData = parent::show($pageTitle);
        //// vsechna data sablony budou globalni
        ///
        // data pohadek
        if(!isset($_GET['article'])) {
            return $tplData;
        }

        $articleId = $_GET['article'];

        $tplData['reviewNumber'] = $this->db->getNumberOfArticleReviews($articleId, "state=1");

        if(isset($_POST['action']) && $tplData['userInfo']['id_role'] == Roles::ROLE_ADMINISTRATOR) {
            if($_POST['action'] == "removeArticle") {
                if($this->db->removeArticle($articleId)) {
                    Utilities::redirect("admin-reviews");
                    return $tplData;
                } else {
                    $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                }
            }

            if($tplData['reviewNumber'] >= 3) {
                if ($_POST['action'] == "passArticle") {
                    if ($this->db->passArticle($articleId)) {
                        $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_ARTICLE_PASSED;
                    } else {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }

                if ($_POST['action'] == "rejectArticle") {
                    if ($this->db->rejectArticle($articleId)) {
                        $tplData[Alerts::ALERTS_SUCCESS][] = Alerts::SUCCESS_ADMIN_ARTICLE_REJECTED;
                    } else {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }
            }
        }

        if($articleId <= 0) {
            return $tplData;
        }

        $tplData['articleInfo'] = $this->db->getArticle($articleId);

        if(isset($_POST['reviewPostSubmit'])) {
            if(isset($_POST['reviewPostEvaluation1']) &&
                isset($_POST['reviewPostEvaluation2']) &&
                isset($_POST['reviewPostEvaluation3']) &&
                isset($_POST['reviewPostEvaluation4']) &&
                isset($_POST['reviewPostDecision'])
            ) {
                $ev1 = intval($_POST['reviewPostEvaluation1']);
                $ev2 = intval($_POST['reviewPostEvaluation2']);
                $ev3 = intval($_POST['reviewPostEvaluation3']);
                $ev4 = intval($_POST['reviewPostEvaluation4']);

                if($ev1 > 10 || $ev1 < 0) $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_REVIEW_POST_BAD_EVALUATION_VALUES;
                if($ev2 > 10 || $ev2 < 0) $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_REVIEW_POST_BAD_EVALUATION_VALUES;
                if($ev3 > 10 || $ev3 < 0) $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_REVIEW_POST_BAD_EVALUATION_VALUES;
                if($ev4 > 10 || $ev4 < 0) $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_REVIEW_POST_BAD_EVALUATION_VALUES;

                $text = "";
                if(isset($_POST['reviewPostTextEvaluation']) && !empty($_POST['reviewPostTextEvaluation'])) {
                    $text = $_POST['reviewPostTextEvaluation'];
                }

                $decision = intval($_POST['reviewPostDecision']);

                if(empty($tplData[Alerts::ALERTS_WARNING])) {
                    if(!$this->db->postArticleReview($articleId, $this->login->getUserId(), $text, $ev1, $ev2, $ev3, $ev4, $decision)) {
                        $tplData[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
                    }
                }
            } else {
                $tplData[Alerts::ALERTS_WARNING][] = Alerts::WARNING_FORM_NOT_COMPLETE;
            }
        }

        $reviewNumber = $tplData['reviewNumber'];
        $app = 2;

        if(isset($_GET['p'])) {
            $tplData['page'] = htmlspecialchars($_GET['p']);
        } else {
            $tplData['page'] = 1;
        }

        $tplData['pages'] = ceil($reviewNumber/$app);
        if($tplData['pages'] < $tplData['page']) {
            $tplData['page'] = $tplData['pages'];
        }
        if($tplData['page'] < 1) {
            $tplData['page'] = 1;
        }

        $tplData['reviews'] = $this->db->getAllArticleReviews($articleId, $tplData['page'], $app, "state=1");


        if(!isset($tplData['articleInfo'])) {
            header("Location: ?page=404");
        }

        if($tplData['articleInfo']['state'] == 0 || $tplData['articleInfo']['state'] == 2) {
            if($tplData['articleInfo']['id_user'] != $this->login->getUserId()) {
                if ($tplData['userInfo']['id_role'] < Roles::ROLE_REVIEWER) {
                    header("Location: ?page=404");
                }
            }
        }

        if(isset($_POST['articleShowDownload'])) {
            $size = $tplData['articleInfo']['size'];
            $file = $tplData['articleInfo']['file'];
            $title = str_replace(" ", "_", $tplData['articleInfo']['title']) . ".pdf";
            header("Content-length: " . $size);
            header("Content-type: application/pdf");
            header("Content-disposition: attachment; filename=" . $title);
            echo $file;
            ob_clean();
            flush();
            exit;
        }

        $tplData['toReview'] = $this->db->isUserAssignedArticleToReview($articleId, $this->login->getUserId()) &&
            $tplData['userInfo']['id_role'] == Roles::ROLE_REVIEWER;
        // vratim sablonu naplnenou daty
        return $tplData;
    }
}