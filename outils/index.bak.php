<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Outils interactifs</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <!-- iOS Safari -->
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous"
referrerpolicy="no-referrer" />
    <style>
        :root {
            /* Dark theme defaults */
            --bg-primary: #000;
            --bg-secondary: #1a1a1a;
            --bg-hover: #2c2c2c;
            --text-primary: #fff;
            --text-secondary: #ccc;
            --text-muted: #333;
            --accent-primary: #0d47a1;
            --accent-secondary: #1976d2;
            --border-primary: #0d47a1;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            --shadow-light: 0 4px 20px rgba(0, 0, 0, 0.1);
            --gradient: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
            --popup-bg: #fff;
            --popup-text: #000;
            --btn-bg: #ccc;
            --username-color: #ddd;
            --footer-color: #333;
            --pencil-fill: #161616;
        }

        [data-theme="light"] {
            --bg-primary: #f8f9fa;
            --bg-secondary: #e9ecef;
            --bg-hover: #dee2e6;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --text-muted: #adb5bd;
            --accent-primary: #0d47a1;
            --accent-secondary: #1976d2;
            --border-primary: #0d47a1;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --shadow-light: 0 4px 20px rgba(0, 0, 0, 0.05);
            --gradient: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
            --popup-bg: #fff;
            --popup-text: #000;
            --btn-bg: #6c757d;
            --username-color: #495057;
            --footer-color: #6c757d;
            --pencil-fill: #161616;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100%; width: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-primary); 
            color: var(--text-primary);
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
        }
        .container {
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            min-height: 100%; text-align: center; padding: 20px;
            animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .logo { 
            width: 30vw; max-width: 150px; height: auto; margin-bottom: 30px;
            filter: drop-shadow(0 4px 8px rgba(13, 71, 161, 0.3));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(20px);
            animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards;
        }
        .logo:hover { 
            filter: drop-shadow(0 4px 12px rgba(13, 71, 161, 0.5)); 
            transform: scale(1.05) translateY(-2px);
        }
        @keyframes slideInUp {
            to {
                transform: translateY(0);
            }
        }
        h1 { 
            font-size: clamp(18px, 5.5vw, 24px); 
            margin-bottom: 15px; 
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            transform: translateY(20px);
            animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.4s forwards;
        }
        p { 
            font-size: clamp(14px, 4vw, 16px); 
            max-width: 90%; 
            line-height: 1.6; 
            margin-bottom: 25px; 
            color: var(--text-secondary);
            transform: translateY(20px);
            animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.6s forwards;
        }
        .tools-list {
            width: 100%; max-width: 600px;
            display: flex; flex-direction: column;
            gap: 15px; margin-bottom: 30px;
            transform: translateY(30px);
            animation: slideInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.8s forwards;
        }
        .tool-item {
            background-color: var(--bg-secondary); 
            padding: 20px;
            border-radius: var(--border-radius); 
            text-align: left;
            text-decoration: none; 
            color: var(--text-primary); 
            display: block;
            border-left: 4px solid var(--border-primary);
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px);
        }
        .tools-list .tool-item:nth-child(1) { animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1s forwards; }
        .tools-list .tool-item:nth-child(2) { animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.1s forwards; }
        .tools-list .tool-item:nth-child(3) { animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.2s forwards; }
        .tools-list .tool-item:nth-child(4) { animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.3s forwards; }
        .tools-list .tool-item:nth-child(5) { animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.4s forwards; }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .tool-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient);
            transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .tool-item:hover { 
            background-color: var(--bg-hover); 
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }
        .tool-item:hover::before { transform: scaleX(1); }
        .tool-item h2 {
            font-size: clamp(16px, 4.5vw, 20px);
            margin-bottom: 8px; 
            display: flex;
            align-items: center; 
            gap: 12px;
            font-weight: 500;
        }
        .tool-item p {
            font-size: clamp(14px, 3.5vw, 16px);
            color: var(--text-secondary); 
            margin-bottom: 0;
            line-height: 1.4;
        }
        .tool-icon { 
            font-size: 18px; 
            color: var(--accent-primary); 
            width: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .tool-item:hover .tool-icon { 
            color: var(--accent-secondary); 
            transform: rotate(10deg) scale(1.1);
        }
        .submit-btn {
            padding: 12px 24px; 
            background: var(--gradient);
            color: #fff; 
            border: none; 
            border-radius: var(--border-radius);
            cursor: pointer; 
            margin: 5px; 
            display: inline-flex;
            align-items: center; 
            gap: 8px; 
            text-decoration: none;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow);
            transform: translateY(0);
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 71, 161, 0.4);
        }
        .username { 
            color: var(--username-color); 
            cursor: pointer; 
            margin-left: 5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .username:hover { 
            text-decoration: underline; 
            color: var(--accent-primary); 
            transform: scale(1.05);
        }
        .pencil-icon { 
            width: 16px; 
            height: 16px; 
            margin-right: 8px; 
            vertical-align: middle;
            fill: var(--pencil-fill);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .username:hover .pencil-icon {
            fill: var(--accent-primary);
            transform: rotate(180deg);
        }
        .popup {
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%;
            background: rgba(0,0,0,0.5); 
            z-index: 2000; 
            display: none;
            align-items: center; 
            justify-content: center;
            backdrop-filter: blur(8px);
            animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .popup-content {
            background: var(--popup-bg); 
            color: var(--popup-text);
            width: 80%; 
            max-width: 400px; 
            padding: 24px;
            border-radius: var(--border-radius); 
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(0.9) translateY(-20px);
            opacity: 0;
        }
        .popup.show .popup-content {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
        .popup-content p { 
            font-size: 16px; 
            margin-bottom: 16px; 
            line-height: 1.5;
            color: var(--popup-text);
        }
        .close-btn {
            padding: 10px 20px; 
            background: var(--btn-bg);
            border: none; 
            border-radius: 8px; 
            cursor: pointer;
            color: var(--text-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 5px;
        }
        .close-btn:hover { 
            background: var(--accent-primary); 
            color: #fff; 
            transform: translateY(-1px);
        }
        .about-link { 
            color: var(--accent-primary); 
            text-decoration: none; 
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .about-link:hover { 
            text-decoration: underline; 
            color: var(--accent-secondary); 
            transform: translateX(5px);
        }
        .username-input {
            padding: 12px; 
            width: 80%; 
            margin: 12px 0;
            border: 1px solid var(--text-secondary); 
            border-radius: 8px; 
            font-size: 16px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .username-input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.15);
            transform: scale(1.02);
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
                transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            }
        }
        /* Header avec horloge et toggle thème */
        .header {
            position: absolute; 
            top: 10px; 
            right: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 1000;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.1s forwards;
        }
        @keyframes slideInDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        #clock {
            font-size: 18px; 
            font-weight: 600; 
            color: var(--accent-primary);
            font-family: 'SF Mono', Monaco, monospace; 
            text-align: right;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #date {
            font-size: 12px; 
            font-weight: normal; 
            color: var(--text-secondary);
            margin-bottom: 2px;
        }
        #colon {
            animation: fadeColon 2s infinite;
        }
        @keyframes fadeColon {
            0%   { opacity: 0; }
            50%  { opacity: 1; }
            100% { opacity: 0; }
        }
        .theme-toggle {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-primary);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-light);
            transform: rotate(0deg);
        }
        .theme-toggle:hover {
            background: var(--bg-secondary);
            color: var(--accent-primary);
            transform: scale(1.1) rotate(180deg);
        }
        .theme-toggle i {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body data-theme="dark">
    <!-- Header avec horloge et toggle thème -->
    <div class="header">
        <div id="clock">
            <div id="date"></div>
            <div>
                <span id="hours"></span><span id="colon">:</span><span id="minutes"></span>
            </div>
        </div>
        <button class="theme-toggle" onclick="toggleTheme()" title="Changer de thème">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
    </div>
    <!-- Popup À propos -->
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org, tous droits réservés</p>
            <p>API : 0.dev</p>
            <p>App : 1.0</p>
            <button class="close-btn" onclick="closePopup('aboutPopup')">Fermer</button>
        </div>
    </div>
    <!-- Popup Confidentialité -->
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n’est collectée ni enregistrée. Toutes les données que vous entrez dans les formulaires sont stockées uniquement sur votre appareil.</p>
            <button class="close-btn" onclick="closePopup('privacyPopup')">Fermer</button>
        </div>
    </div>
    <!-- Popup Username -->
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez le nom à afficher sur les rapports PDF que vous générez.</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" onclick="saveUsername()">Enregistrer</button>
            <button class="close-btn" onclick="closePopup('usernamePopup')">Annuler</button>
        </div>
    </div>
    <!-- Contenu principal -->
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage"></h1>
        <p>Explorez nos outils interactifs pour mieux comprendre et gérer votre
bien-être.</p>
        <!-- Liste des outils -->
        <div class="tools-list">
            <!-- <a href="routine-quotidienne.php" class="tool-item">
                <h2><i class="fas fa-calendar-check tool-icon"></i> Routine quotidienne</h2>
                <p>Planifiez vos actions quotidiennes pour structurer votre journée.</p>
            </a> -->
            <a href="journal-des-emotions.php" class="tool-item">
                <h2><i class="fas fa-face-smile tool-icon"></i> Journal des émotions</h2>
                <p>Identifiez et explorez vos émotions pour mieux les comprendre.</p>
            </a>
            <a href="journal-de-gratitude.php" class="tool-item">
                <h2><i class="fas fa-book tool-icon"></i> Journal de gratitude</h2>
                <p>Enregistrez des pensées positives pour cultiver votre bien-être.</p>
            </a>
            <a href="jauge-d-humeur.php" class="tool-item">
                <h2><i class="fas fa-gauge-high tool-icon"></i> Jauge d’humeur</h2>
                <p>Suivez vos variations d’humeur pour mieux gérer votre quotidien.</p>
            </a>
            <a href="pyramide-des-besoins.php" class="tool-item">
                <h2><i class="fas fa-layer-group tool-icon"></i> Pyramide des besoins</h2>
                <p>Évaluez vos besoins fondamentaux pour prioriser votre bien-être.</p>
            </a>
            <a href="balance-decisionnelle.php" class="tool-item">
                <h2><i class="fas fa-scale-balanced tool-icon"></i> Balance décisionnelle</h2>
                <p>Pesez le pour et le contre pour faciliter vos décisions importantes.</p>
            </a>
        </div>
        <!-- Bouton Retour -->
        <a href="/v1/?v=<?php echo $_COOKIE['client_version']; ?>" class="submit-btn" style="margin-top: 20px;"><i class="fas fa-arrow-left"></i> Retour</a>
        <p style="margin-top: 5em; color: var(--footer-color);">
            v<?php echo $_COOKIE['client_version']; ?> • Accès anticipé<br>
            <a class="about-link" onclick="showPopup('aboutPopup')">À propos</a> &bull; <a class="about-link" onclick="showPopup('privacyPopup')">Confidentialité</a><br/>
            <span style="color: var(--text-muted);">&copy;2025 SanteMentale.org</span><br>
        </p>
    </div>
    <script>
        // Fonctions pour popups avec animations
        function showPopup(id) {
            const popup = document.getElementById(id);
            popup.style.display = 'flex';
            setTimeout(() => popup.classList.add('show'), 10);
        }

        function closePopup(id) {
            const popup = document.getElementById(id);
            popup.classList.remove('show');
            setTimeout(() => popup.style.display = 'none', 300);
        }

        // Gestion du thème
        function toggleTheme() {
            const body = document.body;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            const themeIcon = document.getElementById('themeIcon');
            themeIcon.className = newTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
        }

        // Chargement du thème basé sur la préférence système
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialTheme = savedTheme || (prefersDark ? 'dark' : 'light');
            document.body.setAttribute('data-theme', initialTheme);
            const themeIcon = document.getElementById('themeIcon');
            themeIcon.className = initialTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';

            // Écouteur pour changements de thème système si aucun thème sauvegardé
            if (!savedTheme) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addEventListener('change', (e) => {
                    const newTheme = e.matches ? 'dark' : 'light';
                    document.body.setAttribute('data-theme', newTheme);
                    themeIcon.className = newTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
                });
            }
        });

        // Gestion du nom d'utilisateur
        document.addEventListener('DOMContentLoaded', () => {
            const usernameKey = 'username';
            let username = localStorage.getItem(usernameKey);
            if (!username) {
                localStorage.setItem(usernameKey, 'visiteur');
                username = 'visiteur';
            }
            const welcomeMessage = document.getElementById('welcomeMessage');
            welcomeMessage.innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="showPopup('usernamePopup')">${username},</span>`;
        });
        window.saveUsername = function() {
            const input = document.getElementById('usernameInput');
            const newUsername = input.value.trim() || 'visiteur';
            localStorage.setItem('username', newUsername);
            document.getElementById('welcomeMessage').innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="showPopup('usernamePopup')">${newUsername},</span>`;
            closePopup('usernamePopup');
        };

        // Horloge fade in/out avec date
        function updateClock() {
            const now = new Date();
            let h = now.getHours().toString().padStart(2, '0');
            let m = now.getMinutes().toString().padStart(2, '0');
            document.getElementById('hours').textContent = h;
            document.getElementById('minutes').textContent = m;
            const mois = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"];
            let jour = now.getDate();
            let moisNom = mois[now.getMonth()];
            document.getElementById('date').textContent = `${jour} ${moisNom}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
