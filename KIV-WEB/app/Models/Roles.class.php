<?php


namespace konference\Models;

/**
 * Třída pro konstanty rolí
 * @package konference\Models
 */
class Roles {

    /**
     *  Číselné identifikátory rolí
     */
    const ROLE_NOT_LOGGED = 0;
    const ROLE_AUTHOR = 1;
    const ROLE_REVIEWER = 2;
    const ROLE_ADMINISTRATOR = 3;

    /**
     *  Textová reprezentace rolí
     */
    const ROLE_NOT_LOGGED_STRING = "Nepřihlášený uživatel";
    const ROLE_AUTHOR_STRING = "Autor";
    const ROLE_REVIEWER_STRING = "Recenzent";
    const ROLE_ADMINISTRATOR_STRING = "Administrátor";

    /**
     * Funkce na základě zadého ID role vrací jeho textový ekvivalent
     * @param int $roleId číselná identifikace role
     * @return string textová identifikace role
     */
    public static function roleString(int $roleId):string {
        switch($roleId) {
            case Roles::ROLE_NOT_LOGGED: return Roles::ROLE_NOT_LOGGED_STRING;
            case Roles::ROLE_AUTHOR: return Roles::ROLE_AUTHOR_STRING;
            case Roles::ROLE_REVIEWER: return Roles::ROLE_REVIEWER_STRING;
            case Roles::ROLE_ADMINISTRATOR: return Roles::ROLE_ADMINISTRATOR_STRING;
        }

        return "";
    }

}
