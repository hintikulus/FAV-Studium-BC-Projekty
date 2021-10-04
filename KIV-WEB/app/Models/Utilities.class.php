<?php


namespace konference\Models;


class Utilities {

    /**
     * Kontrola pro validaci hesla (zda obsahuje tečku a zavináč)
     * @param string $email řetězec pro testování
     * @return bool řetězec splňuje podmínky emailové adresy
     */
    public static function checkEmail(string $email):bool {
        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');
        return ($find1 !== false && $find2 !== false && $find2 > $find1);
    }

    /**
     * Funkce pro ověření validace hesla
     * @param string $password heslo v čistém tvaru
     * @return bool pravdivostní hodnota validace hesla
     */
    public static function passWordValidation(string $password):bool {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        if(!$uppercase || !$lowercase || !$number|| strlen($password) < 8) {
            return false;
        }

        return true;
    }

    /**
     * Funkce testovací, zda zadaný řetězec od CKEditoru neobsahuje nepovolené značky
     * @param string $s řetězec pro testování
     * @return bool řetězec neobsahuje testované značky
     */
    public static function checkCKEditorString(string $s):bool {
        if(str_pos($s, "<form") || str_pos($s, "</form>")) return false;
        if(str_pos($s, "<script") || str_pos($s, "</script>")) return false;
        if(str_pos($s, "onclick")) return false;

        return true;
    }

    /**
     * Funkce vypíše pět hvězd na základě zadaného hodnocení
     * @param int $count hodnocení (0-10)
     * @return string řetězec s obsaženými ikonami hvězd podle hodnocení
     */
    public static function stars(int $count):string {
        /*
         * Kontrola vstupních dat
         */
        if($count < 0) $count = 0;
        if($count > 10) $count = 10;

        /*
         * Zahájení plnění výsledného řetězce hvězdami
         */
        $result = "";

        for($i = 1; $i <= 5; $i++) {
            if($i*2 <= $count) {
                $result .= '<i class="fas fa-star"></i>';
            } else if(($i*2)-1 == $count) {
                $result .= '<i class="fas fa-star-half-alt"></i>';
            } else {
                $result .= '<i class="far fa-star"></i>';
            }
        }

        return $result;

    }

    /**
     * Funkce na vytvoření a vypsání jednoduchého paginationu (stránkovací navigace)
     * @param string $link odkaz (stránka) konferenčního systému na kterou bude odkazovat
     * @param int $page číslo strany na které se momentálně uživatel nachází
     * @param int $pages počet stran na stránce
     */
    public static function paginaton(string $link, int $page = 1, int $pages) {
        /*
         * Kontrola vstupních řetězců
         */
        if($pages == 0) return;

        if($page >  $pages) {
            $page = $pages;
        }

        if($page < 1) {
            $page = 1;
        }

        /*
         * Modifikace počtu navrhovaných stran
         */
        if($page < 4) {
            $i = 1;
        } else {
            $i = $page - 2;
        }

        $b = $i + 4;

        if($b >= $pages) {
            if($pages < 5) {
                $b = $pages;
                $i = 1;
            } else {
                $b = $pages;
                $i = $b - 4;
            }
        }

        /*
         * Výpis navigace
         */
        ?>
            <nav aria-label="Stránkovací navigace" class="d-flex justify-content-center mt-3">
              <ul class="pagination">
                <li class="page-item <?php if($page == 1) echo "disabled"; ?>">
                  <a class="page-link" href="?page=<?= $link.'&p='.($page-1); ?>" aria-label="Předcházející">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                  <?php
                  for($i; $i <= $b; $i++) {
                      if($i == $page) {
                          echo '<li class="page-item active"><a class="page-link" href="?page='.$link.'&p='.$i.'">'.$i.'</a></li>';
                      } else {
                          echo '<li class="page-item"><a class="page-link" href="?page='.$link.'&p='.$i.'">'.$i.'</a></li>';
                      }
                  }

                  ?>
                <li class="page-item <?php if($page == $pages) echo "disabled"; ?>">
                  <a class="page-link" href="?page=<?= $link.'&p='.($page+1); ?>" aria-label="Následující">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
        <?php

        echo "Strana " . $page . " z " . $pages;
    }

    public static function printFormated(string $str) {
        return preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|p|strong|blockquote|h1|h2|h3|h4|h5|h6
                                |s|hr|br))&gt;#', '<\1>', $str);
    }

    public static function printUnformated(string $str) {
        return preg_replace('#&lt;(/?(?:pre|b|em|u|ul|li|ol|p|strong|blockquote|h1|h2|h3|h4|h5|h6
                                |s|hr|br))&gt;#', ' ', $str);
    }


    /**
     * Funkce pro přesměrování na jinou stránku
     * @param string $page stránka konferečního systému (defaultně úvod)
     */
    public static function redirect(string $page = "uvod") {
        header("Location: ?page=" . $page);
    }
}