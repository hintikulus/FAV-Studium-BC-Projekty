<?php

global $tplData;

?>

<h3 class="h3"><?= $tplData['title']; ?></h3>
    <div class="row">
        <p>Na této stránce má uživatel přehled jeho příspěvků. Zobrazuje se i informaci o aktuálním stavu příspěvku.
        Uživatel si může příspěvek rozkliknout, aby viděl více informací (například recenze).
        </p>
    </div>
    <div class="row">
<?php
if(!empty($tplData['articles'])) {
    foreach($tplData['articles'] as $article) {
        $bgcolor = "bg-transparent";
        $info = "";
        $icon = "";

        $article['shortAbstract'] = \konference\Models\Utilities::printUnformated($article['shortAbstract']);

        switch($article['state']) {
            case 0:
                $bgcolor = "bg-warning";
                $info = "Článek je v procesu schvalování.";
                $icon = '<i class="fas fa-sync fa-spin"></i>';
                break;
            case 1:
                $bgcolor = "bg-success text-white";
                $info = "Článek je publikován.";
                $icon = '<i class="fas fa-check"></i>';
                break;
            case 2:
                $bgcolor = "bg-danger text-white";
                $info = "Článek nebyl schválen.";
                $icon = '<i class="fas fa-times"></i>';
                break;
        }

        echo '
            <div class="col-md-6 col-lg-4 mt-3">
            <div class="card">
                <div class="card-header">
                    <a class="h5 card-title text-body card-link" href="?page=article&article='.$article['id_article'].'">'.$article['title'].'</a>
                </div>
                <div class="card-body">
                    '.$article['shortAbstract'].' <a href="?page=article&article='.$article['id_article'].'">[...]</a>
                </div>
                <div class="card-footer '.$bgcolor.'">
                    '.$info.'<span class="float-end">'.$icon.'</span>
                </div>
            
            
            </div>
            </div>  
        ';
    }

    \konference\Models\Utilities::paginaton("user-articles", $tplData['page'], $tplData['pages']);
} else {
    echo "<h5 class='text-center mt-3'>Uživatel nemá žádné vytvořené příspěvky</h5>";
}

?>
</div>