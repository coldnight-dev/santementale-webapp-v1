<?php
// Validation centralisée de la version
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../version_check.php');
// $clientVersion est maintenant disponible et validé
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Outils interactifs</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php echo getJsConfig(); ?>
    <style>
        :root {
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
            --pill-bg: linear-gradient(90deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
            --pill-border: rgba(255,255,255,0.06);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100%; width: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
        }
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 10px;
            background: var(--gradient);
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
            box-shadow: var(--shadow);
            z-index: 1000;
            width: 45px;
            height: 45px;
        }
        .back-btn:hover {
            transform: translateX(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(13, 71, 161, 0.4);
        }
        .container {
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            min-height: 100%; text-align: center; padding: 20px;
            padding-bottom: 120px;
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(20px);
            animation: slideInUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) 0.15s forwards;
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
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 600;
            transform: translateY(20px);
            animation: slideInUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Quicksand', sans-serif;
        }
        .tools-list {
            width: 100%; max-width: 600px;
            display: flex; flex-direction: column;
            gap: 15px; margin-bottom: 15px;
            transform: translateY(30px);
            animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.6s forwards;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px);
        }
        .tools-list .tool-item:nth-child(1) { animation: fadeInUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) 0.75s forwards; }
        .tools-list .tool-item:nth-child(2) { animation: fadeInUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) 0.825s forwards; }
        .tools-list .tool-item:nth-child(3) { animation: fadeInUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) 0.9s forwards; }
        .tools-list .tool-item:nth-child(4) { animation: fadeInUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) 0.975s forwards; }
        .tools-list .tool-item:nth-child(5) { animation: fadeInUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) 1.05s forwards; }
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
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        .new-badge {
            background-color: #34D399;
            color: #000;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
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
            transition: all 0.225s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .tool-item:hover .tool-icon {
            color: var(--accent-secondary);
            transform: rotate(10deg) scale(1.1);
        }
        .goal-progress {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
        }
        .progress-bar {
            flex: 1;
            height: 8px;
            background: #333;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10B981, #34D399);
            border-radius: 4px;
            transition: width 0.225s ease;
            position: relative;
            overflow: hidden;
        }
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
            animation: shimmer 2s infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .progress-text {
            color: #888;
            font-size: 11px;
            white-space: nowrap;
        }
        .username {
            color: var(--username-color);
            cursor: pointer;
            transition: all 0.225s cubic-bezier(0.4, 0, 0.2, 1);
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
            transition: all 0.225s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        .pencil-icon:hover {
            fill: var(--accent-primary);
            transform: rotate(180deg);
        }

        /* === Styles pour l'affichage niveau / XP dans la carte Routine === */
        .routine-stats {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .routine-stats .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--pill-bg);
            border: 1px solid var(--pill-border);
            font-size: 13px;
            color: var(--text-secondary);
            box-shadow: var(--shadow-light);
            backdrop-filter: blur(6px);
        }
        .routine-stats .stat-icon {
            font-size: 14px;
            color: var(--accent-primary);
        }
        .routine-stats .stat-pill.small {
            padding: 4px 8px;
            font-size: 12px;
        }
        /* === fin === */

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
        }
        .popup.show {
            display: flex;
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
            transform: scale(1);
            opacity: 1;
            transition: transform 0.225s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.225s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .popup.show .popup-content {
            transform: scale(1);
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
            transition: all 0.225s cubic-bezier(0.4, 0, 0.2, 1);
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
            transition: all 0.225s cubic-bezier(0.4, 0, 0.2, 1);
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
            transition: all 0.225s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .username-input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.15);
            transform: scale(1.02);
        }
        .datetime {
            position: fixed;
            top: 10px;
            right: 10px;
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 500;
            z-index: 1000;
            text-align: right;
            line-height: 1.3;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .header-icon {
            font-size: 18px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.225s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .header-icon:hover {
            color: var(--accent-primary);
            transform: scale(1.1);
        }
        .colon {
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
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
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org, tous droits réservés</p>
            <p>API : 0.dev</p>
            <p id="appVersion"></p>
            <button class="close-btn" onclick="document.getElementById('aboutPopup').classList.remove('show');">Fermer</button>
        </div>
    </div>
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée ni enregistrée. Toutes les données que vous entrez dans les formulaires sont stockées uniquement sur votre appareil.</p>
            <button class="close-btn" onclick="document.getElementById('privacyPopup').classList.remove('show');">Fermer</button>
        </div>
    </div>
    <div id="syncInfoPopup" class="popup">
        <div class="popup-content">
            <p>Vos données sont stockées uniquement sur cet appareil. Pensez à vous connecter à santementale.org pour les conserver et y accéder depuis un autre appareil.</p>
            <button class="close-btn" onclick="document.getElementById('syncInfoPopup').classList.remove('show');">Compris !</button>
        </div>
    </div>
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez le nom à afficher</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" onclick="saveUsername()">Enregistrer</button>
            <button class="close-btn" onclick="document.getElementById('usernamePopup').classList.remove('show');">Annuler</button>
        </div>
    </div>
    <div class="datetime">
        <span class="material-icons header-icon" onclick="document.getElementById('syncInfoPopup').classList.add('show');">task</span>
        <span class="material-icons header-icon" onclick="document.getElementById('syncInfoPopup').classList.add('show');">cloud_off</span>
        <span id="datetime"></span>
    </div>
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage"></h1>
        <div class="tools-list">
            <a href="/v1/outils/routine-quotidienne.php" class="tool-item" id="routineTool">
                <h2><i class="fas fa-calendar-alt tool-icon"></i> Routine quotidienne <span class="new-badge">Nouveau</span></h2>
                <p>Planifiez et suivez vos activités quotiennes pour un équilibre optimal.</p>
                <div class="goal-progress" id="routineGoal"></div>
                <!-- Conteneur ajouté pour afficher niveau & XP (icone + pilles) -->
                <div class="routine-stats" id="routineStats" aria-hidden="false"></div>
            </a>
            <a href="/v1/outils/journal-des-emotions.php" class="tool-item" id="emotionTool">
                <h2><i class="fas fa-face-smile tool-icon"></i> Journal des émotions</h2>
                <p>Identifiez et explorez vos émotions pour mieux les comprendre.</p>
                <div class="goal-progress" id="emotionGoal"></div>
            </a>
            <a href="/v1/outils/journal-de-gratitude.php" class="tool-item" id="gratitudeTool">
                <h2><i class="fas fa-book tool-icon"></i> Journal de
gratitude</h2>
                <p>Enregistrez des pensées positives pour cultiver votre bien-être.</p>
                <div class="goal-progress" id="gratitudeGoal"></div>
            </a>
            <a href="/v1/outils/pyramide-des-besoins.php" class="tool-item" id="pyramideTool">
                <h2><i class="fas fa-layer-group tool-icon"></i> Pyramide des besoins</h2>
                <p>Évaluez vos besoins fondamentaux pour prioriser votre bien-être.</p>
            </a>
            <a href="/v1/outils/balance-decisionnelle.php" class="tool-item" id="balanceTool">
                <h2><i class="fas fa-scale-balanced tool-icon"></i> Balance décisionnelle</h2>
                <p>Pesez le pour et le contre pour prendre des décisions éclairées.</p>
            </a>
        </div>
        <p style="margin-top: 2.5em; color: var(--footer-color); line-height: 1.8;">
            <span id="footerVersion"></span> • Accès anticipé<br>
            <a class="about-link" onclick="document.getElementById('aboutPopup').classList.add('show');">À propos</a> • <a class="about-link" onclick="document.getElementById('privacyPopup').classList.add('show');">Confidentialité</a><br/>
            <span style="color: #161616;">©2025 SanteMentale.org</span><br>
        </p>
    </div>
    <script src="/v1/js/version-helper.js"></script>
    <script>
        function getGreeting() {
            const hour = new Date().getHours();
            if (hour >= 5 && hour < 12) return 'Bonjour';
            if (hour >= 12 && hour < 18) return 'Bon après-midi';
            if (hour >= 18 && hour < 22) return 'Bonsoir';
            return 'Bonne nuit';
        }
        document.addEventListener('DOMContentLoaded', () => {
            const usernameKey = 'username';
            let username = localStorage.getItem(usernameKey);
            if (!username) {
                localStorage.setItem(usernameKey, 'visiteur');
                username = 'visiteur';
            }
            const welcomeMessage = document.getElementById('welcomeMessage');
            const greeting = getGreeting();
            welcomeMessage.innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616" onclick="document.getElementById('usernamePopup').classList.add('show');"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>${greeting}, <span class="username" onclick="document.getElementById('usernamePopup').classList.add('show');">${username}</span>!`;
            // ===== AJOUT SYSTÈME DE VALIDATION CENTRALISÉ =====
            const clientVersion = VersionHelper.init({
                requireVersion: true,
                phpVersion: '<?php echo htmlspecialchars($clientVersion); ?>'
            });
            if (!clientVersion) return; // Redirection en cours
            // Récupérer les informations de version
            const versionInfo = VersionHelper.getVersionInfo();
            // ===== FIN AJOUT =====
            // Afficher la version
            document.getElementById('footerVersion').textContent = `v${clientVersion}-m0.12`;
            document.getElementById('appVersion').textContent = `App: ${clientVersion}`;
            // Définir le bouton de retour avec le paramètre v
            document.getElementById('backBtn').href = `/v1/?v=${encodeURIComponent(clientVersion)}`;
            // Ajouter le paramètre ?v= à tous les liens des outils
            const versionParam = `?v=${encodeURIComponent(clientVersion)}`;
            document.getElementById('routineTool').href = `/v1/outils/routine-quotidienne.php${versionParam}`;
            document.getElementById('emotionTool').href = `/v1/outils/journal-des-emotions.php${versionParam}`;
            document.getElementById('gratitudeTool').href = `/v1/outils/journal-de-gratitude.php${versionParam}`;
            document.getElementById('pyramideTool').href = `/v1/outils/pyramide-des-besoins.php${versionParam}`;
            document.getElementById('balanceTool').href = `/v1/outils/balance-decisionnelle.php${versionParam}`;
            function updateTime() {
                const now = new Date();
                const days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                const dayName = days[now.getDay()];
                const dayNum = now.getDate();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2,
'0');
                document.getElementById('datetime').innerHTML = `${dayName} ${dayNum}<br>${hours}<span class="colon">:</span>${minutes}`;
            }
            updateTime();
            setInterval(updateTime, 1000);
            const routineStreak = parseInt(localStorage.getItem('routineStreak')) || 0;
            const routineGoalDays = parseInt(localStorage.getItem('routineGoalDays')) || 7;
            const routineProgress = Math.min((routineStreak / routineGoalDays) * 100, 100);
            document.getElementById('routineGoal').innerHTML = localStorage.getItem('routineHistory') ? `
                <div class="progress-bar">
                    <div class="progress-fill" style="width: ${routineProgress}%"></div>
                </div>
                <span class="progress-text">${routineStreak}/${routineGoalDays}</span>
            ` : `
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
                <span class="progress-text">0/${routineGoalDays}</span>
            `;

            // === AJOUT : afficher niveau + XP dans la carte Routine ===
            try {
                const routineLevel = parseInt(localStorage.getItem('routineLevel')) || 0;
                const routineXP = parseInt(localStorage.getItem('routineXP')) || 0;
                const routineStatsEl = document.getElementById('routineStats');
                if (routineStatsEl) {
                    routineStatsEl.innerHTML = `
                        <div class="stat-pill small" title="Niveau de routine">
                            <i class="fas fa-award stat-icon" aria-hidden="true"></i>
                            <span> Niveau ${routineLevel}</span>
                        </div>
                        <div class="stat-pill small" title="XP accumulée">
                            <i class="fas fa-bolt stat-icon" aria-hidden="true"></i>
                            <span> ${routineXP} XP</span>
                        </div>
                    `;
                }
            } catch (e) {
                console.warn('Impossible d\'afficher niveau/XP routine:', e);
            }
            // === FIN AJOUT ===

            const emotionStreak = parseInt(localStorage.getItem('emotionStreak')) || 0;
            const emotionGoalDays = parseInt(localStorage.getItem('goalDays')) || 5;
            const emotionProgress = Math.min((emotionStreak / emotionGoalDays) * 100, 100);
            document.getElementById('emotionGoal').innerHTML = localStorage.getItem('emotionHistory') ? `
                <div class="progress-bar">
                    <div class="progress-fill" style="width: ${emotionProgress}%"></div>
                </div>
                <span class="progress-text">${emotionStreak}/${emotionGoalDays}</span>
            ` : `
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
                <span class="progress-text">0/${emotionGoalDays}</span>
            `;
            const gratitudeStreak = parseInt(localStorage.getItem('gratitudeStreak')) || 0;
            const gratitudeGoalDays = parseInt(localStorage.getItem('gratitudeGoalDays')) || 5;
            const gratitudeProgress = Math.min((gratitudeStreak / gratitudeGoalDays) * 100, 100);
            document.getElementById('gratitudeGoal').innerHTML = localStorage.getItem('gratitudeHistory') ? `
                <div class="progress-bar">
                    <div class="progress-fill" style="width: ${gratitudeProgress}%"></div>
                </div>
                <span class="progress-text">${gratitudeStreak}/${gratitudeGoalDays}</span>
            ` : `
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
                <span class="progress-text">0/${gratitudeGoalDays}</span>
            `;
        });
        window.saveUsername = function() {
            const input = document.getElementById('usernameInput');
            const newUsername = input.value.trim() || 'visiteur';
            localStorage.setItem('username', newUsername);
            const greeting = getGreeting();
            document.getElementById('welcomeMessage').innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616" onclick="document.getElementById('usernamePopup').classList.add('show');"><path
d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>${greeting}, <span class="username" onclick="document.getElementById('usernamePopup').classList.add('show');">${newUsername}</span>!`;
            document.getElementById('usernamePopup').classList.remove('show');
        };
    </script>
</body>
</html>
