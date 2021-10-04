<?php


namespace konference\Models;

/**
 * Třída pro práci s přihlašováním
 * @package konference\Models
 */
class UserLoginModel {

    private $ses;

    private $dUserId = "id";
    private $dUserName = "username";
    private $dDate = "datum";

    public function __construct() {
        require_once("UserSessionModel.class.php");
        $this->ses = new UserSessionModel();
    }

    /**
     * Funkce pro zjištění, zda je uživatel přihlášen
     * @return bool uživatel je přihlášen
     */
    public function isUserLogged():bool {
        return $this->ses->isSessionSet($this->dUserName);
    }

    /**
     * Funkce pro přihlášení uživatele
     * @param int $userId identifikátor uživatele
     * @param string $userName uživatelské jméno (login) uživatele
     */
    public function login(int $userId, string $userName) {
        $this->ses->addSession($this->dUserId, $userId);
        $this->ses->addSession($this->dUserName, $userName);
        $this->ses->addSession($this->dDate, date("d. m. Y, G:m:s"));
    }

    /**
     * Funkce pro získání uživatelského identifikátoru
     * @return int identifikátor uživatele
     */
    public function getUserId():int {
        if($this->isUserLogged()) {
            return (int) $this->ses->readSession($this->dUserId);
        }

        return 0;
    }

    /**
     * Funkce pro získání uživatelského jména (loginu) uživatele
     * @return mixed|null uživatelské jméno
     */
    public function getUserName() {
        if($this->isUserLogged()) {
            return $this->ses->readSession($this->dUserName);
        }

        return null;
    }

    /**
     * Funkce pro odhlášení uživatele
     */
    public function logout() {
        $this->ses->removeSession($this->dUserName);
        $this->ses->removeSession($this->dDate);
    }


}