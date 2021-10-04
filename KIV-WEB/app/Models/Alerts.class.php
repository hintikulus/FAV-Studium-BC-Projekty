<?php


namespace konference\Models;

/**
 * Třída pro práci s alerty (upozorněními)
 * @package konference\Models
 */
class Alerts {

    const ALERT_SUCCESS = "Úspěch";
    const ALERT_WARNING = "Upozornění";
    const ALERT_DANGER = "Chyba";

    const ALERTS_SUCCESS = "success";
    const ALERTS_WARNING = "warning";
    const ALERTS_DANGER = "danger";

    const WARNING_FORM_NOT_COMPLETE = "Nebyly zadány veškeré povinné informace.";
    const DANGER_UNEXPECTED_ERROR = "Vyskytla se neočekávaná chyba.";
    const WARNING_RECAPTCHA = "Opravdu nejsi robot?";

    /**
     * Alerty pro editaci uživatelských informací
     */
    const SUCCESS_USER_EDIT_INFO = "Uživatelské informace byly úspěšně změněny.";
    const SUCCESS_USER_EDIT_BIO = "Uživatelova biografie byla úspěšně upravena.";
    const SUCCESS_USER_EDIT_PASS = "Uživatelské heslo bylo úspěšně změněno.";

    const WARNING_USER_EDIT_PASS_VALIDATION = "Zadané heslo neplňuje minimální podmínky hesla. Heslo musí být dlouhé 
        minimálně 8 znaků a obsahovat minimálně jedno malé písmeno, velké písmeno a číslici.";
    const WARNING_USER_EDIT_PASS_NOT_MATCH = "Zadaná hesla nejsou shodná.";
    const WARNING_USER_EDIT_PASS_OLD_NOT_MATCH = "Aktuální heslo není správné.";
    const DANGER_USER_EDIT_PASS_NOT_CHANGED = "Heslo nebylo změněno.";

    const WARNING_USER_EDIT_NOT_FILL = "Nebyly zadány veškeré povinné údaje.";

    const DANGER_USER_EDIT_INFO_NOT_CHANGED = "Uživatelské informace nebyly změněny.";

    /**
     * Alerty pro přihlašování
     */
    const WARNING_USER_LOGIN_INVALID_CREDENTIALS = "Uživatelské jméno nebo heslo není správné.";

    /**
     * Alerty pro registraci
     */
    const WARNING_USER_REG_USERNAME_EXISTS = "Uživatel se zadaným uživatelským jménem již existuje.";
    const WARNING_USER_REG_EMAIL_EXISTS = "Uživatel se zadanou emailovou adresou již existuje.";
    const WARNING_USER_REG_PASS_NOT_MATCH = "Zadaná hesla se neshodují.";
    const WARNING_USER_REG_PASS_VALIDATION = "Zadané heslo neplňuje minimální podmínky hesla. Heslo musí být dlouhé 
        minimálně 8 znaků a obsahovat minimálně jedno malé písmeno, velké písmeno a číslici.";

    /**
     * Alerty pro postování příspěvku
     */

    const WARNING_ARTICLE_POST_FILE_ERROR = "Při nahrávání souboru došlo k chybě.";
    const WARNING_ARTICLE_POST_NO_FILE = "Povinný soubor nebyl přiložen.";
    const WARNING_ARTICLE_POST_BAD_FILE_TYPE = "Soubor je nesprávného typu. Typ souboru musí být PDF.";

    const DANGER_ARTICLE_POST_NOT_PUBLISHED = "Článek nebyl zveřejněn.";

    /**
     * Alerty při správě uživatelů
     */
    const SUCCESS_ADMIN_USER_DELETED = "Uživatel byl odstraněn (společně s jeho články a recenzemi).";
    const SUCCESS_ADMIN_USER_ROLE_SET = "Role uživatele byla úspěšně nastavena.";

    const WARNING_ADMIN_USER_NOT_DELETED = "Uživatel nemohl být z nějakého důvodu odstraněn.";

    /**
     * Alerty při přiřazování recenzentů k článkům
     */
    const SUCCESS_ADMIN_REVIEW_ASSIGNED = "Recenzent byl (nebo již byl) přiřazen k příslušnému článku.";
    const SUCCESS_ADMIN_REVIEW_REMOVED = "Recenze nebo přiřazení k recenzi bylo úspěšně odstraněno.";

    /**
     * Alerty při postování recenze
     */
    const SUCCESS_REVIEW_POST_POSTED = "Recenze byla úspěšně publikována.";
    const WARNING_REVIEW_POST_BAD_EVALUATION_VALUES = "Hodnoty hodnocení nejsou korektní. Hodnoty musí být v rozmezí <0;10>.";

    /**
     * Alerty při správě článků
     */
    const SUCCESS_ADMIN_ARTICLE_PASSED = "Článek byl úspěšně označen jako schválený a byl publikován.";
    const SUCCESS_ADMIN_ARTICLE_REJECTED = "Článek byl úspěšně označen jako zamítnutý.";
    const SUCCESS_ADMIN_ARTICLE_REMOVED = "Článek byl úspěšně odstraněn.";

    /**
     * Sjednotí dva seznamy alertů (druhý vloží do prvního)
     * @param array $arr1 seznam alertů
     * @param array $arr2 seznam alertů
     * @return array Výsledné sjednocení seznamů (první seznam)
     */
    public static function merge_alerts(array &$arr1, array &$arr2):array {
        $arr1[Alerts::ALERTS_SUCCESS] = array_merge($arr1[Alerts::ALERTS_SUCCESS], $arr2[Alerts::ALERTS_SUCCESS]);
        $arr1[Alerts::ALERTS_WARNING] = array_merge($arr1[Alerts::ALERTS_WARNING], $arr2[Alerts::ALERTS_WARNING]);
        $arr1[Alerts::ALERTS_DANGER] = array_merge($arr1[Alerts::ALERTS_DANGER], $arr2[Alerts::ALERTS_DANGER]);

        return $arr1;
    }

    /**
     * Vytvoří strukturu polí pro práci s různými upozorněními
     * @return array struktura polí
     */
    public static function alertArray():array {
        $arr = [];
        $arr[self::ALERTS_SUCCESS] = [];
        $arr[self::ALERTS_WARNING] = [];
        $arr[self::ALERTS_DANGER] = [];

        return $arr;
    }

    /**
     * Funkce vypíše veškeré upozornění, které jsou obsažena v zadaném poli
     * @param array $tplData pole obsahující strukturu pro upozornění
     */
    public static function printAllAlerts(array $tplData) {
        self::printAlerts(self::ALERTS_SUCCESS, self::ALERT_SUCCESS, $tplData);
        self::printAlerts(self::ALERTS_DANGER, self::ALERT_DANGER, $tplData);
        self::printAlerts(self::ALERTS_WARNING, self::ALERT_WARNING, $tplData);
    }

    /**
     * Funkce pro vypsání upozornění konkrétního typu
     * @param string $alertType typ vypisovaných upozornění
     * @param string $alertTypeStr text pro vypsání jako titulek upozornění
     * @param array $tplData pole obsahující strukturu pro dané upozornění
     */
    public static function printAlerts(string $alertType, string $alertTypeStr, array $tplData) {
        if(isset($tplData[$alertType])) {
            foreach($tplData[$alertType] as $alert) {
                echo '
                <div class="alert alert-' . $alertType . ' alert-dismissible fade show" role="alert">
                    <strong>' . $alertTypeStr . '</strong> ' . $alert . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zavřít"></button>
                </div>';
            }
        }
    }
}