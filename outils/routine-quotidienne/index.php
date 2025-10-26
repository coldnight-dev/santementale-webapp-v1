<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Routines - SanteMentale.org</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.0/dist/confetti.browser.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Genos:wght@400;500;600;700&family=Sofia+Sans+Condensed:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css?v=0.12-alpha">
</head>
<body>
    <!-- Header Global -->
    <div class="bg-zinc-900 border-b border-zinc-800 header-sticky">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center gap-3">
            <a href="/v1/outils/?v=<?php echo htmlspecialchars($_GET['v'] ?? '1.web'); ?>" class="back-btn" style="flex-shrink:0;">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-3xl font-bold title-genos" style="flex:1;">Routines</h1>
            <button onclick="window.openPopup('helpModalPopup')" style="background:none;border:none;cursor:pointer;color:#3B82F6;padding:0;margin-right:8px;">
                <span class="material-symbols-outlined" style="font-size:22px;">help_outline</span>
            </button>
            <div>
                <span class="material-symbols-outlined title-icon-animated" style="color:#3B82F6;font-size:32px;">routine</span>
            </div>
        </div>
        
        <!-- Navigation Tabs directement sous le header -->
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex gap-1 justify-around" id="navTabs">
                <button data-view="today" class="nav-tab py-4 px-3 transition-all">
                    <span class="material-icons" style="font-size:30px;">today</span>
                </button>
                <button data-view="calendar" class="nav-tab py-4 px-3 transition-all">
                    <span class="material-icons" style="font-size:30px;">calendar_month</span>
                </button>
                <button data-view="stats" class="nav-tab py-4 px-3 transition-all">
                    <span class="material-icons" style="font-size:30px;">show_chart</span>
                </button>
                <button data-view="goals" class="nav-tab py-4 px-3 transition-all">
                    <span class="material-icons" style="font-size:30px;">local_fire_department</span>
                </button>
                <button data-view="manage" class="nav-tab py-4 px-3 transition-all">
                    <span class="material-icons" style="font-size:30px;">settings</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Barre XP Globale -->
    <div id="xpBarContainer" class="content-with-fixed-header"></div>

    <!-- Placeholder pour les vues -->
    <div class="max-w-2xl mx-auto px-4 py-6">
        <div id="viewContainer">
            <div class="loading-spinner">
                <svg class="animate-spin h-12 w-12 mx-auto text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-center text-zinc-400 mt-4">Chargement...</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div id="appFooter" style="margin-top:40px;padding-bottom:40px;text-align:center;opacity:0;transition:opacity 0.3s;">
        <p style="margin-top:30px;color:#555;font-size:14px;line-height:1.8;">
            <span style="color:#dc2626;font-weight:600;">Accès anticipé</span><br>
            App v<?php echo htmlspecialchars($_GET['v'] ?? '1.web'); ?> • Mod v0.12-alpha<br>
            <a onclick="window.openPopup('aboutPopup')" style="color:#0d47a1;cursor:pointer;">À propos</a> • 
            <a onclick="window.openPopup('privacyPopup')" style="color:#0d47a1;cursor:pointer;">Confidentialité</a><br/>
            <span style="color:#161616;">©2025 SanteMentale.org</span>
        </p>
    </div>

    <!-- Canvas pour partage -->
    <canvas id="shareCanvas" width="1080" height="1920" style="display:none;"></canvas>

    <!-- Popups Globales -->
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org</p>
            <p>API : 0.dev</p>
            <p id="appVersion"></p>
            <p id="clientUUID"></p>
            <button class="close-btn" onclick="window.closePopup('aboutPopup')">Fermer</button>
        </div>
    </div>

    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée. Tout est stocké sur votre appareil.</p>
            <button class="close-btn" onclick="window.closePopup('privacyPopup')">Fermer</button>
        </div>
    </div>

    <div id="helpModalPopup" class="popup help-modal-popup">
        <div class="popup-content">
            <div class="help-modal-title">Routines — v0.12-alpha</div>
            <div class="help-modal-buttons">
                <button class="help-modal-btn primary" onclick="window.showWhatsNew()">Quoi de neuf ?</button>
                <button class="help-modal-btn primary" onclick="window.startTutorial();window.closePopup('helpModalPopup');">Tutoriel</button>
                <button class="help-modal-btn secondary" onclick="window.closePopup('helpModalPopup')">Fermer</button>
            </div>
        </div>
    </div>

    <div id="whatsNewPopup" class="popup whats-new-popup">
        <div class="popup-content">
            <div class="whats-new-header">
                <h2 class="whats-new-title">✨ Quoi de neuf ?</h2>
                <button class="whats-new-close" id="whatsNewCloseBtn" style="display:none;" onclick="window.closePopup('whatsNewPopup')">✕</button>
            </div>
            <div id="whatsNewContent"></div>
            <div class="whats-new-buttons">
                <button class="help-modal-btn primary" onclick="window.closePopup('whatsNewPopup');window.startTutorial();">Tutoriel</button>
            </div>
        </div>
    </div>

    <div id="achievementNotification" class="achievement-notification">
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="material-symbols-outlined" style="font-size:40px;">emoji_events</span>
            <div>
                <div style="font-weight:bold;font-size:16px;">Achievement débloqué !</div>
                <div id="achievementText" style="font-size:14px;margin-top:4px;"></div>
            </div>
        </div>
    </div>

    <div id="tutorialOverlay" class="tutorial-overlay"></div>
    <div id="tutorialHighlight" class="tutorial-highlight"></div>
    <div id="tutorialTooltip" class="tutorial-tooltip"></div>

    <!-- Gestionnaire d'erreurs -->
    <script>
        window.consoleLog = [];
        (function() {
            const oldLog = console.log;
            const oldError = console.error;
            const oldWarn = console.warn;
            console.log = function() {
                window.consoleLog.push({type: 'log', msg: Array.from(arguments).join(' ')});
                oldLog.apply(console, arguments);
            };
            console.error = function() {
                window.consoleLog.push({type: 'error', msg: Array.from(arguments).join(' ')});
                oldError.apply(console, arguments);
                showError(Array.from(arguments).join(' '));
            };
            console.warn = function() {
                window.consoleLog.push({type: 'warn', msg: Array.from(arguments).join(' ')});
                oldWarn.apply(console, arguments);
            };
            window.onerror = function(msg, url, lineNo, columnNo, error) {
                const errorMsg = 'Erreur: ' + msg + ' à la ligne ' + lineNo + ':' + columnNo;
                window.consoleLog.push({type: 'error', msg: errorMsg});
                showError(errorMsg + '\n' + (error ? error.stack : ''));
                return false;
            };
            window.addEventListener('unhandledrejection', function(e) {
                const errorMsg = 'Promise rejetée: ' + e.reason;
                window.consoleLog.push({type: 'error', msg: errorMsg});
                showError(errorMsg);
            });
            function showError(msg) {
                const container = document.getElementById('viewContainer');
                if (container) {
                    let html = '<div style="padding:20px;color:#fff;background:#1a1a1a;font-family:monospace;font-size:12px;">';
                    html += '<h2 style="color:#ef4444;margin-bottom:15px;">🐛 Erreur JavaScript détectée</h2>';
                    html += '<div style="background:#000;padding:15px;border-radius:8px;border:2px solid #ef4444;margin-bottom:15px;white-space:pre-wrap;word-break:break-all;">' + msg + '</div>';
                    html += '<h3 style="color:#f59e0b;margin:15px 0 10px;">Console complète:</h3>';
                    html += '<div style="background:#000;padding:15px;border-radius:8px;max-height:400px;overflow-y:auto;">';
                    window.consoleLog.forEach(log => {
                        const color = log.type === 'error' ? '#ef4444' : log.type === 'warn' ? '#f59e0b' : '#10b981';
                        html += '<div style="color:' + color + ';margin-bottom:5px;">[' + log.type.toUpperCase() + '] ' + log.msg + '</div>';
                    });
                    html += '</div>';
                    html += '<button onclick="location.reload()" style="margin-top:20px;padding:10px 20px;background:#3b82f6;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:14px;font-weight:bold;">Recharger la page</button>';
                    html += '</div>';
                    container.innerHTML = html;
                }
            }
        })();
    </script>

    <!-- Chargement du gestionnaire de vues -->
    <script src="app.js?v=0.12-alpha"></script>
</body>
</html>
