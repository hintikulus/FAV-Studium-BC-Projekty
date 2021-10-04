<?php
///////////////////////////////////////////////////////////////////////////
///////////  Šablona pro zobrazení stránky pro schvalování článků  ////////
///////////////////////////////////////////////////////////////////////////

// určení globálních proměných, se kterými šablona pracuje
global $tplData;

?>
<!-- Vypis obsahu sablony -->
<?php
// muze se hodit:
//<form method='post'>
//    <input type='hidden' name='id_user' value=''>
//    <button type='submit' name='action' value='delete'>Smazat</button>
//</form>

// mam vypsat hlasku?
use konference\Models\Roles;
use konference\Models\Utilities;

// projdu data a vypisu radky tabulky

?>
    <div class="row">
        <p>Stránka určená primárně na vylistování všech příspěvků, které jsou vhodné pro finální verdikt,
            zda je článek schválen (publikován), zamítnut, nebo je vhodný pro smazání. Standardně je limit pro finální
            verdikt příspěvku stanoven na 3 publikované recenze. Příspěvky jsou seřazeny podle času přidání,
            aby byly obslouženy mezi prvními. Finální verdikt je možné uskutečnit zrychleně přímo ze seznamu,
            ale je možné ho vykonat i z detailu příspěvku.
        </p>
    </div>
    <div class="row">
<?php
if(!empty($tplData['articles'])) {
    $res = "<div class='table-responsive-md'>
                <table class='table table-hover'>
                    <tr>
                        <th>ID</th>
                        <th>Název</th>
                        <th>Autor</th>
                        <th>Datum a čas přidání</th>
                        <th>Počet recenzí</th>
                        <th>Průměrné hodnocení</th>
                        <th>Rychlé akce</th>
                    </tr>";
// projdu data a vypisu radky tabulky

    foreach($tplData['articles'] as $u){

        if ($u['prumer'] != 10) {
            $u['prumer'] = sprintf("%.1f", $u['prumer']);
        }

        $res .= '<tr><td>'.$u['id_article'].'</td>'
            .'<td><a href="?page=article&article='.$u['id_article'].'">'.$u['title'].'</a></td>'
            .'<td><a href="?page=profile&user='.$u['login'].'">' . $u['name'] . ' ' .$u['surname'].'</a></td>'
            .'<td>'.date("d. m. Y, H:i.s", strtotime($u['time_posted'])).'</td>'
            .'<td>'.$u['pocet'].'</td>'
            .'<td>'.\konference\Models\Utilities::stars($u['prumer']) . ' (' . $u['prumer'] . ')</td>'
            .'<td><form method="post">'
            .'<div class="d-grid gap-2 d-md-flex justify-content-md-end">'
            ."<input type='hidden' name='id_article' value='$u[id_article]'>"
            ."<button type='submit' name='action' class='btn btn-outline-success' value='passArticle'>Schválit</button> "
            ."<button type='submit' name='action' class='btn btn-outline-danger' value='refuceArticle'>Zamítnout</button> "
            ."<button type='submit' name='action' class='btn btn-outline-danger' value='removeArticle'>Smazat</button>"
            ."</div></form></td></tr>";
    }

    $res .= "</table></div>";
    echo $res;
    echo \konference\Models\Utilities::paginaton("admin-verdict", $tplData['page'], $tplData['pages']);
} else {
    echo "<h5 class='text-center mt-3'>Není k dispozici žádný příspěvek</h5>";
}
?>
    </div>
