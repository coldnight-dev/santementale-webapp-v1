<?php
// Validation centralisée de la version (optionnelle pour cette page)
// Permet l'accès même sans version si elle existe dans localStorage
$clientVersion = $_GET['v'] ?? null;
if ($clientVersion !== null) {
    require_once(__DIR__ . '/version_check.php');
}
// Si pas de version en URL, on laisse le JavaScript gérer
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Paramètres</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <link rel="manifest" href="/v1/manifest.json">
    <meta name="theme-color" content="#0d47a1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            --gradient-blue: linear-gradient(135deg, #0d47a1, #42a5f5);
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
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px;
            background: var(--gradient-blue);
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-light);
            z-index: 1000;
            width: 45px;
            height: 45px;
        }
        .back-btn:hover {
            transform: translateX(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(13, 71, 161, 0.4);
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
        #settingsList {
            transform: scale(0);
            animation: scaleUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 1.4s forwards;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 90%;
            max-width: 400px;
            gap: 16px;
        }
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 12px;
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            color: var(--text-secondary);
            font-size: clamp(14px, 4vw, 16px);
        }
        .setting-label {
            font-weight: 600;
            color: var(--text-primary);
        }
        .setting-value {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .setting-value span.username, .setting-value span.email {
            color: var(--username-color);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .setting-value span.username:hover, .setting-value span.email:hover {
            color: var(--accent-secondary);
            transform: scale(1.05);
        }
        .theme-select {
            padding: 8px;
            border: 1px solid var(--text-secondary);
            border-radius: var(--border-radius);
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: clamp(14px, 4vw, 16px);
            font-family: 'Quicksand', Arial, sans-serif;
            cursor: not-allowed;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-select:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px rgba(13, 71, 161, 0.2);
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
        .username, .email {
            color: var(--username-color);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .username:hover, .email:hover {
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
        }
        .pencil-icon:hover {
            color: var(--accent-secondary);
            transform: rotate(180deg);
        }
        .username-input, .email-input {
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
        .username-input:focus, .email-input:focus {
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
    <a href="/v1/" class="back-btn" id="backBtn"><i class="fas fa-arrow-left"></i></a>
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez votre nom d'utilisateur.</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" onclick="saveUsername()">Enregistrer</button>
            <button class="close-btn" onclick="document.getElementById('usernamePopup').classList.remove('show');">Annuler</button>
        </div>
    </div>
    <div id="emailPopup" class="popup">
        <div class="popup-content">
            <p>Entrez votre adresse e-mail.</p>
            <input type="email" id="emailInput" class="email-input" placeholder="Votre e-mail">
            <button class="close-btn" onclick="saveEmail()">Enregistrer</button>
            <button class="close-btn" onclick="document.getElementById('emailPopup').classList.remove('show');">Annuler</button>
        </div>
    </div>
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1>Paramètres</h1>
        <div id="settingsList">
            <div class="setting-item">
                <span class="setting-label">Nom d'utilisateur</span>
                <span class="setting-value">
                    <span id="displayUsername" class="username" onclick="document.getElementById('usernamePopup').classList.add('show');"></span>
                    <span class="material-icons pencil-icon" style="font-size: 16px; color: var(--pencil-fill);">edit</span>
                </span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Adresse e-mail</span>
                <span class="setting-value">
                    <span id="displayEmail" class="email" onclick="document.getElementById('emailPopup').classList.add('show');"></span>
                    <span class="material-icons pencil-icon" style="font-size: 16px; color: var(--pencil-fill);">edit</span>
                </span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Thème</span>
                <select id="themeSelect" class="theme-select" disabled>
                    <!-- Options populated dynamically -->
                </select>
            </div>
            <div class="setting-item">
                <span class="setting-label">Stockage utilisé</span>
                <span id="displayStorageSize"></span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Version du client</span>
                <span id="displayClientVersion"></span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Version de l'application</span>
                <span>1.0a</span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Version de l'API</span>
                <span>0.dev</span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Identifiant d'appareil</span>
                <span id="displayDeviceUUID"></span>
            </div>
        </div>
        <p class="footer" style="margin-top: 16px; color: var(--footer-color); line-height: 1.8; font-size: 14px;">
            <span id="footerVersion"></span> • Accès anticipé<br>
            <span style="color: #161616;">©2025 SanteMentale.org</span>
        </p>
    </div>
    <div id="incompatible">
        <h2>Appareil non compatible</h2>
        <p>Veuillez visiter <a href="https://app.santementale.org">https://app.santementale.org</a> avec un appareil mobile compatible Android ou iOS.</p>
    </div>
    <script>
        // Theme options defined here for easy editing
        const themeOptions = [
            { value: 'system', label: 'Système' },
            { value: 'light', label: 'Clair' },
            { value: 'dark', label: 'Foncé' }
        ];
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
            const emailKey = 'email';
            const clientVersionKey = 'client_version';
            const themeKey = 'theme';
            
            // Set default theme to 'system' if not defined
            if (!localStorage.getItem(themeKey)) {
                localStorage.setItem(themeKey, 'system');
            }
            let deviceUUID = localStorage.getItem(uuidKey);
            let username = localStorage.getItem(usernameKey) || 'visiteur';
            let email = localStorage.getItem(emailKey) || '';
            let theme = localStorage.getItem(themeKey) || 'system';
            
            // Récupérer la version depuis localStorage ou URL
            const urlVersion = '<?php echo htmlspecialchars($clientVersion ?? ''); ?>';
            let clientVersion = localStorage.getItem(clientVersionKey);
            
            // Si version fournie en URL, elle prend la priorité
            if (urlVersion) {
                clientVersion = urlVersion;
                localStorage.setItem(clientVersionKey, urlVersion);
            } else if (!clientVersion) {
                // Si aucune version en URL ni localStorage, rediriger
                window.location.href = '/v1/errors/403.php?error=no-version';
                return;
            }
            
            // Définir le bouton de retour avec le paramètre v
            document.getElementById('backBtn').href = `/v1/?v=${encodeURIComponent(clientVersion)}`;
            
            // Calculate localStorage size
            let totalSize = 0;
            for (let i = 0; i < localStorage.length; i++) {
                let key = localStorage.key(i);
                let value = localStorage.getItem(key);
                totalSize += ((key.length + value.length) * 2);
            }
            
            // Populate settings
            document.getElementById('displayUsername').textContent = username;
            document.getElementById('displayEmail').textContent = email || 'Non défini';
            document.getElementById('displayDeviceUUID').textContent = deviceUUID || 'Non défini';
            document.getElementById('displayStorageSize').textContent = `${(totalSize / 1024).toFixed(2)} KB`;
            document.getElementById('displayClientVersion').textContent = clientVersion || 'Non défini';
            document.getElementById('footerVersion').textContent = `v${clientVersion}-p0.10`;
            
            // Populate theme dropdown
            const themeSelect = document.getElementById('themeSelect');
            themeOptions.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.value;
                opt.textContent = option.label;
                if (option.value === theme) {
                    opt.selected = true;
                }
                themeSelect.appendChild(opt);
            });
            
            window.saveUsername = function() {
                const input = document.getElementById('usernameInput');
                const newUsername = input.value.trim() || 'visiteur';
                localStorage.setItem(usernameKey, newUsername);
                document.getElementById('displayUsername').textContent = newUsername;
                document.getElementById('usernamePopup').classList.remove('show');
            };
            
            window.saveEmail = function() {
                const input = document.getElementById('emailInput');
                const newEmail = input.value.trim();
                // Email validation regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (newEmail && !emailRegex.test(newEmail)) {
                    alert("Veuillez entrer une adresse e-mail valide.");
                    return;
                }
                localStorage.setItem(emailKey, newEmail);
                document.getElementById('displayEmail').textContent = newEmail || 'Non défini';
                document.getElementById('emailPopup').classList.remove('show');
            };
            
            document.getElementById('usernameInput').value = username;
            document.getElementById('emailInput').value = email;
        });
    </script>
</body>
</html>
