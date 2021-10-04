<?php

namespace konference\Views\TemplateAdministration;

use konference\Views\IView;
use konference\Views\TemplatePlain\TemplatePlain;

/**
 * Třída vypisující HTML hlavišku a patičku stránky.
 * @package kivweb\Views\TemplatePlain
 */
class TemplateAdministration extends TemplatePlain implements IView {

    const PAGE_ADMIN_USER_MANAGEMENT = "AdminUserManagementTemplate.tpl.php";
    const PAGE_ADMIN_REVIEW_MANAGEMENT = "AdminArticleReviewAssignTemplate.tpl.php";
    const PAGE_ADMIN_ARTICLE_MANAGEMENT = "AdminArticleManagementTemplate.tpl.php";
    const PAGE_ADMIN_ARTICLE_VERDICT = "AdminArticleVerdictTemplate.tpl.php";

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $templateData       Data stranky.
     * @param string $pageType          Typ vypisovane stranky.
     */
    public function printOutput(array $templateData, string $pageType = self::PAGE_INTRODUCTION)
    {
        //// vypis hlavicky
        $this->getHTMLHeader($templateData);

        //// vypis sablony obsahu
        // data pro sablonu nastavim globalni
        global $tplData;
        $tplData = $templateData;
        // nactu sablonu
        require_once($pageType);

        //// vypis pacicky
        $this->getHTMLFooter();
    }


    /**
     * Vrátí vrchní část stránky (hlavičku) až po oblast vypisovanou aplikací.
     * Vypisovaná část obsahuje definici a hlavičku HTML souboru a navigaci.
     *  @param string $pageTitle    Nazev stranky.
     */
    public function getHTMLHeader(array $tplData) {
        parent::getHTMLHeader($tplData);
        ?>
            <div class="row mt-4">
                <div class="col-md-3 col-lg-2">
                    <h4 class="h4">Administrace</h4>
                    <nav class="">
                        <ul class="nav nav-pills flex-column">

                            <li class="nav-item">
                                <a class="nav-link" href="?page=admin-user">Správa uživatelů</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=admin-articles">Správa článků</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=admin-reviews">Články pro přiřazení recenzentů</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?page=admin-verdict">Orecenzované články</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-9 col-lg-10">
                    <h3 class="h3"><?= $tplData['title']; ?></h3>
        <?php
    }

    /**
     *  Vrátí patičku stránky
     */
    public function getHTMLFooter(){
        ?>

                </div>
            </div>
        <?php
        parent::getHTMLFooter();
        ?>


        <?php
    }
        
}

?>
