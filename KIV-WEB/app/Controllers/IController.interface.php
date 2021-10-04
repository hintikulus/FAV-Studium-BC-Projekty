<?php

namespace konference\Controllers;

/**
 * Rozhrani pro vsechny ovladace (kontrolery).
 * @package kivweb\Controllers
 */
interface IController {

    /**
     * Zajisti vypsani prislusne stranky.
     *
     * @param string $pageTitle     Nazev stanky.
     * @return array                Vytvorena data pro sablonu.
     */
    public function show(string $pageTitle):array;


}

?>
