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
<div class="mt-3">
    <h4>Články pro recenzování</h4>
<!-- ------------------------------------------------------------------------------------------------------- -->


<!-- Vypis obsahu sablony -->
<?php
// muze se hodit: strtotime($d['date'])

// vypis pohadek

if(array_key_exists('articles', $tplData) && !empty($tplData['articles'])) {
    echo '<div class="table-responsive-md"><table class="table table-hover"><tr><th>ID</th><th>Nadpis článku</th><th>Datum a čas přiřazení</th><th>Akce</th></tr>';
    foreach ($tplData['articles'] as $d) {
        echo '
            <tr>
                <td>'.$d['id_article'].'</td>
                <td>'.$d['title'].'</td>
                <td>'.date("d. m. Y, H:i.s", strtotime($d['time_posted'])).'</td>
                <td><a href="?page=article&article='.$d['id_article'].'" class="btn btn-outline-primary">Přejít na článek</a></td>
            </tr>
        
        
        ';
    }
    echo "</table></div>";
    \konference\Models\Utilities::paginaton("articles-to-review", $tplData['page'], $tplData['pages']);
} else {
    echo "<h5 class='text-center mt-3'>Žádné články nenalezeny</h5>";
}

?>
</div>
