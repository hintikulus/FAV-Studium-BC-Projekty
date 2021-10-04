<?php

global $tplData;

?>

<h3 class="h3"><?= $tplData['title'] . " " . $tplData['profileInfo']['login']; ?></h3>
<div class="row">
    <div class="col-lg-3">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">

                <h3><?= $tplData['profileInfo']['name'] . " " . $tplData['profileInfo']['surname'] ?></h3>
            </div>
            <dl class="card-body">
                <dt>Role: </dt><dd><?= \konference\Models\Roles::roleString($tplData['profileInfo']['id_role']); ?></dd>
                <dt>Počet příspěvků: </dt><dd><?= $tplData['userNumberArticles']; ?></dd>
                <dt>E-mail: </dt><dd><?= $tplData['profileInfo']['email']; ?></dd>
                <dt>Poslední přihlášení: </dt><dd><?= $tplData['profileInfo']['time_lastlogin']; ?></dd>
                <dt>Registrace: </dt><dd><?= $tplData['profileInfo']['time_reg']; ?></dd>
                <?php if(!empty($tplData['profileInfo']['bio'])) { ?>
                <dt>Biografie</dt>
                <dd><?= \konference\Models\Utilities::printFormated($tplData['profileInfo']['bio']); ?></dd>
                <?php } ?>
            </dl>

        </div>
    </div>
    <div class="col-lg-9">
            <h4>Příspěvky</h4>
            <?php
            if(!empty($tplData['articles'])) {
                foreach ($tplData['articles'] as $article) {
                    echo '
                        <div class="card mt-3">
                            <div class="card-header">
                                <a href="?page=article&article=' . $article['id_article'] . '" class="h5 text-body card-link">' . $article['title'] . '</a>
                                <span class="float-end">' . date("d. m. Y, H:i.s", strtotime($article['time_posted'])) . '</span>
                            </div>
                            <div class="card-body">
                                ' . \konference\Models\Utilities::printUnformated($article['shortAbstract']) . ' <a href="?page=article&article='.$article['id_article'].'">[...]</a>
                            </div>
                            
                        </div>
                    
                    ';

                }
                \konference\Models\Utilities::paginaton('profile&user=' . $tplData['profileInfo']['login'], $tplData['page'], $tplData['pages']);
            } else {
                echo "<h5 class='text-center mt-3'>Uživatel nemá žádné publikované příspěvky</h5>";
            }


            ?>


        </div>
</div>