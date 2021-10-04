<?php


namespace konference\Models;

/**
 * Třída pro konstatny pro statusy
 * @package konference\Models
 */
class States {

    /**
     *  Číselné identifikátory statusů příspěvků
     */

    const ARTICLE_STATE_NOT_PUBLISHED = 0;
    const ARTICLE_STATE_PUBLISHED = 1;
    const ARTICLE_STATE_DENIED = 2;

    /**
     *  Textové reprezentace statusů příspěvků
     */

    const ARTICLE_STATE_NOT_PUBLISHED_STR = "Nepublikováno";
    const ARTICLE_STATE_PUBLISHED_STR = "Publikováno";
    const ARTICLE_STATE_DENIED_STR = "Zamítnuto";

    /**
     * Číselné identifikátory statusů recenzí
     */

    const REVIEW_STATE_ASSIGNED = 0;
    const REVIEW_STATE_PUBLISHED = 1;

    /**
     * Funkce pro získání textového statusu článku
     * @param int $state identifikátoru statusu
     * @return string textová reprezentace statusu
     */
    public static function articleStateToString(int $state):string {
        switch ($state) {
            case self::ARTICLE_STATE_NOT_PUBLISHED: return self::ARTICLE_STATE_NOT_PUBLISHED_STR;
            case self::ARTICLE_STATE_PUBLISHED: return self::ARTICLE_STATE_PUBLISHED_STR;
            case self::ARTICLE_STATE_DENIED: return self::ARTICLE_STATE_DENIED_STR;
        }

        return "";
    }

}