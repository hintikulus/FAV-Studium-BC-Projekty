<?php
///////////////////////////////////////////////////////////////////////////
///////////  Šablona pro zobrazení stránky pro správu recenzí  /////////////
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
       <p>Na této stránce lze neschváleným článkům přiřadit recenzenty, kteří poté mají přístup článek ohodnotit.
           Příspěvkům lze přiřadit neomezené (omezené množstvím recenzentům) množství recenzentů.
        Pokud recenzent příspěvek již ohodnotil je u jména recenzenta umístěn odznak zelené fajivky
           a průměrné ohodnocení. Přiřazení k článkům lze i zrušit, avšak pokud recenze je již publikována,
           tak dojte také k jejímu nenávratnému odstranění. Pro urychlené akce jsou dostupné tlačítka pro přístup
           na článek a jeho odstranění. Poté co článek má publikované 3 recenze se článek objeví také na stránce
           <a href="?page=admin-verdict">Orecenzované články</a>.

       </p>

    </div>
    <div class="row">
<?php
if(!empty($tplData['articles'])) {
    foreach ($tplData['articles'] as $article) {

        echo '
        <div class="col-md-6 col-lg-4 mt-3">
            <div class="card">
                <div class="card-header">
                    <a class="h5 card-title text-body card-link" 
                        href="?page=article&article=' . $article['id_article'] . '">' . $article['title'] . '
                    </a>
                </div>
                <div class="card-body">
                    <form method="post">
                        <h5 class="card-title">Přiřadit recenzenta</h5>';
        if (empty($tplData['reviewers'])) {
            echo "Není žádný recenzent.";
        } else {
            $out = '<select class="form-select" name="id_user">';
            foreach ($tplData['reviewers'] as $reviewer) {
                $out .= '<option value="' . $reviewer['id_user'] . '">
                                                ' . $reviewer['name'] . ' ' . $reviewer['surname'] . '
                                        </option>';
            }
            $out .= '</select>';

            $out .= '<div class="form-group mt-3 mb-3 col-12">
                                        <button type="submit" name="action" class="btn btn-outline-primary" value="addReviewer">
                                            Přidat
                                        </button>
                                    </div>';
            echo $out;
        }
        echo '
                           
                        <input type="hidden" name="id_article" value="' . $article['id_article'] . '">
                    </form>   
                    
                    <h5 class="card-title">Přiřazení recenzenti</h5>
                    ';
        if (!empty($article['reviewers'])) {

            echo '<ul class="list-group">';

            foreach ($article['reviewers'] as $reviewer) {
                echo '
                            <li class="list-group-item">
                                <form method="post" class="mb-0">
                                    <a href="?page=profile&user=' . $reviewer['login'] . '">
                                    ' . $reviewer['name'] . ' ' . $reviewer['surname'] . '</a>' .

                    ($reviewer['state'] == 1 ?
                        ' <i class="far fa-check-circle text-success" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="Recenze byla publikována">

                                    </i>
                                    ' : '') . '
                            <input type="hidden" name="id_review" value="' . $reviewer['id_review'] . '">
                            <button type="submit" class="btn-close float-end" aria-label="Zavřít" 
                                name="action" value="removeReviewAssignment">
                            
                            </button>
                            </form>
                            </li>';
            }
            echo "</ul>";
        } else {
            echo "K článku není přiřazen žádný recenzent pro recenzování.";
        }
        echo '
                </div>
                <div class="card-footer">
                    <form method="post" class="mb-0">
                        <a href="?page=article&article=' . $article['id_article'] . '" class="btn btn-outline-primary">
                            Příspěvek
                        </a>
                        <input type="hidden" name="id_article" value="' . $article['id_article'] . '">
                        <button type="submit" name="action" value="removeArticle" class="btn btn-outline-danger float-end">
                            Odstranit
                        </button>          
                    
                    </form>
                </div>
            
            
            </div>
        </div>  
    ';
    }

    echo \konference\Models\Utilities::paginaton("admin-reviews", $tplData['page'], $tplData['pages']);
} else {
    echo "<h5 class='text-center mt-3'>Nejsou k dispozici žádné příspěvky</h5>";
}

echo '</div>';
?>