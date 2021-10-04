<?php
///////////////////////////////////////////////////////////////////////////
///////////  Šablona pro zobrazení stránky pro správu uživatelů  /////////////
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

?>

<div class="row">
    <p>Správa uživatelů slouží pro přehled registrovaných uživatelů, zobrazení jejich informací, změnu jejich role a případné smazání.</p>
</div>

<div class="row">
<?php

if(!empty($tplData['users'])) {
    $res = "<div class='table-responsive-md'>
            <table class='table table-hover'>
                <tr>
                    <th>ID</th>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Login</th>
                    <th>E-mail</th>
                    <th>Role</th>
                    <th>Akce</th>
                </tr>";
// projdu data a vypisu radky tabulky

    foreach ($tplData['users'] as $u) {
        $res .= '<tr>
                <td>' . $u['id_user'] . '</td>
                <td>' . $u['name'] . '</td>
                <td>' . $u['surname'] . '</td>
                <td><a href="?page=profile&user=' . $u['login'] . '">' . $u['login'] . '</a></td>
                <td>' . $u['email'] . '</td>
                <td>
                    <form method="post">
                    <input type="hidden" name="id_user" value="' . $u['id_user'] . '">
                       <div class="input-group">
                          <select class="form-select" name="adminUserManagementRole" aria-label="Nastavení role">
                            <option 
                                value="' . Roles::ROLE_AUTHOR . '" ' . ($u['id_role'] == Roles::ROLE_AUTHOR ? "selected" : "") . '>
                                ' . Roles::ROLE_AUTHOR_STRING . '
                            </option>
                            <option 
                                value="' . Roles::ROLE_REVIEWER . '" ' . ($u['id_role'] == Roles::ROLE_REVIEWER ? "selected" : "") . '>
                                ' . Roles::ROLE_REVIEWER_STRING . '
                            </option>
                            <option 
                                value="' . Roles::ROLE_ADMINISTRATOR . '" ' . ($u['id_role'] == Roles::ROLE_ADMINISTRATOR ? "selected" : "") . '>
                                ' . Roles::ROLE_ADMINISTRATOR_STRING . '
                            </option>
                          </select>
                          <button class="btn btn-outline-primary" name="action" value="roleSet" type="submit">Nastavit</button>
                       </div>
                   </form>
        
        </td>'
            . '<td><form method="post">'
            . "<input type='hidden' name='id_user' value='$u[id_user]'>"
            . "<button type='submit' name='action' class='btn btn-outline-danger' value='delete'>Smazat</button>"
            . "</form></td></tr>";
    }

    $res .= "</table></div>";
    echo $res;

    echo \konference\Models\Utilities::paginaton("admin-user", $tplData['page'], $tplData['pages']);
} else {
    echo "<h5 class='text-center mt-3'>Nejsou k dispozici žádní uživatelé</h5>";
}
?>

</div>
