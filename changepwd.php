<?php
function changePassword($uid, $employeeNumber, $newPassword) {
    include('ldap_connection.php');
    // Recherche de l'utilisateur dans LDAP
    $filter = "(&(uid=$uid)(employeeNumber=$employeeNumber))";
    $ldapSearch = ldap_search($ldapConn, $searchParam, $filter);
    $entry = ldap_get_entries($ldapConn, $ldapSearch);
    // si il existe on change le mot de passe
    if ($entry['count'] == 1) {
        // Modification du mot de passe si l'user existe
        $userDN = $entry[0]['dn'];
        $userData = ['userPassword' => $newPassword];
        ldap_modify($ldapConn, $userDN, $userData);
        echo "<p>Le mot de passe a été changé avec succès.</p>";
        echo  "<p>Retourner sur la page de connexion: <a href=\"auth.php\">cliquer ici</a> </p>";
    } else {
        echo "Erreur : Utilisateur non trouvé ou informations incorrectes.";
    }
    ldap_close($ldapConn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST["uid"];
    $employeeNumber = $_POST["employeeNumber"];
    $newPassword = $_POST["newPassword"];
    changePassword($uid, $employeeNumber, $newPassword);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Changement de mot de passe</title>
</head>
<body>
    <h2>Changement de mot de passe</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="uid">UID:</label>
        <input type="text" name="uid" required><br>

        <label for="employeeNumber">Numéro employé:</label>
        <input type="text" name="employeeNumber" required><br>

        <label for="newPassword">Nouveau mot de passe:</label>
        <input type="password" name="newPassword" required><br>

        <input type="submit" value="Changer le mot de passe">
    </form>
</body>
</html>
