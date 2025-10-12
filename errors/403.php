<?php
// Pas de vérification de version pour cette page
http_response_code(403);

// Récupération des paramètres d'erreur
$errorCode = $_GET['error'] ?? 'unknown';
$errorData = $_GET['data'] ?? '';

// Configuration des messages d'erreur
$errorMessages = [
    'bad-version' => [
        'title' => 'Version invalide',
        'description' => 'La version de l\'application spécifiée (' . htmlspecialchars($errorData) . ') n\'est pas reconnue ou n\'est plus supportée.',
        'details' => [
            'Votre application est peut-être obsolète',
            'La version demandée n\'existe pas',
            'Les données locales ont été modifiées'
        ]
    ],
    'no-version' => [
        'title' => 'Version manquante',
        'description' => 'Aucune version de l\'application n\'a été spécifiée.',
        'details' => [
            'Vous avez accédé directement à une page interne',
            'Les paramètres de navigation sont manquants',
            'Veuillez accéder via la page principale'
        ]
    ],
    'unknown' => [
        'title' => 'Erreur inconnue',
        'description' => 'Une erreur inattendue s\'est produite.',
        'details' => [
            'L\'accès à cette ressource a été refusé',
            'Veuillez retourner à la page principale'
        ]
    ]
];

// Sélection du message approprié
$error = $errorMessages[$errorCode] ?? $errorMessages['unknown'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Erreur 403</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <link rel="manifest" href="/v1/manifest.json">
    <meta name="theme-color" content="#0d47a1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #1e1e1e;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --text-muted: #555555;
            --accent-primary: #0d47a1;
            --accent-secondary: #42a5f5;
            --gradient-blue: linear-gradient(135deg, #42a5f5, #0d47a1);
            --gradient-red: linear-gradient(90deg, #e63946, #d00000);
            --border-radius: 12px;
            --shadow: 0 6px 24px rgba(0, 0, 0, 0.4);
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.2);
            --popup-bg: #ffffff;
            --popup-text: #1a1a1a;
            --btn-bg: #e0e0e0;
            --username-color: #e0e0e0;
            --footer-color: #555555;
            --pencil-fill: #333333;
            --gray-btn: #555555;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            width: 100%;
            font-family: 'Quicksand', Arial, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
            position: relative;
        }
        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 48px;
            height: auto;
            filter: drop-shadow(0 4px 12px rgba(13, 71, 161, 0.3));
            transform: translateX(100%);
            opacity: 0;
            animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.4s forwards;
        }
        .error-icon {
            font-size: 96px;
            color: #e63946;
            margin-bottom: 24px;
            filter: drop-shadow(0 4px 12px rgba(214, 48, 49, 0.3));
            transform: translateY(20px);
            opacity: 0;
            animation: slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards, pulse 2.5s infinite ease-in-out;
        }
        @keyframes slideInUp {
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideInRight {
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 0.7; }
        }
        @keyframes vibrate {
            0% { transform: translateX(0); }
            25% { transform: translateX(-2px) translateY(1px); }
            50% { transform: translateX(2px) translateY(-1px); }
            75% { transform: translateX(-1px) translateY(2px); }
            100% { transform: translateX(0); }
        }
        h1 {
            font-size: clamp(20px, 6vw, 28px);
            margin-bottom: 20px;
            background: var(--gradient-red);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            transform: translateY(-100%);
            opacity: 0;
            animation: slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards, vibrate 0.3s infinite ease-in-out;
        }
        @keyframes slideInDown {
            to { transform: translateY(0); opacity: 1; }
        }
        p.description {
            font-size: clamp(14px, 4vw, 16px);
            max-width: 90%;
            line-height: 1.6;
            margin-bottom: 20px;
            color: var(--text-secondary);
            transform: translateX(100%);
            opacity: 0;
            animation: slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.8s forwards;
        }
        .error-details {
            max-width: 90%;
            text-align: left;
            margin: 20px auto;
            padding: 0;
            list-style: none;
            transform: translateX(100%);
            opacity: 0;
            animation: slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) 1s forwards;
        }
        .error-details li {
            position: relative;
            padding-left: 24px;
            margin-bottom: 12px;
            color: var(--text-secondary);
            font-size: clamp(13px, 3.5vw, 15px);
            line-height: 1.5;
        }
        .error-details li::before {
            content: '•';
            position: absolute;
            left: 8px;
            color: #e63946;
            font-weight: bold;
            font-size: 18px;
        }
        .return-link {
            position: relative;
            padding: 16px 24px;
            font-size: clamp(14px, 4vw, 16px);
            background: var(--gradient-blue);
            color: var(--text-primary);
            border: none;
            border-radius: var(--border-radius);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-light);
            font-weight: 600;
            margin-top: 20px;
            transform: scale(0);
            animation: scaleUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.4s forwards;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .return-link:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 28px rgba(13, 71, 161, 0.5);
        }
        .return-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: var(--border-radius);
            box-shadow: 0 0 10px 2px var(--accent-secondary), 0 0 20px 4px var(--accent-primary);
            opacity: 0.5;
            animation: glowPulse 2s infinite ease-in-out;
            z-index: -1;
        }
        .return-link span {
            position: relative;
            z-index: 1;
        }
        .return-link .material-icons {
            margin-right: 8px;
            font-size: 20px;
        }
        @keyframes glowPulse {
            0% { opacity: 0.5; box-shadow: 0 0 10px 2px var(--accent-secondary), 0 0 20px 4px var(--accent-primary); }
            50% { opacity: 1; box-shadow: 0 0 15px 4px var(--accent-secondary), 0 0 25px 6px var(--accent-primary); }
            100% { opacity: 0.5; box-shadow: 0 0 10px 2px var(--accent-secondary), 0 0 20px 4px var(--accent-primary); }
        }
        @keyframes scaleUp {
            to { transform: scale(1); }
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        .footer {
            opacity: 0;
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) 2.0s forwards;
        }
        .error-code {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 10px;
            font-family: monospace;
        }
        @media (orientation: landscape) {
            body {
                transform: rotate(90deg);
                transform-origin: left top;
                width: 100vh;
                height: 100vw;
                overflow-x: hidden;
                position: absolute;
                top: 100%;
                left: 0;
                transition: transform 0.375s cubic-bezier(0.4, 0, 0.2, 1);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <span class="material-icons error-icon">block</span>
        <h1>Erreur 403 &mdash; <?php echo htmlspecialchars($error['title']); ?></h1>
        <p class="description">
            <?php echo htmlspecialchars($error['description']); ?>
        </p>
        <ul class="error-details">
            <?php foreach ($error['details'] as $detail): ?>
                <li><?php echo htmlspecialchars($detail); ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="error-code">Code: <?php echo htmlspecialchars($errorCode); ?></p>
        <a href="https://santementale.org" class="return-link">
            <span class="material-icons">home</span>
            <span>Retour au site principal</span>
        </a>
        <p class="footer" style="margin-top: 3em; color: var(--footer-color); line-height: 1.8; font-size: 14px;">
            <span id="appVersionFooter"></span><br>
            <span style="color: #161616;">©2025 SanteMentale.org</span>
        </p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const clientVersionKey = 'client_version';
            const clientVersion = localStorage.getItem(clientVersionKey) || 'Non défini';
            document.getElementById('appVersionFooter').textContent = `App v${clientVersion} • Mod 0.11`;
            
            // Forcer le déclenchement des animations d'entrée
            document.querySelector('.logo').style.animation = 'none';
            document.querySelector('.error-icon').style.animation = 'none';
            document.querySelector('h1').style.animation = 'none';
            document.querySelector('.description').style.animation = 'none';
            document.querySelector('.error-details').style.animation = 'none';
            document.querySelector('.return-link').style.animation = 'none';
            
            setTimeout(() => {
                document.querySelector('.logo').style.animation = 'slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.4s forwards';
                document.querySelector('.error-icon').style.animation = 'slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards, pulse 2.5s infinite ease-in-out';
                document.querySelector('h1').style.animation = 'slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards, vibrate 0.3s infinite ease-in-out';
                document.querySelector('.description').style.animation = 'slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.8s forwards';
                document.querySelector('.error-details').style.animation = 'slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) 1s forwards';
                document.querySelector('.return-link').style.animation = 'scaleUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.4s forwards';
            }, 0);
        });
    </script>
</body>
</html>
