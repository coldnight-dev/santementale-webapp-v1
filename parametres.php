<?php
$clientVersion = $_GET['v'] ?? null;
if ($clientVersion !== null) {
    require_once(__DIR__ . '/version_check.php');
}
// ✅ HANDLER PHP UNIQUEMENT
require_once(__DIR__ . '/components/save-handler.php');
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
        /* TOUS LES CSS (identiques à avant - minifiés pour brevité) */
        :root {
            --bg-primary: #0a0a0a; --bg-secondary: #1e1e1e; --text-primary: #ffffff;
            --text-secondary: #b0b0b0; --text-muted: #555555; --accent-primary: #0d47a1;
            --accent-secondary: #42a5f5; --gradient-blue: linear-gradient(135deg, #0d47a1, #42a5f5);
            --gradient-red: linear-gradient(90deg, #e63946, #d00000); --border-radius: 12px;
            --shadow: 0 6px 24px rgba(0, 0, 0, 0.4); --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.2);
            --popup-bg: #ffffff; --popup-text: #1a1a1a; --btn-bg: #e0e0e0;
            --username-color: #e0e0e0; --footer-color: #555555; --pencil-fill: #333333;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; width: 100%; font-family: 'Quicksand', Arial, sans-serif;
            background-color: var(--bg-primary); color: var(--text-primary); scroll-behavior: smooth; overflow-x: hidden; }
        .back-btn { position: fixed; top: 20px; left: 20px; padding: 10px; background: var(--gradient-blue);
            color: #fff; border: none; border-radius: 50%; cursor: pointer; display: inline-flex; align-items: center;
            justify-content: center; text-decoration: none; font-weight: 500; transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-light); z-index: 1000; width: 45px; height: 45px; animation: bounceIn 0.6s ease-in-out forwards; }
        .back-btn:active { transform: scale(0.95); }
        @keyframes bounceIn { 0% { transform: scale(0); opacity: 0; } 50% { transform: scale(1.2); opacity: 0.7; } 100% { transform: scale(1); opacity: 1; } }
        .container { display: flex; flex-direction: column; justify-content: center; align-items: center;
            min-height: 100vh; text-align: center; padding: 20px; }
        .logo { width: 30vw; max-width: 160px; height: auto; margin-bottom: 32px;
            filter: drop-shadow(0 4px 12px rgba(13, 71, 161, 0.3)); transform: translateY(50px) scale(0.8);
            animation: slideInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards, pulse 2.5s infinite ease-in-out; }
        @keyframes slideInUp { to { transform: translateY(0) scale(1); } }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        h1 { font-size: clamp(20px, 6vw, 28px); margin-bottom: 20px; background: var(--gradient-blue);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 600;
            display: flex; align-items: center; gap: 8px; transform: translateY(-50px); opacity: 0;
            animation: slideInDown 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards; }
        @keyframes slideInDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        #settingsList { display: flex !important; flex-direction: column; align-items: flex-start; width: 90%; max-width: 400px; gap: 16px; opacity: 1 !important; }
        .setting-item { display: flex !important; justify-content: space-between; align-items: center; width: 100%; padding: 12px;
            background: var(--bg-secondary); border-radius: var(--border-radius); color: var(--text-secondary); font-size: clamp(14px, 4vw, 16px);
            opacity: 0; transform: translateX(100%); }
        .setting-item:nth-child(even) { transform: translateX(-100%); }
        .setting-item:nth-child(1) { animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards; }
        .setting-item:nth-child(2) { animation: slideInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.6s forwards; }
        .setting-item:nth-child(3) { animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.7s forwards; }
        .setting-item:nth-child(4) { animation: slideInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.8s forwards; }
        .setting-item:nth-child(5) { animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.9s forwards; }
        .setting-item:nth-child(6) { animation: slideInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1) 1.0s forwards; }
        .setting-item:nth-child(7) { animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1) 1.1s forwards; }
        .setting-item:nth-child(8) { animation: slideInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1) 1.2s forwards; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideInLeft { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .setting-label { font-weight: 600; color: var(--text-primary); }
        .setting-value { display: flex; align-items: center; gap: 8px; }
        .setting-value span.username, .setting-value span.email { color: var(--username-color); cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .setting-value span.username:active, .setting-value span.email:active { transform: scale(0.95); }
        .theme-select { padding: 8px; border: 1px solid var(--text-secondary); border-radius: var(--border-radius);
            background: var(--bg-secondary); color: var(--text-primary); font-size: clamp(14px, 4vw, 16px);
            font-family: 'Quicksand', Arial, sans-serif; cursor: pointer; transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .theme-select:focus { outline: none; border-color: var(--accent-primary); box-shadow: 0 0 0 4px rgba(13, 71, 161, 0.2); transform: scale(1.02); }
        .footer { margin-top: 16px; color: var(--footer-color); line-height: 1.8; font-size: 14px;
            opacity: 0; animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) 2.5s forwards; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .popup { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6);
            z-index: 2000; display: none !important; align-items: center; justify-content: center;
            backdrop-filter: blur(8px); opacity: 0; transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .popup.show { display: flex !important; opacity: 1; }
        .popup-content { background: var(--popup-bg); color: var(--popup-text); width: 90%; max-width: 420px; padding: 28px;
            border-radius: var(--border-radius); text-align: center; box-shadow: var(--shadow); transform: scale(0.7); opacity: 0;
            animation: popIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        .popup.show .popup-content { transform: scale(1); opacity: 1; }
        @keyframes popIn { from { transform: scale(0.7); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .popup-content p { font-size: 16px; margin-bottom: 20px; line-height: 1.6; color: var(--popup-text); }
        .close-btn { padding: 12px 24px; background: var(--btn-bg); border: none; border-radius: var(--border-radius);
            cursor: pointer; color: var(--popup-text); font-weight: 600; transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); margin: 0 6px; }
        .close-btn:active { transform: scale(0.95); }
        .username, .email { color: var(--username-color); cursor: pointer; transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .username:active, .email:active { transform: scale(0.95); }
        .pencil-icon { width: 16px; height: 16px; margin-left: 8px; vertical-align: middle; cursor: pointer; }
        .username-input, .email-input { padding: 12px; width: 85%; margin: 16px 0; border: 1px solid var(--text-secondary);
            border-radius: var(--border-radius); font-size: 16px; background: #f5f5f5; color: var(--popup-text);
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .username-input:focus, .email-input:focus { outline: none; border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px rgba(13, 71, 161, 0.2); transform: scale(1.02); }
        #incompatible { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: var(--popup-bg); z-index: 3000; justify-content: center; align-items: center;
            flex-direction: column; color: var(--popup-text); text-align: center; padding: 20px;
            animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        #incompatible h2 { font-size: clamp(18px, 5vw, 22px); margin-bottom: 16px; }
        #incompatible p { font-size: clamp(14px, 4vw, 16px); margin-bottom: 16px; }
        #incompatible a { color: var(--accent-primary); text-decoration: none; font-weight: 600;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        #incompatible a:active { transform: scale(0.95); }
        @media (orientation: landscape) {
            body { transform: rotate(90deg); transform-origin: left top; width: 100vh; height: 100vw;
                overflow-x: hidden; position: absolute; top: 100%; left: 0; transition: transform 0.375s cubic-bezier(0.4, 0, 0.2, 1); }
        }
    </style>
</head>
<body>
    <?php require_once(__DIR__ . '/components/save-cloud.php'); ?>
    <a href="/v1/" class="back-btn" id="backBtn"><i class="fas fa-arrow-left"></i></a>
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez votre nom d'utilisateur.</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" id="saveUsernameBtn">Enregistrer</button>
            <button class="close-btn" id="cancelUsernameBtn">Annuler</button>
        </div>
    </div>
    <div id="emailPopup" class="popup">
        <div class="popup-content">
            <p>Entrez votre adresse e-mail.</p>
            <input type="email" id="emailInput" class="email-input" placeholder="Votre e-mail">
            <button class="close-btn" id="saveEmailBtn">Enregistrer</button>
            <button class="close-btn" id="cancelEmailBtn">Annuler</button>
        </div>
    </div>
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1>Paramètres</h1>
        <div id="settingsList">
            <div class="setting-item">
                <span class="setting-label">Nom d'utilisateur</span>
                <span class="setting-value">
                    <span id="displayUsername" class="username">Chargement...</span>
                    <span class="material-icons pencil-icon" style="font-size: 16px; color: var(--pencil-fill);">edit</span>
                </span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Adresse e-mail</span>
                <span class="setting-value">
                    <span id="displayEmail" class="email">Chargement...</span>
                    <span class="material-icons pencil-icon" style="font-size: 16px; color: var(--pencil-fill);">edit</span>
                </span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Thème</span>
                <select id="themeSelect" class="theme-select"></select>
            </div>
            <div class="setting-item">
                <span class="setting-label">Stockage utilisé</span>
                <span id="displayStorageSize">Chargement...</span>
            </div>
            <div class="setting-item">
                <span class="setting-label">Version du client</span>
                <span id="displayClientVersion">Chargement...</span>
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
                <span id="displayDeviceUUID">Chargement...</span>
            </div>
        </div>
        <p class="footer">
            <span id="footerVersion">Chargement...</span> • Accès anticipé<br>
            <span style="color: #161616;">©2025 SanteMentale.org</span>
        </p>
    </div>
    <div id="incompatible">
        <h2>Appareil non compatible</h2>
        <p>Veuillez visiter <a href="https://app.santementale.org">https://app.santementale.org</a> avec un appareil mobile compatible Android ou iOS.</p>
    </div>
    <script>
        // ✅ JS SAUVEGARDE AUTONOME (fonctionne TOUT SEUL)
        async function saveLocalStorage() {
            const saveStatus = document.getElementById('saveStatus');
            if (!saveStatus) return;
            try {
                console.log('Début sauvegarde localStorage...');
                const localStorageData = {};
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    localStorageData[key] = localStorage.getItem(key);
                }
                const formData = new FormData();
                formData.append('device_uuid', localStorage.getItem('device_uuid') || 'unknown');
                formData.append('data', JSON.stringify(localStorageData));
                
                const response = await fetch(window.location.href, { method: 'POST', body: formData });
                console.log('Réponse fetch reçue, status:', response.status, 'ok:', response.ok);
                const data = await response.json();
                console.log('Réponse JSON:', data);
                
                if (data.status === 'success') {
                    setTimeout(() => {
                        saveStatus.textContent = 'cloud_done';
                        saveStatus.style.color = '#4CAF50';
                        console.log('✅ Sauvegarde réussie - Icône VERTE après 250ms !');
                    }, 250);
                } else {
                    saveStatus.style.color = '#f44336';
                    console.error('❌ Échec:', data.message);
                }
            } catch (error) {
                console.error('Erreur détaillée sauvegarde localStorage:', error.message);
                saveStatus.style.color = '#f44336';
            }
        }

        // ✅ PARAMÈTRES (identique à avant)
        const themeOptions = [
            { value: 'system', label: 'Système' },
            { value: 'light', label: 'Clair [Bientôt]' },
            { value: 'dark', label: 'Foncé' }
        ];
        function openPopup(popupId) {
            const popup = document.getElementById(popupId);
            if (popup) popup.classList.add('show');
        }
        function closePopup(popupId) {
            const popup = document.getElementById(popupId);
            if (popup) popup.classList.remove('show');
        }
        document.addEventListener('DOMContentLoaded', async () => {
            // ✅ SAUVEGARDE AUTO (100ms après DOM)
            setTimeout(saveLocalStorage, 100);
            
            // Reste identique...
            const isAndroid = /Android/i.test(navigator.userAgent);
            const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
            if (!isAndroid && !isIOS) document.getElementById('incompatible').style.display = 'flex';

            const uuidKey = 'device_uuid'; const usernameKey = 'username'; const emailKey = 'email';
            const clientVersionKey = 'client_version'; const themeKey = 'theme';
            if (!localStorage.getItem(themeKey)) localStorage.setItem(themeKey, 'system');

            const deviceUUID = localStorage.getItem(uuidKey) || 'Non défini';
            const username = localStorage.getItem(usernameKey) || 'visiteur';
            const email = localStorage.getItem(emailKey) || 'Non défini';
            const theme = localStorage.getItem(themeKey) || 'system';
            const urlVersion = '<?php echo htmlspecialchars($clientVersion ?? ''); ?>';
            let clientVersion = localStorage.getItem(clientVersionKey) || 'unknown';
            if (urlVersion) { clientVersion = urlVersion; localStorage.setItem(clientVersionKey, urlVersion); }

            document.getElementById('backBtn').href = `/v1/?v=${encodeURIComponent(clientVersion)}`;

            let totalSize = 0;
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i); const value = localStorage.getItem(key);
                totalSize += (key.length + value.length) * 2;
            }

            document.getElementById('displayUsername').textContent = username;
            document.getElementById('displayEmail').textContent = email;
            document.getElementById('displayDeviceUUID').textContent = deviceUUID;
            document.getElementById('displayStorageSize').textContent = `${(totalSize / 1024).toFixed(2)} KB`;
            document.getElementById('displayClientVersion').textContent = clientVersion;
            document.getElementById('footerVersion').textContent = `App v${clientVersion} • Mod v0.10`;
            document.getElementById('usernameInput').value = username;
            document.getElementById('emailInput').value = email;

            const themeSelect = document.getElementById('themeSelect');
            themeOptions.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.value; opt.textContent = option.label;
                if (option.value === theme) opt.selected = true;
                if (option.value === 'light') opt.disabled = true;
                themeSelect.appendChild(opt);
            });

            ['username', 'email'].forEach(type => {
                const display = document.getElementById(`display${type.charAt(0).toUpperCase() + type.slice(1)}`);
                const popupId = type + 'Popup';
                if (display) {
                    display.addEventListener('click', () => openPopup(popupId));
                    display.addEventListener('touchstart', e => { e.preventDefault(); openPopup(popupId); });
                }
                const pencil = document.querySelector(`#display${type.charAt(0).toUpperCase() + type.slice(1)} + .pencil-icon`);
                if (pencil) {
                    pencil.addEventListener('click', () => openPopup(popupId));
                    pencil.addEventListener('touchstart', e => { e.preventDefault(); openPopup(popupId); });
                }
            });

            document.getElementById('saveUsernameBtn').addEventListener('click', () => {
                const newUsername = document.getElementById('usernameInput').value.trim() || 'visiteur';
                localStorage.setItem('username', newUsername);
                document.getElementById('displayUsername').textContent = newUsername;
                closePopup('usernamePopup'); saveLocalStorage();
            });
            document.getElementById('cancelUsernameBtn').addEventListener('click', () => closePopup('usernamePopup'));
            document.getElementById('saveEmailBtn').addEventListener('click', () => {
                const newEmail = document.getElementById('emailInput').value.trim();
                if (newEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(newEmail)) return alert("E-mail invalide.");
                localStorage.setItem('email', newEmail);
                document.getElementById('displayEmail').textContent = newEmail || 'Non défini';
                closePopup('emailPopup'); saveLocalStorage();
            });
            document.getElementById('cancelEmailBtn').addEventListener('click', () => closePopup('emailPopup'));
        });
    </script>
</body>
</html>
