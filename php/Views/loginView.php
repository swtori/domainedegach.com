<?php
/** @var string $loginError optionnel */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Domaine de Gach</title>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: system-ui, "Segoe UI", Roboto, sans-serif; background: #f0ebe3; }
        .login-box { background: #fff; padding: 2rem; border-radius: 14px; box-shadow: 0 6px 28px rgba(0,0,0,0.08); text-align: center; width: 100%; max-width: 320px; border: 1px solid rgba(0,0,0,0.05); }
        .login-box h1 { margin: 0 0 0.5rem; font-size: 1.5rem; color: #4a6741; font-weight: 650; }
        .login-box h2 { margin: 0 0 1.5rem; font-size: 1rem; font-weight: normal; color: #666; }
        .login-box label { display: block; text-align: left; margin-bottom: 0.5rem; font-size: 0.9rem; color: #444; }
        .login-box input { width: 100%; padding: 0.6rem; box-sizing: border-box; border: 1px solid #d0ccc4; border-radius: 8px; margin-bottom: 1rem; font-size: 1rem; }
        .login-box button { width: 100%; padding: 0.7rem; background: #4a6741; color: #fff; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; font-weight: 600; }
        .login-box button:hover { background: #3d5536; }
        .login-box .msg { margin-bottom: 1rem; font-size: 0.9rem; }
        .login-box .msg.ok { color: #2a7; }
        .login-box .msg.err { color: #c22; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Domaine de Gach</h1>
        <h2>Connexion</h2>
        <?php if (isset($_GET['admin_created']) && $_GET['admin_created'] === '1'): ?>
            <p class="msg ok">Compte créé. Connectez-vous avec vos identifiants.</p>
        <?php endif; ?>
        <?php if (!empty($loginError)): ?>
            <p class="msg err"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="action" value="login">
            <label>Identifiant<br><input type="text" name="username" required autocomplete="username" maxlength="255"></label>
            <label>Mot de passe<br><input type="password" name="password" required autocomplete="current-password"></label>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
