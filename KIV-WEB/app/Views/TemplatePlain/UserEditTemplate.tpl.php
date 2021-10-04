<?php

global $tplData;

?>

<h3 class="h3"><?= $tplData['title']; ?></h3>

<form class="border rounded mt-3" method="POST">
    <div class="row g-3 p-3">
        <h4 class="h4">Základní informace</h4>
        <div class="form-group col-md-6">
            <label for="userEditInfoName" class="form-label">Křestní jméno</label>
            <input type="text" class="form-control" id="userEditInfoName" name="userEditInfoName" placeholder="Křestní jméno" value="<?php echo $tplData['userInfo']['name']; ?>" required>
        </div>

        <div class="form-group col-md-6">
            <label for="userEditInfoSurName" class="form-label">Příjmení</label>
            <input type="text" class="form-control" id="userEditInfoSurName" name="userEditInfoSurName" placeholder="Příjmení" value="<?php echo $tplData['userInfo']['surname']; ?>" required>
        </div>

        <div class="form-group col-md-6">
            <label for="userEditInfoEmail" class="form-label">E-Mail</label>
            <input type="email" class="form-control" id="userEditInfoEmail" name="userEditInfoEmail" placeholder="voprsalek@example.com" value="<?php echo $tplData['userInfo']['email']; ?>" disabled>
        </div>

        <div class="form-group col-md-6">
            <label for="userEditInfoUserName" class="form-label">Uživalské jméno</label>
            <input type="text" class="form-control disabled" id="userEditInfoUserName" name="userEditInfoUserName" placeholder="Uživalské jméno" value="<?php echo $tplData['userInfo']['login']; ?>" disabled>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-outline-primary col-12 col-md-auto" value="Změnit" name="userEditInfoSubmit">
        </div>

    </div>



</form>

<form class="border rounded mt-3" method="POST">
    <div class="row g-3 p-3">
        <h4 class="h4">Biografie</h4>
        <div class="form-group">
            <div class="form-group">
                <textarea class="form-control" name="userEditBio" id="editor1" required>
                    <?= $tplData['userInfo']['bio']; ?>
                </textarea>
            </div>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-outline-primary col-12 col-md-auto" value="Změnit" name="userEditBioSubmit">
        </div>


    </div>



</form>

<form class="border rounded mt-3" method="POST">
    <div class="row g-3 p-3">
        <h4 class="h4">Změna hesla</h4>

        <div class="form-group col-md-6">
                <label for="userEditPassOld" class="form-label">Aktuální heslo</label>
                <input type="password" class="form-control" id="userEditPassOld" name="userEditPassOld" placeholder="Heslo" required>
        </div>
        <div class="mt-0 col-md-6"></div>
        <div class="form-group col-md-6">
            <label for="userEditPass1" class="form-label">Nové Heslo</label>
            <input type="password" class="form-control" id="userEditPass1" name="userEditPass1" placeholder="Heslo" required>
        </div>

        <div class="form-group col-md-6">
            <label for="userEditPass2" class="form-label">Nové heslo znovu</label>
            <input type="password" class="form-control" id="userEditPass2" name="userEditPass2" placeholder="Heslo" required>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-outline-primary col-12 col-md-auto" value="Změnit" name="userEditPassSubmit">
        </div>
    </div>


</form>

