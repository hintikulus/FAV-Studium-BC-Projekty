<?php

namespace konference\Views;

/**
 * Rozhrani pro vsechny sablony (views).
 * @package konference\Views
 */
interface IView {

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $tplData   Data stranky.
     */
    public function printOutput(array $tplData);

}

?>
