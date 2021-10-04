<?php

namespace konference\Models;

use \PDO;
use \PDOException;

/**
 * Třída pro práci s databází
 * @package konference\Models
 */
class DatabaseModel {

    /** @var DatabaseModel $database  Singleton databazoveho modelu. */
    private static $database;

    /** @var \PDO $pdo  Objekt pracujici s databazi prostrednictvim PDO. */
    private $pdo;

    /**
     * Inicializace pripojeni k databazi.
     */
    private function __construct() {
        // inicializace DB
        $this->pdo = new \PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        // vynuceni kodovani UTF-8
        $this->pdo->exec("set names utf8");
    }

    /**
     * Tovarni metoda pro poskytnuti singletonu databazoveho modelu.
     * @return DatabaseModel    Databazovy model.
     */
    public static function getDatabaseModel(){
        if(empty(self::$database)){
            self::$database = new DatabaseModel();
        }
        return self::$database;
    }

    /**
     * Funkce provede htmlspecialchars(...) na všechny prvky zadaného pole
     * @param array $array pole hodnot
     * @return array upravené pole hodnot
     */
    public static function htmlspecialchars(array $array):array {
        foreach($array as $key => $value) {
            $array[$key] = htmlspecialchars($value);
        }

        return $array;
    }

    /**
     * Funkce pro kontrolu, zda všechny zadané parametry obsahují hodnotu
     * @return bool všechny hodnoty jsou v pořádku
     */
    public static function isset():bool {
        $argNumber = func_num_args();

        if($argNumber < 2) return true;

        $array = func_get_arg(0);
        $result = true;

        for($i = 1; $i < $argNumber; $i++) {
           $result = $result && isset($array[func_get_arg($i)]) && !empty($array[func_get_arg($i)]);
        }

        return $result;
    }
    //////////////////////////////////////////////////////////
    ///////////  Prace s databazi  /////////////////////////
    //////////////////////////////////////////////////////////

    /**
     * Funkce pro získání všech příspěvků
     * @param int $page     číslo strany
     * @param int $articlesPerPage  počet položek na jednu stranu
     * @param int $abstractLength   počet vyjmutých slov (pokud 0 => vše)
     * @param string $cond  podmínka vyhledávání
     * @param string $search    vyhledávaný řetězec
     * @return array pole příspěvků
     */
    public function getAllArticles(int $page = 1, int $articlesPerPage = 10, int $abstractLength = 0, string $cond = "true", string $search = "%"):array {
        $column = "SUBSTRING_INDEX(a.abstract, ' ', :length)";
        if($abstractLength == 0) {
            $column = "a.abstract";
        }

        $q = "SELECT a.id_article, a.title, a.state, ".$column." as shortAbstract, a.time_posted, u.name, u.surname, u.login FROM "
            .TABLE_ARTICLES . " as a INNER JOIN " . TABLE_USERS . " as u ON u.id_user = a.id_author AND " . $cond .
            " AND CONCAT(a.abstract, ' ', a.title, ' ', u.name, ' ', u.surname) LIKE :search order by id_article desc LIMIT :limit,:app;";

        $res = $this->pdo->prepare($q);
        if($abstractLength != 0) {
            $res->bindParam(":length",  $abstractLength);
        }

        $search = "%" . $search . "%";
        $res->bindParam(":search", $search);

        $limit = ($page-1)*$articlesPerPage;
        $res->bindParam(":app", $articlesPerPage, PDO::PARAM_INT);
        $res->bindParam(":limit", $limit, PDO::PARAM_INT);

        if($res->execute()) {
            $result = $res->fetchAll();
            return $result;
        }
        $res->errorInfo();
        return [];
    }


    /**
     * Funkce pro získání všech uživatelů
     * @param int $page číslo strany
     * @param int $articlesPerPage počet položek na jednu stranu
     * @param string $cond podmínka vyhledávání
     * @return array seznam uživatelů
     */
    public function getAllUsers($page = 1, $articlesPerPage = 10, $cond = "true"):array {

        $q = "SELECT * FROM ".TABLE_USERS . " WHERE " . $cond . " LIMIT :limit, :app;";
        $res = $this->pdo->prepare($q);

        // pripravim dotaz
        $limit = ($page-1)*$articlesPerPage;
        $res->bindParam(":app", $articlesPerPage, PDO::PARAM_INT);
        $res->bindParam(":limit", $limit, PDO::PARAM_INT);

        // provedu a vysledek vratim jako pole
        // protoze je o uzkazku, tak netestuju, ze bylo neco vraceno
        if($res->execute()) {
            return $res->fetchALl();
        }

        return [];
    }

    /**
     * Funkce pro hledání uživatele se zadaným loginem/emailem a hashem hesla
     * @param string $login login uživatele
     * @param string $password hash hesla
     * @return mixed|null nalezený uživatel
     */
    public function loginUser(string $login, string $password) {
        $login = htmlspecialchars($login);

        $q = "SELECT id_user, login, password from " . TABLE_USERS . " WHERE login = :login OR email = :email";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindParam(':login', $login);
        $vystup->bindParam(':email', $login);
        $vystup->execute();
        if($vystup->rowCount() == 0) {
            return null;
        }

        $result = $vystup->fetchAll()[0];

        if(password_verify($password, $result['password'])) {
            $q = "UPDATE " . TABLE_USERS . " SET time_lastlogin = CURRENT_TIMESTAMP() WHERE login = :login OR email = :email";
            $res = $this->pdo->prepare($q);
            $res->bindParam(':login', $login);
            $res->bindParam(':email', $login);
            $res->execute();
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Vytvoření záznamu o novém uživateli
     * @param string $login přihlašovací jméno uživatele
     * @param string $email email uživatele
     * @param string $password hash hesla uživatele
     * @param string $name křestní jméno uživatele
     * @param string $surname příjmení uživatele
     * @return array alerty vzniklé při procesu vkládání
     */
    public function createUser(string $login, string $email, string $password, string $name, string $surname) {
        $data = Alerts::alertArray();

        /**
         * Kontrola vstupních dat
         */
        $login = htmlspecialchars($login);
        $email = htmlspecialchars($email);
        $name = htmlspecialchars($name);
        $surname = htmlspecialchars($surname);

        /**
         * Kontrola, zda uživatel s takovými údaji není v záznamu
         */
        $q = "SELECT login, email from " . TABLE_USERS . " WHERE login = :login OR email = :email;";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindParam(':login', $login);
        $vystup->bindParam(':email', $email);
        $vystup->execute();
        if($vystup->rowCount() > 0) {
            $result = $vystup->fetch();

            if($result['login'] == $login) {
                $data[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_REG_USERNAME_EXISTS;
                return $data;
            }

            if($result['email'] == $email) {
                $data[Alerts::ALERTS_WARNING][] = Alerts::WARNING_USER_REG_EMAIL_EXISTS;
                return $data;
            }

            $data[Alerts::ALERTS_DANGER][] = Alerts::DANGER_UNEXPECTED_ERROR;
            return $data;
        }

        /**
         * Vkládání nového uživatele do databáze
         */
        $q = "INSERT INTO " . TABLE_USERS . " (login, email, password, name, surname) 
            VALUES (:login, :email, :password, :name, :surname);";
        $vystup = $this->pdo->prepare($q);
        $vystup->bindParam(':login', $login);
        $vystup->bindParam(':email', $email);
        $vystup->bindParam(':password', $password);
        $vystup->bindParam(':name', $name);
        $vystup->bindParam(':surname', $surname);

        if($vystup->execute()) {
            $q = "SELECT id_user as id FROM " . TABLE_USERS . " WHERE login = :login";
            $res = $this->pdo->prepare($q);
            $res->bindParam(":login", $login);
            if($res->execute()) {
                $id = $res->fetch()['id'];
                $data['result'] = $id;
            } else {
                $data[Alerts::ALERTS_DANGER] = Alerts::DANGER_UNEXPECTED_ERROR;
            }
        } else {
            $data[Alerts::ALERTS_DANGER] = Alerts::DANGER_UNEXPECTED_ERROR;
        }

        return $data;
    }

    /**
     * Smazání zadaného uživatele
     * @param int $userId číselný identifikátor uživatele
     * @return bool proces byl úspěšný
     */
    public function deleteUser(int $userId):bool {
        // pripravim dotaz
        $q = "DELETE FROM ".TABLE_USERS." WHERE id_user = $userId";
        // provedu dotaz
        $res = $this->pdo->query($q);
        // pokud neni false, tak vratim vysledek, jinak null
        if ($res) {
            // neni false
            return true;
        } else {
            // je false
            return false;
        }
    }

    /**
     * Získání informací o uživateli
     * @param int $userId číselný identifikátor uživatele
     * @return mixed|null informace o uživateli
     */
    public function getUserInfo(int $userId) {

        $q = "SELECT * FROM " . TABLE_USERS . " WHERE id_user = :id_user";
        $res = $this->pdo->prepare($q);
        $res->bindParam(':id_user', $userId);
        if($res->execute()) {
            return $res->fetchAll()[0];
        } else {
            return null;
        }
    }

    /**
     * Získání informací o uživateli podle loginu
     * @param string $username login uživatele
     * @return mixed|null informace o uživateli
     */
    public function getUserInfoByUserName(string $username) {

        $q = "SELECT * FROM " . TABLE_USERS . " WHERE login = :username";
        $res = $this->pdo->prepare($q);
        $res->bindParam(':username', $username);
        if($res->execute()) {
            return $res->fetchAll()[0];
        } else {
            return null;
        }
    }

    /**
     * Změna hesla uživatele
     * @param int $userId číselný identifikátor uživatele
     * @param string $old_password hash aktuálního hesla uživatele
     * @param string $new_password hash nového hesla uživatele
     * @return bool|int informace o výsledku procesu
     */
    public function changeUserPassword(int $userId, string $old_password, string $new_password) {
        $userInfo = $this->getUserInfo($userId);

        /**
         * Kontrola, zda se zadané aktuální heslo shoduje opravdu shoduje s aktuálním heslem v databázi
         */
        if(!password_verify($old_password, $userInfo['password'])) {
           return 2;
        }

        $password = password_hash($new_password, PASSWORD_BCRYPT);

        $q = "UPDATE " . TABLE_USERS . " SET password = :pass WHERE id_user = :id_user";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":pass", $password);
        $res->bindParam("id_user", $userId);
        return $res->execute();
    }

    /**
     * Funkce připraví zadaný SQL příkaz, vloží do něj zadané parametry a provede ho
     * @return bool informace o provedeném příkazu
     */
    public function execute():bool {
        if(func_num_args() == 0) {
            return false;
        }

        $q = func_get_arg(0);
        $res = $this->pdo->prepare($q);

        $args = func_get_args();

        for($i = 1; $i < func_num_args(); $i++) {
            $res->bindParam($i, $args[$i]);
        }

        return $res->execute();


    }

    /**
     * Vytvoření záznamu pro článek
     * @param int $author číselný identifikátor uživatele (autora)
     * @param string $title nadpis článku
     * @param string $abstract abstrakt (úvod) článku
     * @param $file
     * @return int ID přidaného příspěvku (pokud selže => -1)
     */
    public function postArticle(int $author, string $title, string $abstract, $file):int {
        $title = htmlspecialchars($title);
        $abstract = htmlspecialchars($abstract, ENT_QUOTES,'UTF-8',false);

        $q = "INSERT INTO " . TABLE_ARTICLES . " (id_author, title, abstract, file) VALUES (:author, :title, :abstract, :file)";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":author", $author);
        $res->bindParam(":title", $title);
        $res->bindParam(":abstract", $abstract);
        $res->bindParam(":file", $file, PDO::PARAM_LOB);

        if($res->execute()) {
            return $this->pdo->lastInsertId('id_article');
        } else {
            return -1;
        }
    }

    /**
     * Získá informace o článku
     * @param int $articleId číselná identifikace článku
     * @return mixed|null pole informací o článku
     */
    public function getArticle(int $articleId) {
        $q = "SELECT title, abstract, file, time_posted, state, u.id_user, u.name, u.surname, u.login, octet_length(file) as size 
            FROM " . TABLE_ARTICLES . " as a INNER JOIN " . TABLE_USERS . " as u ON u.id_user = a.id_author 
            where a.id_article = :id_article";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_article", $articleId);
        if($res->execute()) {
            return $res->fetchAll()[0];
        } else {
            return null;
        }
    }

    /**
     * Získá seznam článků určeného uživatele
     * @param int $userId číselná identifikace uživatele
     * @param int $page číslo aktuální stránky
     * @param int $articlesPerPage počet položek na stránku
     * @param int $abstractLength počet vyjmutých slov z abstraktu (pokud 0 => )
     * @param string $cond podmínka dotazu
     * @return array pole příspěvků uživatele
     */
    public function getUsersArticles(int $userId, $page = 1, $articlesPerPage = 10, int $abstractLength = 0, string $cond = "true") {
        $column = "SUBSTRING_INDEX(abstract, ' ', :length)";
        if($abstractLength == 0) {
            $column = "abstract";
        }

        $q = "SELECT id_article, title, ".$column." as shortAbstract, state, time_posted FROM " . TABLE_ARTICLES . " WHERE id_author = :authorId AND " . $cond . " LIMIT :limit,:app;";
        $res = $this->pdo->prepare($q);
        if($abstractLength != 0) {
            $res->bindParam(":length", $abstractLength);
        }

        $limit = ($page-1)*$articlesPerPage;
        $res->bindParam(":app", $articlesPerPage, PDO::PARAM_INT);
        $res->bindParam(":limit", $limit, PDO::PARAM_INT);

        $res->bindParam(":authorId", $userId);
        if($res->execute()) {
            return $res->fetchAll();
        }

        return [];
    }

    /**
     * Získání počtu registrovaných uživatelů
     * @param string $cond podmínka dotazu
     * @return int počet získaných uživatelů
     */
    public function getNumberOfUsers($cond = "true"):int {
        $q = "SELECT COUNT(id_user) from " . TABLE_USERS . " where " . $cond;
        $res = $this->pdo->prepare($q);
        if($res->execute()) {
            return $res->fetch()[0];
        }

        return 0;
    }

    /**
     * Počet recenzí daného příspěvku
     * @param int $articleId číselný identifikátor příspěvku
     * @param string $cond podmínka dotazu
     * @return int počet recenzí na příspěvku
     */
    public function getNumberOfArticleReviews(int $articleId, string $cond = "true"):int {
        $q = "SELECT COUNT(id_review) from " . TABLE_REVIEWS . " where id_article = :articleID AND " . $cond;
        $res = $this->pdo->prepare($q);
        $res->bindParam(":articleID", $articleId);
        if($res->execute()) {
            return $res->fetch()[0];
        }

        return 0;
    }

    /**
     * Počet všech příspěvků
     * @param string $cond podmínka dotazu
     * @param string $search vyhledávaný řetězec
     * @return int počet nalezených příspěvků
     */
    public function getNumberOfArticles(string $cond = "true", string $search = "%"):int {
        $q = "SELECT COUNT(id_article) FROM " .TABLE_ARTICLES . " as a INNER JOIN " . TABLE_USERS . " as u 
        ON u.id_user = a.id_author WHERE " . $cond .
            " AND CONCAT(a.abstract, ' ', a.title, ' ', u.name, ' ', u.surname) LIKE :search";

        $res = $this->pdo->prepare($q);

        $search = "%" . $search . "%";
        $res->bindParam(":search", $search);

        if($res->execute()) {
            $result = $res->fetch()[0];
            return $result;
        }
        $res->errorInfo();
        return 0;
    }

    /**
     * Počet příspěvků uživatele
     * @param $userId číselný identifikátor uživatele
     * @param string $cond podmínka dotazu
     * @return int počet příspěků uživatele
     */
    public function getNumberOfUsersArticles($userId, $cond = "true"):int {
        $q = "SELECT COUNT(id_article) from " . TABLE_ARTICLES . " where id_author = :authorID AND " . $cond;
        $res = $this->pdo->prepare($q);
        $res->bindParam(":authorID", $userId);
        if($res->execute()) {
            return $res->fetch()[0];
        }

        return 0;
    }

    /**
     * Získá všechny recenze k zadanému článku
     * @param int $articleId číselný identifikátor článku
     * @param int $page číslo strany
     * @param int $perSite počet položek na stranu
     * @param string $cond podmínka dotazu
     * @return array pole nalezených recenzí
     */
    public function getAllArticleReviews(int $articleId, int $page = 1, int $perSite = 10, string $cond = "true"):array {

        $q = "SELECT r.text_evaluation, r.evaluation1, r.evaluation2, r.evaluation3, r.evaluation4, r.decision, r.time_posted, u.login, u.name, u.surname 
            from ".TABLE_REVIEWS." as r INNER JOIN ".TABLE_USERS." as u ON r.id_user = u.id_user 
            where r.id_article = :articleId AND " . $cond . " LIMIT :limit,:app;";
        $res = $this->pdo->prepare($q);

        $limit = ($page-1)*$perSite;
        $res->bindParam(":app", $perSite, PDO::PARAM_INT);
        $res->bindParam(":limit", $limit, PDO::PARAM_INT);

        $res->bindParam(":articleId", $articleId);
        if($res->execute()) {
            return $res->fetchAll();
        }

        return [];

    }

    /**
     * Nastaví uživateli roli
     * @param int $userId číselný identifikátor uživatele
     * @param int $roleId číselný identifkátor role
     * @return bool dotaz proběhl v pořádku
     */
    public function setRole(int $userId, int $roleId):bool {
        $userId = htmlspecialchars($userId);
        $roleId = htmlspecialchars($roleId);

        $q = "UPDATE " . TABLE_USERS . " SET id_role = :role WHERE id_user = :user";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":user", $userId);
        $res->bindParam(":role", $roleId);

        return $res->execute();

    }

    /**
     * Počet přiřazených recenzí k článku
     * @param int $articleId číselný identifikátor článku
     * @return int počet nalezených recenzí
     */
    public function countAssignToReview(int $articleId):int {
        $articleId = htmlspecialchars($articleId);

        $q = "SELECT COUNT(id_review) FROM " . TABLE_REVIEWS . " WHERE id_article = :article AND state = 0";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":article",$articleId);
        if($res->execute()) {
            return $res->fetch()[0];
        }

        return -1;
    }

    /**
     * Zjistí zda je recenzent přiřazen k recenzi zadaného článku
     * @param int $articleId číselný identifikátor článku
     * @param int $userId číselný identifikátor uživatele
     * @return int
     */
    public function isUserAssignedArticleToReview(int $articleId, int $userId):int {
        $articleId = htmlspecialchars($articleId);
        $userId = htmlspecialchars($userId);

        $q = "SELECT COUNT(id_review) FROM " . TABLE_REVIEWS . " WHERE id_article = :article AND id_user = :user AND state=0";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":article", $articleId);
        $res->bindParam(":user", $userId);
        if($res->execute()) {
            return $res->fetch()[0] > 0 ? 1 : 0;
        }
        return -1;
    }

    /**
     * Přiřadí uživatele k recenzi příspěvku
     * @param int $articleId číselný identifikátor článku
     * @param int $userId číselný identifikátor uživatele
     * @return bool přiřazení proběhlo úspěšně
     */
    public function assignArticleToReview(int $articleId, int $userId):bool {
        $articleId = htmlspecialchars($articleId);
        $userId = htmlspecialchars($userId);

        $q = "INSERT INTO " . TABLE_REVIEWS . " (id_article, id_user) 
         SELECT * from (SELECT :article, :user) as tmp WHERE NOT EXISTS (
            SELECT id_review from " . TABLE_REVIEWS . " where id_article = :article AND id_user = :user)";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":article", $articleId);
        $res->bindParam(":user", $userId);

        if($res->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Publikuje recenzi
     * @param int $articleId číselný identifikátor článku
     * @param int $userId číselný identifikátor uživatele
     * @param string $text_evaluation slovní hodnocení článku
     * @param int $eva1 hodnotitelské kritérium 1
     * @param int $eva2 hodnotitelské kritérium 1
     * @param int $eva3 hodnotitelské kritérium 1
     * @param int $eva4 hodnotitelské kritérium 1
     * @param int $decision výsledné binární hodnocení (ANO/NE)
     * @return bool operace proběhla úspěšně
     */
    public function postArticleReview(int $articleId, int $userId, string $text_evaluation, int $eva1, int $eva2,
                                      int $eva3, int $eva4, int $decision) {
        $articleId = htmlspecialchars($articleId);
        $userId = htmlspecialchars($userId);
        $text_evaluation = htmlspecialchars($text_evaluation, ENT_QUOTES,'UTF-8',false);
        $eva1 = htmlspecialchars($eva1);
        $eva2 = htmlspecialchars($eva2);
        $eva3 = htmlspecialchars($eva3);
        $eva4 = htmlspecialchars($eva4);
        $decision = htmlspecialchars($decision);

        $q = "UPDATE " . TABLE_REVIEWS . " SET text_evaluation = :evaluation, evaluation1 = :eva1,
            evaluation2 = :eva2, evaluation3 = :eva3, evaluation4 = :eva4, decision = :dec, state = 1,
             time_posted = CURRENT_TIMESTAMP() where id_user = :user AND id_article = :article";

        $res = $this->pdo->prepare($q);
        $res->bindParam(":evaluation", $text_evaluation);
        $res->bindParam(":eva1", $eva1);
        $res->bindParam(":eva2", $eva2);
        $res->bindParam(":eva3", $eva3);
        $res->bindParam(":eva4", $eva4);
        $res->bindParam(":dec", $decision);
        $res->bindParam(":user", $userId);
        $res->bindParam(":article", $articleId);


        if($res->execute()) {
            return true;
        } else {
            var_dump($res->errorInfo());
            return false;
        }

    }

    /**
     * Získá přiřazené recenzenty k článku
     * @param int $articleId číselný identifikátor článku
     * @param string $cond podmínka dotazu
     * @return array přiřazení recenzenti k článku
     */
    public function getReviewsAssignment(int $articleId, string $cond = "true") {

        $q = "SELECT r.id_review, u.id_user, u.name, u.login, u.surname, r.state FROM " . TABLE_REVIEWS . " as r INNER JOIN " . TABLE_USERS . " as u ON u.id_user = r.id_user 
            WHERE r.id_article = :article AND " . $cond;

        $res = $this->pdo->prepare($q);
        $res->bindParam(":article", $articleId);

        if($res->execute()) {
            return $res->fetchAll();
        }

        return [];
    }

    /**
     * Odstranění článku
     * @param int $reviewId číselný identifikátor článku
     * @return bool úspěšnost procedury
     */
    public function removeReview(int $reviewId) {
        $q = "DELETE FROM " . TABLE_REVIEWS . " WHERE id_review = :review";

        $res = $this->pdo->prepare($q);
        $res->bindParam(":review", $reviewId);

        return $res->execute();
    }

    /**
     * Získá všechny recenzenty přiřazené k danému článku
     * @param string $cond podmínka dotazu
     * @return array seznam recenzentů
     */
    public function getAllReviewers(string $cond = "true"): array {
        $res = $this->pdo->query("SELECT name, surname, id_user from " . TABLE_USERS . " where id_role=".Roles::ROLE_REVIEWER." AND " . $cond);
        if($res) {
            return $res->fetchAll();
        }

        return [];
    }

    /**
     * Vrátí recenze zadaného uživatele
     * @param int $userId číselná identifikace uživatele
     * @param int $page číslo stránky
     * @param int $perSite počet položek na stránku
     * @param string $cond podmínka dotazu
     * @return array pole recenzí uživatele
     */
    public function getUsersReviews(int $userId, int $page = 1, int $perSite = 10, string $cond = "true"):array {
        $q = "SELECT a.title, a.id_article, r.time_posted from reviews as r inner join articles as a 
            on r.id_article = a.id_article where r.id_user = :user AND " . $cond . " LIMIT :limit,:perSite";
        $res = $this->pdo->prepare($q);
        $limit = ($page-1)*$perSite;

        $res->bindParam(":user", $userId);
        $res->bindParam(":limit", $limit, PDO::PARAM_INT);
        $res->bindParam(":perSite", $perSite, PDO::PARAM_INT);

        if($res->execute()) {
            return $res->fetchAll();
        }

        var_dump($res->errorInfo());
        return [];
    }

    /**
     * Vrátí počet recenzí uživatele
     * @param int $userId číselná identifikace uživatele
     * @param string $cond podmínka dotazu
     * @return int počet recenzí
     */
    public function getNumberOfUsersReviews(int $userId, string $cond = "true"):int {
        $q = "SELECT COUNT(id_review) from " . TABLE_REVIEWS . " where id_user = :user AND " . $cond;
        $res = $this->pdo->prepare($q);
        $res->bindParam(":user", $userId);
        if($res->execute()) {
            return $res->fetch()[0];
        }

        return -1;
    }

    /**
     * Smazání článku
     * @param int $articleId číselná identifikace článku
     * @return bool informace o provedení procedury
     */
    public function removeArticle(int $articleId):bool {
        $articleId = htmlspecialchars($articleId);
        $q = "DELETE FROM " . TABLE_ARTICLES . " WHERE id_article = :article";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":article", $articleId);
        if($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Publikuje zadaný příspěvek
     * @param int $articleId číselný identifikátor článku
     * @return bool informace o provedení procedury
     */
    public function passArticle(int $articleId):bool {
        $articleId = htmlspecialchars($articleId);
        $q = "UPDATE " . TABLE_ARTICLES . " SET state = " . States::ARTICLE_STATE_PUBLISHED . " WHERE id_article = " . $articleId;
        $res = $this->pdo->query($q);
        if($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Označí příspěvek jako neschválený
     * @param int $articleId číselný identifikátor článku
     * @return bool informace o provedení procedury
     */
    public function rejectArticle(int $articleId):bool {
        $articleId = htmlspecialchars($articleId);

        $q = "UPDATE " . TABLE_ARTICLES . " SET state = " . States::ARTICLE_STATE_DENIED . " WHERE id_article = " . $articleId;
        $res = $this->pdo->query($q);
        if($res) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vrátí počet orecenzovaných příspěvků čekajících na schválení
     * @return int
     */
    public function getNumberOfArticlesToVerdict():int {
        $q = "SELECT COUNT(t.id_article) as pocet from (SELECT a.id_article
            from " . TABLE_ARTICLES . " a 
            left join " . TABLE_REVIEWS . " r on a.id_article = r.id_article 
            inner join " . TABLE_USERS . " u on u.id_user = a.id_author
            where a.state=0 AND r.state=1 group by a.id_article HAVING COUNT(r.id_review) >= ".NUMBER_OF_REVIEWS_TO_VERDICT.") as t";

        $res = $this->pdo->query($q);
        if($res) {
            return $res->fetch()[0];
        }

        return 0;
    }

    /**
     * Vrátí všechny orecenzované příspěvky čekajících na schválení
     * @param int $page číslo stránky
     * @param int $perSite počet položek na stránku
     * @return array příspěvky čekající na schválení
     */
    public function getArticlesToVerdict(int $page = 1, int $perSite = 10):array {
        $q = "SELECT a.id_article, a.title, u.name, u.surname, u.login, a.time_posted, COUNT(r.id_article) as pocet,
            ((SUM(r.evaluation1) + SUM(evaluation2) + SUM(evaluation3) + SUM(evaluation4))/(COUNT(r.id_article)*4)) as prumer
            from " . TABLE_ARTICLES . " a 
            left join " . TABLE_REVIEWS . " r on a.id_article = r.id_article 
            inner join " . TABLE_USERS . " u on u.id_user = a.id_author
            where a.state=0 AND r.state=1 group by a.id_article HAVING COUNT(r.id_review) >= ".NUMBER_OF_REVIEWS_TO_VERDICT." LIMIT :limit,:perSite";

        $limit = ($page-1)*$perSite;

        $res = $this->pdo->prepare($q);
        $res->bindParam(":limit", $limit, PDO::PARAM_INT);
        $res->bindParam(":perSite", $perSite, PDO::PARAM_INT);

        if($res->execute()) {
            return $res->fetchAll();
        }
        return [];
    }
    //////////////////////////////////////////////////////////
    ///////////  KONEC: Prace s databazi  /////////////////////////
    //////////////////////////////////////////////////////////

}

?>
