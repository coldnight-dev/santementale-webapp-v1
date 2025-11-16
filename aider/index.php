<?php
// Validation centralisée de la version
require_once(__DIR__ . '/../config.php');
// require_once(__DIR__ . '/../version_check.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Aider • SanteMentale.org</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <link rel="manifest" href="/v1/manifest.json">
    <meta name="theme-color" content="#0d47a1">
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
            --gradient-red: linear-gradient(90deg, #e63946, #d00000);            --gradient-pink: linear-gradient(135deg, #ffb3ba, #ff6b7a);
            --border-radius: 12px;
            --shadow: 0 6px 24px rgba(0, 0, 0, 0.4);
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.2);
            --footer-color: #555555;
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
        }
        .logo {
            width: 30vw;
            max-width: 160px;
            height: auto;
            margin-bottom: 32px;
            filter: drop-shadow(0 4px 12px rgba(13, 71, 161, 0.3));
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(20px);
            animation: slideInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards, pulse 2.5s infinite ease-in-out;
        }
        .logo:hover {
            filter: drop-shadow(0 6px 16px rgba(13, 71, 161, 0.5));
            transform: scale(1.05) translateY(-2px);
        }
        @keyframes slideInUp {
            to { transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.0363); }
            100% { transform: scale(1); }
        }
        h1 {
            font-size: clamp(24px, 7vw, 32px);
            margin-bottom: 24px;
            background: var(--gradient-pink);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            transform: translateY(-100%);
            animation: slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1)
0.2s forwards;
        }
        @keyframes slideInDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        .content-box {
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            padding: 28px;
            max-width: 500px;
            width: 90%;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            transform: scale(0);
            animation: scaleUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.8s forwards;
        }
        @keyframes scaleUp {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .content-box p {
            font-size: clamp(15px, 4vw, 17px);
            line-height: 1.7;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }
        .content-box p:last-child {
            margin-bottom: 0;
        }
        .icon-heart {
            font-size: 48px;
            color: #ff6b7a;
            margin-bottom: 16px;
            animation: heartBeat 1.5s infinite ease-in-out;
        }
        @keyframes heartBeat {
            0%, 100% { transform: scale(1); }
            10%, 30% { transform: scale(1.1); }
            20%, 40% { transform: scale(0.95); }
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .icon-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-light);
            text-decoration: none;
        }
        .icon-btn:hover {
            transform: translateY(-4px) scale(1.1);
        }
        .icon-btn.facebook {
            background: #1877f2;
        }
        .icon-btn.facebook:hover {
            box-shadow: 0 8px 20px rgba(24, 119, 242, 0.5);
        }
        .icon-btn.freecash {
            background: linear-gradient(135deg, #00d4aa, #00b894);
        }
        .icon-btn.freecash:hover {
            box-shadow: 0 8px 20px rgba(0, 212, 170, 0.5);
        }
        .icon-btn.recompenses {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }
        .icon-btn.recompenses:hover {
            box-shadow: 0 8px 20px rgba(155, 89, 182, 0.5);
        }
        .icon-btn .material-icons {
            font-size: 28px;
            color: #ffffff;
        }
        .icon-btn img {
            width: 32px;
            height: 32px;
            filter: brightness(0) invert(1);
        }
        .back-btn {
            padding: 14px 24px;
            font-size: clamp(14px, 4vw, 16px);
            background: var(--gradient-blue);
            color: var(--text-primary);
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-light);
            font-weight: 600;
            opacity: 0;
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.4s
forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .back-btn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 28px rgba(13, 71, 161, 0.5);
        }
        .back-btn .material-icons {
            font-size: 20px;
        }
        .footer {
            opacity: 0;
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) 2.0s
forwards;
        }
        .about-link {
            color: var(--accent-primary);
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .about-link:hover {
            color: var(--accent-secondary);
            text-decoration: underline;
            transform: translateX(4px);
        }
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
            opacity: 0;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .popup.show {
            display: flex;
            opacity: 1;
        }
        .popup-content {
            background: #ffffff;
            color: #1a1a1a;
            width: 90%;
            max-width: 420px;
            padding: 28px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
            transform: scale(0.9);
            opacity: 0;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .popup.show .popup-content {
            transform: scale(1);
            opacity: 1;
        }
        .popup-content p {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: #1a1a1a;
        }
        .close-btn {
            padding: 12px 24px;
            background: #e0e0e0;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            color: #1a1a1a;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 6px;
        }
        .close-btn:hover {
            background: var(--accent-primary);
            color: var(--text-primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
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
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org, tous droits réservés</p>
            <button class="close-btn" onclick="document.getElementById('aboutPopup').classList.remove('show');">Fermer</button>
        </div>
    </div>
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée ni enregistrée. Toutes les données que vous entrez dans les formulaires sont stockées uniquement sur votre appareil.</p>
            <button class="close-btn" onclick="document.getElementById('privacyPopup').classList.remove('show');">Fermer</button>
        </div>
    </div>
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage">Hey, <span id="username">visiteur</span> !</h1>
        <div class="content-box">
            <span class="material-icons icon-heart">volunteer_activism</span>
            <p>
                <strong>Vous aimez cette application ?</strong>
            </p>
            <p>
                Cette application est <strong>entièrement gratuite</strong> et <strong>sans publicités</strong>.
            </p>
            <p>
                Il sera bientôt possible de la soutenir <strong>gratuitement</strong> avec des <strong>cadeaux à gagner</strong> à la clé
!
            </p>
            <p style="margin-top: 24px; font-weight: 600; color: #ff6b7a;">
                Merci pour votre fidélité !
            </p>
            <div class="action-buttons">
                <a href="https://facebook.com/SanteMentaleOrg" target="_blank" rel="noopener noreferrer" class="icon-btn facebook" title="Suivez-nous sur Facebook">
                    <span class="material-icons">facebook</span>
                </a>
                <a href="freecash.php" class="icon-btn freecash" title="Soutenez-nous gratuitement">
                    <span class="material-icons">card_giftcard</span>                </a>
                <a href="recompenses.php" class="icon-btn recompenses" title="Regarder des vidéos récompensées">
                    <span class="material-icons">play_circle</span>
                </a>
            </div>
        </div>
        <button class="back-btn" onclick="window.history.back();">
            <span class="material-icons">arrow_back</span>
            Retour
        </button>
        <p class="footer" style="margin-top: 3em; color: var(--footer-color); line-height: 1.8; font-size: 14px;">
            <span id="appVersionFooter">X-0.251115</span><br>
            <a class="about-link" onclick="document.getElementById('aboutPopup').classList.add('show');">À propos</a> •
            <a class="about-link" onclick="document.getElementById('privacyPopup').classList.add('show');">Confidentialité</a><br>
            <span style="color: #161616;">©2025 SanteMentale.org</span>
        </p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usernameKey = 'username';
            const username = localStorage.getItem(usernameKey) || 'visiteur';
            document.getElementById('username').textContent = username;
        });
    </script>
</body>
</html>
