<?php
    // LDAP Information
    $ldapServer = "ldap://127.0.0.1";
    $ldapPort = 389;
    $searchParam = "ou=user,dc=example,dc=com";
    $ldapBindUser = "cn=admin,dc=example,dc=com";
    $ldapBindPassword = "26042001";
    // Connexion et bind avec l'utilisateur admin
    $ldapConn = ldap_connect($ldapServer, $ldapPort);
    ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);
    $ldapBind = ldap_bind($ldapConn, $ldapBindUser, $ldapBindPassword);
    // vérification du bind avec l'utilisateur admin
    if (!$ldapBind) {
        die("Échec de la liaison LDAP");
    }   
?>
