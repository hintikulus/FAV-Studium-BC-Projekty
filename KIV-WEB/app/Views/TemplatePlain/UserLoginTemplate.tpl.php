<?php

global $tplData;

if(isset($tplData['errors'])) {

}

?>

<div class="row mb-3 justify-content-center">
    <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-6 col-sm-12 col-xs-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">

                <h3>Přihlášení</h3>
            </div>
            <form class="card-body" method="post">
                <div class="row g-3">
                    <div class="form-group col-12">
                        <label for="loginUserName" class="form-label">Uživalské jméno nebo E-Mail</label>
                        <input type="text" class="form-control" id="loginUserName" name="loginUserName" placeholder="Uživalské jméno nebo E-Mail">
                    </div>

                    <div class="form-group col-12">
                        <label for="loginPassWord" class="form-label">Heslo</label>
                        <input type="password" class="form-control" id="loginPassWord" name="loginPassWord" placeholder="Heslo">
                    </div>
                    <div class="form-group d-grid d-md-flex">
                        <input type="submit" class="btn btn-outline-primary" value="Přihlášení" name="loginSubmit">
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>


