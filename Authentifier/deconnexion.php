<?php
session_start();

// 1) Vider les variables de session
$_SESSION = [];

// 2) Supprimer le cookie de session (si présent)
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// 3) Détruire la session
session_destroy();

// 4) Anti-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// 5) Redirection côté serveur
header('Location: ../index.php');
exit();
?>
<!-- Si tu préfères forcer un remplacement d'historique côté client :
   - commente la redirection PHP ci-dessus (les 2 lignes) et remplace par :


<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Déconnexion…</title></head>
<body>
<script>window.location.replace('index.php');</script>
<noscript><meta http-equiv="refresh" content="0;url=index.php"></noscript>
</body></html>
 -->
