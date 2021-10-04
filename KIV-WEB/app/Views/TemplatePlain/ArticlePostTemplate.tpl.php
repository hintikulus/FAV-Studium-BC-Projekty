<?php

global $tplData;

if(isset($tplData['errors'])) {

}

?>

<form class="border rounded container mt-3" method="POST" enctype="multipart/form-data">
    <div class="row g-3 p-3">
        <h4 class="h4"><?= $tplData['title']; ?></h4>
        <div class="form-group col-md-12">
            <label for="articlePostTitle" class="form-label">Nadpis</label>
            <input type="text" class="form-control" id="articlePostTitle" name="articlePostTitle" placeholder="Nadpis článku" required>
        </div>

        <div class="form-group col-md-12">
            <label for="editor1" class="form-label">Abstrakt</label>
            <textarea class="form-control" id="editor1" name="articlePostAbstract" required></textarea>
        </div>

        <div class="form-group">
            <label for="articlePostFile" class="form-label">PDF soubor s kompletním článkem</label>
            <input type="file" class="form-control" id="articlePostFile" name="articlePostFile" required>

        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-outline-primary col-12 col-md-auto" value="Odeslat ke schválení" name="articlePostSubmit">
        </div>

    </div>



</form>


