<?php
/////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni uvodni stranky  ///////////
/////////////////////////////////////////////////////////////

//// pozn.: sablona je samostatna a provadi primy vypis do vystupu:
// -> lze testovat bez zbytku aplikace.
// -> pri vyuziti Twigu se sablona obejde bez PHP.

/*
////// Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////
//// UKAZKA: Uvod bude vypisovat informace z tabulky, ktera ma nasledujici sloupce:
// id, date, author, title, text
$tplData['title'] = "Úvodní stránka (TPL)";
$tplData['stories'] = [
    array("id_introduction" => 1, "date" => "2016-11-01 10:53:00", "author" => "A.B.", "title" => "Nadpis", "text" => "abcd")
];
define("DIRECTORY_VIEWS", "../Views");
const WEB_PAGES = array(
    "uvod" => array("title" => "Úvodní stránka (TPL)")
);
////// KONEC: Po zakomponovani do zbytku aplikace bude tato cast odstranena/zakomentovana  //////
*/

//// vypis sablony
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

?>

<form class="row g-3">
    <h4>Vyhledávání</h4>
    <div class="form-group col-md-10">
        <input type="text" class="form-control" name="search" id="uvodSearchText" placeholder="Vyhledávání příspěvků podle autora nebo textu" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
    </div>
    <div class="form-group col-md-2">
        <input type="submit" class="btn btn-outline-primary col-12" name="uvodSearchSubmit" value="Hledat">
    </div>
</form>

<div class="mt-3">
    <h4>Publikované články</h4>


<!-- Vypis obsahu sablony -->
<?php
// muze se hodit: strtotime($d['date'])

// vypis pohadek
$res = "";

if(array_key_exists('articles', $tplData) && !empty($tplData['articles'])) {
    foreach ($tplData['articles'] as $d) {
        echo '
            <div class="card mt-3">
                <div class="card-header">
                    <a class="text-body card-link" href="?page=profile&user='.$d['login'].'">'.$d['name'] . ' ' . $d['surname'] . '</a>
                    <span class="float-end">'.date("d. m. Y, H:i.s", strtotime($d['time_posted'])).'</span>
                </div>
                <div class="card-body">
                    <a class="h5 card-title text-body card-link" href="?page=article&article='.$d['id_article'].'">'.$d['title'].'</a>
                    <div class="card-text">
                        '.\konference\Models\Utilities::printUnformated($d['shortAbstract']).' <a href="?page=article&article='.$d['id_article'].'">[...]</a>
                    </div>
                </div>
            </div>
        ';
    }

    $page = 'uvod';
    if(isset($_GET['search'])) $page .= '&search=' . $_GET['search'];

    \konference\Models\Utilities::paginaton($page, $tplData['page'], $tplData['pages']);
} else {
    echo "<h5 class='text-center mt-3'>Žádné články nenalezeny</h5>";
}

?>
</div>
