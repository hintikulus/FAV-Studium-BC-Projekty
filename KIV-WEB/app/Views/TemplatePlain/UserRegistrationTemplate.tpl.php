<?php

global $tplData;

if(isset($tplData['errors'])) {

}

?>

<div class="row mb-3 justify-content-center">
    <div class="col-sm-12 col-md-8">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">

                <h3>Registrace</h3>
            </div>
            <form class="card-body" method="post">
                <div class="row g-3">
                    <div class="form-group col-md-6">
                        <label for="registrationName" class="form-label">Křestní jméno</label>
                        <input type="text" class="form-control" id="registrationName" name="registrationName" placeholder="Křestní jméno" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="registrationSurName" class="form-label">Příjmení</label>
                        <input type="text" class="form-control" id="registrationSurName" name="registrationSurName" placeholder="Příjmení" required>
                    </div>

                    <div class="form-group col-12">
                        <label for="registrationEMail" class="form-label">E-Mail</label>
                        <input type="email" class="form-control" id="registrationEMail" name="registrationEMail" placeholder="voprsalek@example.com" required>
                    </div>

                    <div class="form-group col-12">
                        <label for="registrationUserName" class="form-label">Uživalské jméno</label>
                        <input type="text" class="form-control" id="registrationUserName" name="registrationUserName" placeholder="Uživalské jméno" required>
                    </div>

                    <div class="form-group col-12">
                        <label for="registrationPassWord" class="form-label">Heslo</label>
                        <input type="password" class="form-control" id="registrationPassWord" name="registrationPassWord" placeholder="Heslo" required>
                    </div>

                    <div class="form-group col-12">
                        <label for="registrationPassWord2" class="form-label">Ověření hesla</label>
                        <input type="password" class="form-control" id="registrationPassWord2" name="registrationPassWord2" placeholder="Ověření hesla" required>
                    </div>
                    <div class="from-group col-md-6">
                        <input class="form-check-input" type="checkbox" id="registrationSouhlas" name="registrationSouhlas" required>
                        <label class="form-check-label" for="registrationSouhlas">
                            Souhlasím s podmínkami užití
                        </label>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="g-recaptcha float-end" data-sitekey="6Ld7FxcaAAAAAHdEC-QxRz1y_2_ybOPOtZRzoG5j"></div>
                    </div>
                    <div class="form-group d-grid d-md-flex">
                        <input type="submit" class="btn btn-outline-primary" value="Registrace" name="registrationSubmit">
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>


