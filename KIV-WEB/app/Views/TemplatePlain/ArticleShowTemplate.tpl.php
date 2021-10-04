<?php

global $tplData;

if(isset($tplData['errors'])) {

}

$statusIcon = "";
switch($tplData['articleInfo']['state']) {
    case \konference\Models\States::ARTICLE_STATE_PUBLISHED:
        $statusIcon = '<i class="far fa-check-circle text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Článek byl schválen a publikován."></i>';
        break;
    case \konference\Models\States::ARTICLE_STATE_NOT_PUBLISHED:
        $statusIcon = '<i class="fas fa-sync text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Článek čeká na schválení."></i>';
        break;
    case \konference\Models\States::ARTICLE_STATE_DENIED;
        $statusIcon = '<i class="far fa-times-circle text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Článek nebyl schválen."></i>';
        break;
}

?>

<h3>Detail článku</h3>
<div class="row mb-3 justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">

                <h5 class="card-title"><?= $statusIcon . ' ' . $tplData['articleInfo']['title'];?></h5>
            </div>
            <div class="card-body">

                <?= \konference\Models\Utilities::printFormated($tplData['articleInfo']['abstract']); ?>
            </div>
            <div class="card-footer">
                <form method="post">
                    <button type="submit" name="articleShowDownload" class="btn btn-outline-primary" value="download"
                            data-bs-placement="bottom" title="Stáhnou celý článek v PDF" data-bs-toggle="tooltip">
                        <i class="fas fa-cloud-download-alt">

                        </i> PDF</button>
                    <?php
                        if(isset($tplData['userInfo']) && $tplData['userInfo']['id_role'] == \konference\Models\Roles::ROLE_ADMINISTRATOR) {
                            echo '<span class="float-end">';
                            if($tplData['articleInfo']['state'] == 0) {
                                if ($tplData['reviewNumber'] >= 3) {
                                    echo '<button type="submit" class="btn btn-outline-success" name="action" value="passArticle">Schválit</button> ';
                                    echo '<button type="submit" class="btn btn-outline-danger" name="action" value="rejectArticle">Zamítnout</button> ';
                                } else {
                                    echo '<h4 class="fas fa-info-circle text-primary mb-0 align-middle" data-bs-placement="bottom"
                                    title="Ke schválení příspěvku je potřeba ještě '
                                        . (3 - $tplData['reviewNumber']) . ' recenze" data-bs-toggle="tooltip"></h4> ';
                                }
                            }

                            echo '<button class="btn btn-outline-danger" name="action" value="removeArticle">Odstranit</button>';
                            echo '</span>';
                        }
                    ?>
                </form>
            </div>

        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <hr>
        <h3 class="h3">Recenze</h3>


<?php if($tplData['toReview']) { ?>
    <form class="border rounded mt-3" method = "POST" >
        <div class="row g-3 p-3" >
            <h4 class="h4" > Vytvoření recenze </h4 >
            <div class="col-md-10" >
                <h5 class="h5" > Slovní hodnocení </h5 >
                <div class="row g-3" >
                    <div class="form-group" >
                        <div class="form-group col-md-12" >
                            <label for="editor1" class="form-label" > Podrobný rozbor hodnocení článku </label >
                            <textarea class="form-control" name = "reviewPostTextEvaluation" id = "editor1" required ></textarea >
                        </div >
                    </div >
                </div >
            </div >
            <div class="col-md-2" >
                <div class="row g-3" >
                    <h5 class="h5" > Hodnocení</h5 >
                    <div class="form-group col-md-12 col-sm-6" >
                        <label for="reviewPostEvaluation1" class="form-label" > Relevance</label >
                        <input type = "number" class="form-control" id = "reviewPostEvaluation1" name = "reviewPostEvaluation1" placeholder = "0" value = "0" min = "0" max = "10" step = "1" required >
                    </div >

                    <div class="form-group col-md-12 col-sm-6" >
                        <label for="reviewPostEvaluation2" class="form-label" > Kvalita dat </label >
                        <input type = "number" class="form-control" id = "reviewPostEvaluation2" name = "reviewPostEvaluation2" placeholder = "0" value = "0" min = "0" max = "10" step = "1" required >
                    </div >

                    <div class="form-group col-md-12 col-sm-6" >
                        <label for="reviewPostEvaluation3" class="form-label" > Styl textu </label >
                        <input type = "number" class="form-control" id = "reviewPostEvaluation3" name = "reviewPostEvaluation3" placeholder = "0" value = "0" min = "0" max = "10" step = "1" required>
                    </div >

                    <div class="form-group col-md-12 col-sm-6" >
                        <label for="reviewPostEvaluation4" class="form-label" > Zdroje</label >
                        <input type = "number" class="form-control disabled" id = "reviewPostEvaluation4" name = "reviewPostEvaluation4" placeholder = "0" value = "0" min = "0" max = "10" step = "1" required>
                    </div >
                </div >
            </div >
            <div class="row p-3 g-3 mt-0" >
                <div class="form-group" >
                    <div class="form-check" >
                        <input class="form-check-input" type = "radio" name = "reviewPostDecision" id = "reviewPostDecision1" value="1" checked >
                        <label class="form-check-label" for="reviewPostDecision1" >
                            Článek doporučuji
                        </label >
                    </div >
                    <div class="form-check" >
                        <input class="form-check-input" type = "radio" name = "reviewPostDecision" id = "reviewPostDecision0" value="0" >
                        <label class="form-check-label" for="reviewPostDecision0" >
                            Článek nedoporučuji
                        </label >
                    </div >
                </div >
            </div >
            <div class="form-group" >
                <input type = "submit" class="btn btn-outline-primary col-12 col-md-auto" value = "Publikovat" name = "reviewPostSubmit" >
            </div >
        </div >

    </form>
<?php
}
?>
    <h4 class="h4 mt-3">Seznam recenzí</h4>

    <?php

    if(isset($tplData['reviews']) && count($tplData['reviews']) > 0) {
        foreach ($tplData['reviews'] as $review) {
            $avg = ($review['evaluation1'] + $review['evaluation2'] + $review['evaluation3'] + $review['evaluation4']) / 4;
            if ($avg == 10) {
                $number = $avg;
            } else {
                $number = sprintf("%.1f", $avg);
            }

            $decision = "";

            $review['text_evaluation'] = \konference\Models\Utilities::printFormated($review['text_evaluation']);

            if ($review['decision'] == 0) {
                $decision = "negativně";
            } else {
                $decision = "pozitivně";
            }

            echo '
            <div class="card shadow-sm mt-3">
                <div class="card-header">
                    <a href="?page=profile&user=' . $review['login'] . '" class="card-title h5 text-body card-link">' . $review['name'] . ' ' . $review['surname'] . '</a>
                    <span class="float-end">' . date("d. m. Y, H:i.s", strtotime($review['time_posted'])) . '</span>
                </div>
                <div class="card-body row">
                    <div class="col-md-8 col-lg-9">
                        <h5 class="card-title">Recenze</h5>
                        '.$review['text_evaluation']. '
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <h5 class="card-title">Hodnocení</h5>
                        <table class="list-group list-group-flush">
                            <tr><td>Relevance: </td><td>' . \konference\Models\Utilities::stars($review['evaluation1']) . '</td><td>(' . $review['evaluation1'] . ')</td></tr>
                            <tr><td>Kvalita dat: </td><td>' . \konference\Models\Utilities::stars($review['evaluation2']) . '</td><td>(' . $review['evaluation2'] . ')</td></tr>
                            <tr><td>Styl textu: </td><td>' . \konference\Models\Utilities::stars($review['evaluation3']) . '</td><td>(' . $review['evaluation3'] . ')</td></tr>
                            <tr><td>Zdroje: </td><td>' . \konference\Models\Utilities::stars($review['evaluation4']) . '</td><td>(' . $review['evaluation4'] . ')</td></tr>
                            <tr class="border-top">
                                <th>Průměr:</th><td>' . \konference\Models\Utilities::stars(round($avg)) . '</td><td>(' . $number . ')</td>
                            </tr>
                        </table>
                    </div>                
                </div> 
                <div class="card-footer">
                    Recenzent článek celkově hodnotí <b>' . $decision . '</b>.
                </div>       
            </div>';
        }
        \konference\Models\Utilities::paginaton('article&article=' . $_GET['article'], $tplData['page'], $tplData['pages']);
    } else {
        echo "<h5 class='text-center mt-3'>K článku nejsou přidány žádné recenze</h5>";
    }

    ?>
    </div>
</div>


