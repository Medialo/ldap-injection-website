<?php
function login($username, $password) {
    include('ldap_connection.php');

    $userDN = "uid=$username,ou=user,dc=example,dc=com";
    $ldapUserBind = ldap_bind($ldapConn, $userDN, $password);
    if ($ldapUserBind) {
        return "<p>Authentification réussie pour l'utilisateur $username</p>";
    } else {
        // Vérifie si l'utilisateur existe
        $ldapSearch = ldap_search($ldapConn, $searchParam, "(uid=$username)");
        $entries = ldap_get_entries($ldapConn, $ldapSearch);
        $retMessage = "";
        if ($entries['count'] > 0) {
            $retMessage = "<p>Mot de passe incorrect pour l'utilisateur <b>$username</b></p>";
        } else {
            $retMessage = "<p>L'utilisateur <b>$username</b> n'existe pas</p>";
        }
        return $retMessage;
    }
    ldap_close($ldapConn);
}
$retMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $retMessage = login($username, $password);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authentification LDAP</title>
</head>
<body>
<h2>Authentification Centralisée Example & Co</h2>
<?php echo $retMessage?>
<form method="post" action="">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>
    <br>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <input type="submit" value="Se connecter">
</form
</br>
<p>Mot de passe oublié? <a href="changepwd.php">Reset</a></p>
<p>Pour rechercher un utilisateur utiliser <a href="search.php">Outil de recherche</a></p>
</body>
</html>
