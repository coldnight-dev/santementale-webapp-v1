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
    <title>Vidéos récompensées • SanteMentale.org</title>
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
            --gradient-purple: linear-gradient(135deg, #9b59b6, #8e44ad);
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
            background: var(--gradient-purple);
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
        .icon-video {
            font-size: 48px;
            color: #9b59b6;
            margin-bottom: 16px;
            animation: float 3s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .provider-buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 20px;
        }
        .provider-btn {
            padding: 16px 20px;
            font-size: clamp(15px, 4vw, 17px);
            background: var(--gradient-purple);
            color: var(--text-primary);
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-light);
            font-weight: 700;
        }
        .provider-btn:hover:not(.disabled) {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 28px rgba(155, 89, 182, 0.5);
        }
        .provider-btn.disabled {
            background: linear-gradient(135deg, #555555, #444444);
            cursor: not-allowed;
            opacity: 0.5;
        }
        .provider-btn .material-icons {
            font-size: 24px;
        }
        .tickets-info {
            background: rgba(155, 89, 182, 0.1);
            border: 2px solid rgba(155, 89, 182, 0.3);
            border-radius: var(--border-radius);
            padding: 16px;
            text-align: center;
        }
        .tickets-count {
            font-size: 28px;
            font-weight: 700;
            color: #9b59b6;
            line-height: 1;
        }
        .tickets-label {
            font-size: 12px;
            color: var(--text-secondary);
        }
        .history-section {
            margin-top: 20px;
            max-height: 200px;
            overflow-y: auto;
            text-align: left;
        }
        .history-item {
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .history-date {
            font-weight: 600;
            color: var(--text-primary);
        }
        .history-section {
            margin-top: 20px;
            max-height: 200px;
            overflow-y: auto;
            text-align: left;
        }
        .history-item {
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .history-date {
            font-weight: 600;
            color: var(--text-primary);
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
        .highlight {
            color: #9b59b6;
            font-weight: 700;
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
        <h1>Vidéos récompensées</h1>
        <div class="content-box">
            <span class="material-icons icon-video">live_tv</span>
            <p>
                <strong>Gagnez des tickets</strong> en visionnant des vidéos courtes !
            </p>
            <p>
                Chaque vidéo terminée vous rapporte <span class="highlight">+1 ticket</span>. Plus vous accumulez de tickets, plus vous avez de chances de gagner des <strong>cadeaux</strong> lors de nos concours.
            </p>
            <p style="font-weight: 600; color: #9b59b6; margin-top: 16px;">
                1 ticket = 1 chance de gagner ! 🎁
            </p>
            <div style="margin: 20px auto; max-width: 90%;">
                <div style="width: 100%; background: rgba(255, 255, 255, 0.1); border-radius: 10px; height: 20px; overflow: hidden; position: relative;">
                    <div id="progressBar" style="height: 100%; background: linear-gradient(90deg, #9b59b6, #e056fd); transition: width 0.5s ease; width: 0%; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); animation: shimmer 2s infinite;"></div>
                    </div>
                </div>
                <div id="progressLabel" style="text-align: center; font-size: 14px; color: var(--text-secondary); margin-top: 8px;">0 / 100</div>
            </div>
            <div class="provider-buttons">
                <button class="provider-btn disabled" disabled>
                    <span class="material-icons">queue_play_next</span>
                    <span style="flex: 1; text-align: center;">Regarder une vidéo (AdGate)</span>
                </button>
                <button class="provider-btn" onclick="watchVideo('torox')">
                    <span class="material-icons">queue_play_next</span>
                    <span style="flex: 1; text-align: center;">Regarder une vidéo (Torox)</span>
                </button>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; margin-top: 24px;">
                <div class="tickets-info">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="material-icons" style="color: #9b59b6; font-size: 28px;">confirmation_number</span>
                        <div style="text-align: left; flex: 1;">
                            <div class="tickets-count" id="todayTickets">0</div>
                            <div class="tickets-label" style="margin: 0;">aujourd'hui</div>
                        </div>
                    </div>
                </div>
                <div class="tickets-info">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="material-icons" style="color: #9b59b6; font-size: 28px;">calendar_today</span>
                        <div style="text-align: left; flex: 1;">
                            <div class="tickets-count" id="weekTickets">0</div>
                            <div class="tickets-label" style="margin: 0;">cette semaine</div>
                        </div>
                    </div>
                </div>
                <div class="tickets-info">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="material-icons" style="color: #9b59b6; font-size: 28px;">workspace_premium</span>
                        <div style="text-align: left; flex: 1;">
                            <div class="tickets-count" id="totalTickets">0</div>
                            <div class="tickets-label" style="margin: 0;">au total</div>
                        </div>
                    </div>
                </div>
            </div>
            <p style="margin-top: 20px; font-size: 15px; color: var(--text-secondary); font-style: italic;">
                Merci de soutenir cette application gratuitement ! 💜
            </p>
        </div>
        <div class="content-box" style="background: rgba(155, 89, 182, 0.1); border: 2px solid rgba(155, 89, 182, 0.3);">
            <span class="material-icons" style="font-size: 36px; color: #9b59b6; margin-bottom: 12px;">info</span>
            <p style="margin-bottom: 0;">
                <strong>Pourquoi regarder des vidéos ?</strong>
            </p>
            <p style="font-size: clamp(14px, 3.5vw, 16px);">
                En visionnant ces vidéos, vous nous aidez à maintenir cette application <strong>gratuite et sans publicité</strong>. En remerciement, vous gagnez des <span class="highlight">tickets de participation</span> pour nos concours réguliers avec des <strong>cadeaux à gagner</strong> ! 🎁
            </p>
        </div>
        <button class="back-btn" onclick="window.history.back();">
            <span class="material-icons">arrow_back</span>
            Retour
        </button>
        <p class="footer" style="margin-top: 3em; color: var(--footer-color); line-height: 1.8; font-size: 14px;">
            <span id="appVersionFooter">X-0.251117</span><br>
            <a class="about-link" onclick="document.getElementById('aboutPopup').classList.add('show');">À propos</a> •
            <a class="about-link" onclick="document.getElementById('privacyPopup').classList.add('show');">Confidentialité</a><br>
            <span style="color: #161616;">©2025 SanteMentale.org</span>
        </p>
    </div>
    <script>
        const STORAGE_KEY = 'videoRewards';
        
        function getTodayDateKey() {
            const now = new Date();
            return now.toISOString().split('T')[0];
        }
        
        function getRewardsData() {
            const data = localStorage.getItem(STORAGE_KEY);
            return data ? JSON.parse(data) : {};
        }
        
        function saveRewardsData(data) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        }
        
        function getTodayTickets() {
            const data = getRewardsData();
            const today = getTodayDateKey();
            return data[today] || 0;
        }
        
        function getWeekTickets() {
            const data = getRewardsData();
            const now = new Date();
            const dayOfWeek = now.getDay();
            const monday = new Date(now);
            monday.setDate(now.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));
            monday.setHours(0, 0, 0, 0);
            
            let total = 0;
            for (const dateStr in data) {
                const date = new Date(dateStr);
                if (date >= monday) {
                    total += data[dateStr];
                }
            }
            return total;
        }
        
        function getTotalTickets() {
            const data = getRewardsData();
            let total = 0;
            for (const dateStr in data) {
                total += data[dateStr];
            }
            return total;
        }
        
        function addTicket() {
            const data = getRewardsData();
            const today = getTodayDateKey();
            data[today] = (data[today] || 0) + 1;
            saveRewardsData(data);
            updateDisplay();
        }
        
        function updateDisplay() {
            const todayCount = getTodayTickets();
            const weekCount = getWeekTickets();
            const totalCount = getTotalTickets();
            
            document.getElementById('todayTickets').textContent = todayCount;
            document.getElementById('weekTickets').textContent = weekCount;
            document.getElementById('totalTickets').textContent = totalCount;
            
            const progressPercent = Math.min(totalCount, 100);
            const progressBar = document.getElementById('progressBar');
            const progressLabel = document.getElementById('progressLabel');
            
            progressBar.style.width = progressPercent + '%';
            progressLabel.textContent = totalCount >= 100 ? '100 / 100' : totalCount + ' / 100';
        }
        
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            if (dateStr === getTodayDateKey()) return 'Aujourd\'hui';
            if (dateStr === yesterday.toISOString().split('T')[0]) return 'Hier';
            
            return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
        }
        
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            if (dateStr === getTodayDateKey()) return 'Aujourd\'hui';
            if (dateStr === yesterday.toISOString().split('T')[0]) return 'Hier';
            
            return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
        }
        
        function watchVideo(provider) {
            // Placeholder pour l'intégration future des fournisseurs
            if (provider === 'adgate') {
                alert('Intégration AdGate Media en cours de développement.');
                // TODO: Intégrer AdGate Media SDK/API
            } else if (provider === 'torox') {
                alert('Intégration Torox en cours de développement.');
                // TODO: Intégrer Torox SDK/API
            }
            
            // Simulation: ajouter un ticket après visionnage
            // Dans la version finale, ceci sera déclenché par le callback du fournisseur
            // addTicket();
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            updateDisplay();
        });
    </script>
</body>
</html>
