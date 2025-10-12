<?php
// Liste des versions de package publiées
$packageVersions = ['1.0', '1.1', '1.web'];
// Vérification de la version passée en GET
$clientVersion = $_GET['v'] ?? null;
// Si aucune version n'est fournie ou si elle n'est pas dans $packageVersions, rediriger
/*if ($clientVersion === null || !in_array($clientVersion, $packageVersions)) {
    header('Location: /v1/errors/403.php');
    exit;
}*/
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Appli mobile v1</title>
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
            font-size: clamp(20px, 6vw, 28px);
            margin-bottom: 20px;
            background: var(--gradient-blue);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translateY(-100%);
            animation: slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards;
        }
        .new-device-message {
            color: #f1c40f;
            font-size: clamp(20px, 6vw, 28px);
            animation: slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards;
        }
        p.description {
            font-size: clamp(14px, 4vw, 16px);
            max-width: 90%;
            line-height: 1.6;
            margin-bottom: 28px;
            color: var(--text-secondary);
            transform: translateX(100%);
            opacity: 0;
            animation: slideInRight 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.8s forwards;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        #buttonsContainer {
            transform: scale(0);
            animation: scaleUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.4s forwards;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .footer {
            opacity: 0;
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) 2.0s forwards;
        }
        @keyframes slideInDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        @keyframes scaleUp {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .cta {
            padding: 16px 16px 16px 12px;
            font-size: clamp(14px, 4vw, 16px);
            background: var(--gradient-blue);
            color: var(--text-primary);
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-light);
            font-weight: 600;
            width: 250px;
            box-sizing: border-box;
        }
        .cta:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 28px rgba(13, 71, 161, 0.5);
        }
        .cta.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            background: #555555;
        }
        .cta.settings-btn {
            background: var(--gray-btn);
        }
        .cta.settings-btn:hover {
            background: #666666;
            box-shadow: 0 10px 28px rgba(85, 85, 85, 0.5);
        }
        .cta .material-icons {
            margin-right: 12px;
            font-size: 20px;
            flex-shrink: 0;
        }
        .cta span.text {
            flex-grow: 1;
            text-align: center;
        }
        .banner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--gradient-red);
            color: var(--text-primary);
            text-align: center;
            padding: 8px;
            line-height: 1.2;
            cursor: pointer;
            z-index: 1000;
            box-shadow: var(--shadow);
            font-weight: 600;
            font-size: clamp(14px, 3.5vw, 15px);
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInDown 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .banner:hover {
            transform: translateY(2px);
            box-shadow: 0 4px 16px rgba(214, 48, 49, 0.4);
        }
        .banner-icon {
            font-size: 20px;
            margin-right: 10px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .banner:hover .banner-icon {
            transform: scale(1.1);
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
            background: var(--popup-bg);
            color: var(--popup-text);
            width: 90%;
            max-width: 420px;
            padding: 28px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
            transform: scale(0.9);
            opacity: 0;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .popup.show .popup-content {
            transform: scale(1);
            opacity: 1;
        }
        .popup-content p {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: var(--popup-text);
        }
        .close-btn {
            padding: 12px 24px;
            background: var(--btn-bg);
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            color: var(--popup-text);
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
        .install-btn {
            width: 100%;
            max-width: 300px;
            margin: 12px auto;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 16px;
        }
        .install-btn .material-icons {
            margin-right: 12px;
            font-size: 20px;
            flex-shrink: 0;
        }
        .install-btn span.text {
            flex-grow: 1;
            text-align: center;
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
        .username {
            color: var(--username-color);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .username:hover {
            color: var(--accent-secondary);
            transform: scale(1.05);
        }
        .pencil-icon {
            width: 16px;
            height: 16px;
            margin-left: 8px;
            vertical-align: middle;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            color: var(--pencil-fill);
        }
        .pencil-icon:hover {
            color: var(--accent-secondary);
            transform: rotate(180deg);
        }
        .username-input {
            padding: 12px;
            width: 85%;
            margin: 16px 0;
            border: 1px solid var(--text-secondary);
            border-radius: var(--border-radius);
            font-size: 16px;
            background: #f5f5f5;
            color: var(--popup-text);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .username-input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px rgba(13, 71, 161, 0.2);
            transform: scale(1.02);
        }
        #incompatible {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--popup-bg);
            z-index: 3000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: var(--popup-text);
            text-align: center;
            padding: 20px;
            animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        #incompatible h2 {
            font-size: clamp(18px, 5vw, 22px);
            margin-bottom: 16px;
        }
        #incompatible p {
            font-size: clamp(14px, 4vw, 16px);
            margin-bottom: 16px;
        }
        #incompatible a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #incompatible a:hover {
            color: var(--accent-secondary);
            text-decoration: underline;
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
    <div id="installBanner" class="banner">
        <span id="bannerIcon" class="material-icons banner-icon">install_mobile</span>
        Installer le raccourci pour accéder aux fonctionnalités avancées
    </div>
    <div id="installPopup" class="popup">
        <div class="popup-content">
            <p>
                Il est nécessaire d’installer le raccourci d’accès rapide sur l’appareil pour bénéficier du suivi et des outils personnalisés. Cette opération ne requiert aucune autorisation et est anonyme mais sert simplement à identifier l’appareil afin de restaurer les données associées (tests diagnostics, suivi des émotions, etc...).
            </p>
            <div id="installArea"></div>
            <button class="close-btn" onclick="document.getElementById('installPopup').classList.remove('show');">Fermer</button>
        </div>
    </div>
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org, tous droits réservés</p>
            <p>API : 0.dev</p>
            <p id="clientUUID"></p>
            <p id="appVersion"></p>
            <button class="close-btn" onclick="document.getElementById('aboutPopup').classList.remove('show');">Fermer</button>
        </div>
    </div>
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n’est collectée ni enregistrée. Toutes les données que vous entrez dans les formulaires sont stockées uniquement sur votre appareil.</p>
            <button class="close-btn" onclick="document.getElementById('privacyPopup').classList.remove('show');">Fermer</button>
        </div>
    </div>
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez le nom à afficher sur les rapports PDF que vous générez.</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" onclick="saveUsername()">Enregistrer</button>
            <button class="close-btn" onclick="document.getElementById('usernamePopup').classList.remove('show');">Annuler</button>
        </div>
    </div>
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage"></h1>
        <p class="description">
            L'appli mobile vous permet d'accéder rapidement à vos outils interactifs, diagnostics, suivis et soutien émotionnel.
        </p>
        <div id="buttonsContainer">
            <a href="outils/" id="outilsBtn" class="cta"><span class="material-icons">business_center</span><span class="text">Outils interactifs</span></a>
            <a href="https://santementale.org/tests/index.dev.php" id="testsBtn" class="cta"><span class="material-icons">assignment</span><span class="text">Tests diagnostics</span></a>
            <a href="parametres.php" id="settingsBtn" class="cta settings-btn"><span class="material-icons">settings_applications</span><span class="text">Paramètres</span></a>
        </div>
        <p class="footer" style="margin-top: 3em; color: var(--footer-color); line-height: 1.8; font-size: 14px;">
            <span id="appVersionFooter"></span> • Accès anticipé<br>
            <a class="about-link" onclick="document.getElementById('aboutPopup').classList.add('show');">À propos</a> •
            <a class="about-link" onclick="document.getElementById('privacyPopup').classList.add('show');">Confidentialité</a><br>
            <span style="color: #161616;">©2025 SanteMentale.org</span>
        </p>
    </div>
    <div id="incompatible">
        <h2>Appareil non compatible</h2>
        <p>Veuillez visiter <a href="https://app.santementale.org">https://app.santementale.org</a> avec un appareil mobile compatible Android ou iOS.</p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isAndroid = /Android/i.test(navigator.userAgent);
            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
            const isMobile = isAndroid || isIOS;
            if (!isMobile) {
                document.getElementById('incompatible').style.display = 'flex';
                return;
            }

            const uuidKey = 'device_uuid';
            const usernameKey = 'username';
            const clientVersionKey = 'client_version';

            // Gérer la version : écraser client_version avec urlVersion si disponible
            const urlVersion = '<?php echo htmlspecialchars($clientVersion); ?>';
            let clientVersion = urlVersion || localStorage.getItem(clientVersionKey);
            if (urlVersion) {
                localStorage.setItem(clientVersionKey, urlVersion);
                clientVersion = urlVersion;
            }
            if (!clientVersion) {
                window.location.href = '/v1/errors/403.php';
                return;
            }

            // Afficher la version
            document.getElementById('appVersion').textContent = `App : ${clientVersion}`;
            document.getElementById('appVersionFooter').textContent = `App v${clientVersion} • Mod 0.11`;

            let deviceUUID = localStorage.getItem(uuidKey);
            let username = localStorage.getItem(usernameKey);
            const isNewDevice = !deviceUUID;

            if (isNewDevice) {
                deviceUUID = crypto.randomUUID();
                localStorage.setItem(uuidKey, deviceUUID);
                if (!username) {
                    localStorage.setItem(usernameKey, 'visiteur');
                    username = 'visiteur';
                }
                document.getElementById('privacyPopup').classList.add('show');
            }

            const welcomeMessage = document.getElementById('welcomeMessage');
            if (isNewDevice) {
                welcomeMessage.textContent = 'Bienvenue !';
                welcomeMessage.classList.add('new-device-message');
            } else {
                welcomeMessage.innerHTML = `Bonjour, <span class="username" onclick="document.getElementById('usernamePopup').classList.add('show');">${username}</span><span class="material-icons pencil-icon" style="font-size: 16px; color: var(--pencil-fill);">edit</span>`;
            }

            document.getElementById('clientUUID').textContent = `Client : ${deviceUUID}`;

            let deferredPrompt = null;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
            });

            const isInstalled = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone === true;
            const installBanner = document.getElementById('installBanner');
            const bannerIcon = document.getElementById('bannerIcon');
            const installPopup = document.getElementById('installPopup');
            const installArea = document.getElementById('installArea');
            const testsBtn = document.getElementById('testsBtn');

            function showInstallPopup() {
                installPopup.classList.add('show');
                installArea.innerHTML = '';
                if (isAndroid && deferredPrompt) {
                    const installBtn = document.createElement('button');
                    installBtn.classList.add('cta', 'install-btn');
                    installBtn.innerHTML = '<span class="material-icons">android</span><span class="text">Ajouter à l\'écran d\'accueil</span>';
                    installBtn.onclick = () => {
                        deferredPrompt.prompt();
                        deferredPrompt.userChoice.then((choice) => {
                            if (choice.outcome === 'accepted') {
                                installBanner.style.display = 'none';
                                testsBtn.classList.remove('disabled');
                            }
                            deferredPrompt = null;
                        });
                    };
                    installArea.appendChild(installBtn);
                } else if (isIOS) {
                    const instructions = document.createElement('p');
                    instructions.innerHTML = "Pour installer : Appuyez sur l'icône de partage en bas de l'écran, puis sélectionnez <strong>'Ajouter à l'écran d'accueil'</strong>.";
                    installArea.appendChild(instructions);
                } else {
                    const errorMsg = document.createElement('p');
                    errorMsg.textContent = 'Installation non supportée sur cet appareil.';
                    errorMsg.style.color = '#e63946';
                    errorMsg.style.fontWeight = '600';
                    installArea.appendChild(errorMsg);
                }
            }

            // Afficher la bannière d'installation uniquement pour 1.web et si non installé
            if (!isInstalled && clientVersion === '1.web') {
                if (isAndroid) {
                    bannerIcon.innerHTML = '<span class="material-icons banner-icon">android</span>';
                    installBanner.innerHTML = '<span id="bannerIcon" class="material-icons banner-icon">android</span> Installer l\'application pour accéder aux tests diagnostics';
                } else if (isIOS) {
                    bannerIcon.innerHTML = '<span id="bannerIcon" class="material-icons banner-icon">apple</span>';
                    installBanner.innerHTML = '<span id="bannerIcon" class="material-icons banner-icon">apple</span> Ajouter à l\'écran d\'accueil pour accéder aux tests diagnostics';
                }
                installBanner.style.display = 'flex';
                installBanner.addEventListener('click', showInstallPopup);
                testsBtn.classList.add('disabled');
                testsBtn.addEventListener('click', (e) => {
                    if (testsBtn.classList.contains('disabled')) {
                        e.preventDefault();
                        showInstallPopup();
                    }
                });
            } else {
                installBanner.style.display = 'none';
                testsBtn.classList.remove('disabled');
            }

            window.saveUsername = function() {
                const input = document.getElementById('usernameInput');
                const newUsername = input.value.trim() || 'visiteur';
                localStorage.setItem(usernameKey, newUsername);
                document.getElementById('welcomeMessage').innerHTML = `Bonjour, <span class="username" onclick="document.getElementById('usernamePopup').classList.add('show');">${newUsername}</span><span class="material-icons pencil-icon" style="font-size: 16px; color: var(--pencil-fill);">edit</span>`;
                document.getElementById('usernamePopup').classList.remove('show');
            };
        });
    </script>
</body>
</html>
