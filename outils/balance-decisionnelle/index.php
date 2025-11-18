<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanteMentale.org - Bientôt disponible</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <!-- Manifest PWA (désactivé pour éviter le popup installation) -->
    <link rel="manifest" href="/v1/manifest.json">
    <!-- Couleur de la barre sur mobile -->
    <meta name="theme-color" content="#2c3e50">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Réinitialisation et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
        }
        /* Conteneur principal */
        .container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            min-height: 100%;
            text-align: center;
            padding: 5%;
            width: 90%;
            margin: 0 auto;
        }
        /* Logo */
        .logo {
            width: 15vw;
            max-width: 75px;
            height: auto;
            margin-bottom: 20px;
        }
        /* Titres et textes */
        h1 {
            font-size: clamp(18px, 5.5vw, 24px);
            margin-bottom: 15px;
        }
        p {
            font-size: 4vw;
            max-width: 90%;
            line-height: 1.5;
            margin-bottom: 25px;
        }
        /* Zone d'affichage */
        .coming-soon {
            width: 100%;
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #0d47a1;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .coming-soon i {
            font-size: 5em;
            color: #0d47a1;
            margin-bottom: 15px;
        }
        .coming-soon p {
            font-size: clamp(16px, 4vw, 18px);
            color: #ccc;
            max-width: 80%;
        }
        /* Boutons */
        .submit-btn {
            padding: 10px 20px;
            background-color: #0d47a1;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none; /* Pas de soulignement */
        }
        /* Style pour le nom d'utilisateur */
        .username {
            color: #ddd;
            cursor: pointer;
            margin-left: 5px;
        }
        .username:hover {
            text-decoration: underline;
        }
        .pencil-icon {
            width: 14px;
            height: 14px;
            margin-right: 5px;
            vertical-align: middle;
        }
        /* Popup (À propos, Confidentialité, Username) */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .popup-content {
            background: white;
            color: black;
            width: 80%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .popup-content p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .close-btn {
            padding: 10px 20px;
            background: #ccc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        /* Style pour le lien À propos et Confidentialité */
        .about-link {
            color: #0d47a1;
            text-decoration: none;
            cursor: pointer;
        }
        .about-link:hover {
            text-decoration: underline;
        }
        /* Style pour le formulaire dans la popup */
        .username-input {
            padding: 8px;
            width: 80%;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        /* Gestion du mode paysage mobile uniquement */
        @media (max-width: 767px) and (orientation: landscape) {
            body {
                transform: rotate(90deg);
                transform-origin: left top;
                width: 100vh;
                height: 100vw;
                overflow-x: hidden;
                position: absolute;
                top: 100%;
                left: 0;
            }
            .container {
                padding: 3%;
            }
        }
        /* Ajustement pour petits écrans */
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
        }
        /* Compatibilité desktop */
        @media (min-width: 768px) {
            body {
                transform: none !important;
                width: 100% !important;
                height: auto !important;
                position: static !important;
                top: auto !important;
                left: auto !important;
            }
            .container {
                max-width: 900px;
                margin: 0 auto;
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Popup À propos -->
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org, tous droits réservés</p>
            <p>API : 0.dev</p>
            <p>App : <?php echo htmlspecialchars($_COOKIE['client_version'] ?? 'Non définie'); ?></p>
            <button class="close-btn" onclick="document.getElementById('aboutPopup').style.display = 'none';">Fermer</button>
        </div>
    </div>
    <!-- Popup Confidentialité -->
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n’est collectée ni enregistrée. Toutes les données que vous entrez dans les formulaires sont stockées uniquement sur votre appareil.</p>
            <button class="close-btn" onclick="document.getElementById('privacyPopup').style.display = 'none';">Fermer</button>
        </div>
    </div>
    <!-- Popup pour modifier le nom d'utilisateur -->
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez le nom à afficher sur les rapports PDF que vous générez.</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" onclick="saveUsername()">Enregistrer</button>
            <button class="close-btn" onclick="document.getElementById('usernamePopup').style.display = 'none';">Annuler</button>
        </div>
    </div>
    <!-- Contenu principal -->
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage"></h1>
        <div class="coming-soon">
            <i class="fas fa-laptop-code"></i>
            <p>Bientôt disponible ! Notre équipe travaille d’arrache-pied pour vous concocter un nouveau module passionnant. Restez à l’écoute, de belles surprises vous attendent très prochainement. Merci de votre patience et de votre soutien ! 😊</p>
        </div>
        <a href="/v1/outils/?v=1.0&msg=patched" class="submit-btn" style="margin-top: 20px;"><i class="fas fa-arrow-left"></i> Retour</a>
        <p style="margin-top: auto; color: #333;">
            v<?php echo htmlspecialchars($_COOKIE['client_version'] ?? 'Non définie'); ?> • Accès anticipé<br>
            <a class="about-link" onclick="document.getElementById('aboutPopup').style.display = 'flex';">À propos</a> &bull; <a class="about-link" onclick="document.getElementById('privacyPopup').style.display = 'flex';">Confidentialité</a><br/>
            <span style="color: #161616;">&copy;2025 SanteMentale.org</span><br>
        </p>
    </div>
    <script>
        // Gestion du nom d'utilisateur
        document.addEventListener('DOMContentLoaded', () => {
            const usernameKey = 'username';
            let username = localStorage.getItem(usernameKey);
            if (!username) {
                localStorage.setItem(usernameKey, 'visiteur');
                username = 'visiteur';
            }
            const welcomeMessage = document.getElementById('welcomeMessage');
            welcomeMessage.innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="document.getElementById('usernamePopup').style.display = 'flex';">${username},</span>`;
        });

        // Fonction pour enregistrer le nom d'utilisateur
        window.saveUsername = function() {
            const input = document.getElementById('usernameInput');
            const newUsername = input.value.trim() || 'visiteur';
            localStorage.setItem('username', newUsername);
            document.getElementById('welcomeMessage').innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="document.getElementById('usernamePopup').style.display = 'flex';">${newUsername},</span>`;
            document.getElementById('usernamePopup').style.display = 'none';
        };
    </script>
</body>
</html>
