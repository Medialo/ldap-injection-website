<?php
function searchUsers($searchTerm, $text) {
    include('ldap_connection.php');
    $filter = "(&(cn=*$searchTerm*))";  // filtre de recherche volontairement injectable
    $ldapSearch = ldap_search($ldapConn, $searchParam, $filter);
    if ($ldapSearch) {
        $entries = ldap_get_entries($ldapConn, $ldapSearch);
        if ($entries['count'] > 0) {
            // Affiche les résultats
            $text .= "<h3>Résultats de la recherche:</h3>";
            $text .= "<ul>";
            for ($i = 0; $i < $entries['count']; $i++) {
                $uid = $entries[$i]['uid'][0];
                $cn = $entries[$i]['cn'][0];
                $email = $entries[$i]['mail'][0];
                $text .= "<li><strong>ID:</strong> $uid, <strong>Nom Prenom:</strong> $cn, <strong>Email:</strong> $email</li>";
            }
            $text .= "</ul>";
        } else {
            $text .= "<p>Aucun résultat trouvé pour la recherche : $searchTerm</p>";
        }
    } else {
        $text .= "<p>Erreur lors de la recherche LDAP</p>";
    }
    ldap_close($ldapConn);
    return $text;
}

$text = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchTerm = $_POST['searchTerm'];
    if (!empty($searchTerm)) {
        $text = searchUsers($searchTerm, $text);
    } else {
        $text = "<p>Veuillez saisir un terme de recherche.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recherche d'Utilisateurs LDAP</title>
</head>
<body>
<h2>Recherche d'Utilisateurs de l'entreprise</h2>
<form method="post" action="">
    <label for="searchTerm">Rechercher par nom prénom :</label>
    <input size="50" type="text" id="searchTerm" name="searchTerm" required>
    <br>
    <input type="submit" value="Rechercher">
</form>
<?php echo $text; ?>
</body>
</html>
