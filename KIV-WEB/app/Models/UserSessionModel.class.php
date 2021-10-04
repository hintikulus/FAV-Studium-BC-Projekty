<?php


namespace konference\Models;

/**
 * Třída pro práci se sezením
 * @package konference\Models
 */
class UserSessionModel {

    /**
     * Konstruktor pro založení session
     * UserSessionModel constructor.
     */
    public function __construct() {
        session_start();
    }

    /**
     * Funkce pro přidání hodnoty do sezení
     * @param string $key klíč hodnoty
     * @param mixed $value ukládaná hodnota
     */
    public function addSession(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Funkce pro kontrolu existence hodnoty v sezení
     * @param string $key klíč hodnoty
     * @return bool klíč je uložen v sezení
     */
    public function isSessionSet(string $key):bool {
        return isset($_SESSION[$key]);
    }

    /**
     * Funkce pro čtení hodnoty ze sezení
     * @param string $key klíč hodnoty
     * @return mixed|null hodnota nalezená podle klíče
     */
    public function readSession(string $key) {
        if($this->isSessionSet($key)) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }

    /**
     * Funkce pro odebrání všech hodnoty ze sezení
     * @param string $key klíč hodnoty
     */
    public function removeSession(string $key) {
        unset($_SESSION[$key]);
    }
}