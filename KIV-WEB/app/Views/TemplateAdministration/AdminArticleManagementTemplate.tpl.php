<?php
///////////////////////////////////////////////////////////////////////////
///////////  Šablona pro zobrazení stránky pro správu článků  /////////////
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

$res = "<div class='table-responsive-md'>
            <table class='table table-hover'>
                <tr><th>ID</th><th>Název</th><th>Autor</th><th>Datum a čas přidání</th><th>Stav</th><th>Akce</th></tr>";
// projdu data a vypisu radky tabulky

if(!empty($tplData['articles'])) {
    foreach ($tplData['articles'] as $u) {
        $res .= '<tr><td>' . $u['id_article'] . '</td>'
            . '<td><a href="?page=article&article=' . $u['id_article'] . '">' . $u['title'] . '</a></td>'
            . '<td><a href="?page=profile&user=' . $u['login'] . '">' . $u['name'] . ' ' . $u['surname'] . '</a></td>'
            . '<td>' . date("d. m. Y, H:i.s", strtotime($u['time_posted'])) . '</td>'
            . '<td>' . \konference\Models\States::articleStateToString($u['state']) . '</td><td>'
            . "<form method='post'><input type='hidden' name='id_article' value='$u[id_article]'>"
            . "<button type='submit' name='action' class='btn btn-outline-danger' value='delete'>Smazat</button>"
            . "</form></td></tr>";
    }

    $res .= "</table></div>";
    echo $res;

    echo \konference\Models\Utilities::paginaton("admin-articles", $tplData['page'], $tplData['pages']);
} else {
    echo "<h5 class='text-center mt-3'>Nejsou k dispozici žádné příspěvky</h5>";
}
?>
