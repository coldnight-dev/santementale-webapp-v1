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
    <style>
        body { background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding-bottom: 40px; }
        .popup { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; display: none; align-items: center; justify-content: center; }
        .popup-content { background: white; color: black; width: 90%; max-width: 500px; padding: 20px; border-radius: 10px; text-align: center; max-height: 80vh; overflow-y: auto; }
        .popup-content p { font-size: 16px; margin-bottom: 10px; }
        .close-btn { padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .task-input { padding: 8px; width: 100%; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        .calendar-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 600; }
        .calendar-day:hover { transform: scale(1.1); }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%); color: white; border-radius: 8px; text-decoration: none; transition: all 0.2s; }
        .back-btn:hover { transform: translateX(-3px); box-shadow: 0 4px 12px rgba(13, 71, 161, 0.4); }
        .icon-option { padding: 10px; border: 2px solid #ccc; border-radius: 5px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .icon-option:hover { border-color: #3B82F6; background: #f0f9ff; }
        .icon-option.selected { border-color: #3B82F6; background: #dbeafe; }
        .title-genos { font-family: 'Genos', sans-serif; }
        .task-name-sofia { font-family: 'Sofia Sans Condensed', sans-serif; }
        .streak-pulse { animation: pulse-intense 1.5s ease-in-out infinite; }
        @keyframes pulse-intense {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }
        .task-progress-bar { position: relative; overflow: hidden; border-radius: 12px; }
        .task-progress-fill { position: relative; overflow: hidden; border-radius: 12px; }
        .task-progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
            animation: shimmer-task 2.5s infinite;
        }
        @keyframes shimmer-task {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes slow-spin {
            0%, 38.89% { transform: rotate(0deg); }
            55.56% { transform: rotate(180deg); }
            83.33% { transform: rotate(180deg); }
            100% { transform: rotate(360deg); }
        }
        .material-symbols-outlined.title-icon-animated { animation: slow-spin 18s linear infinite; }
        @keyframes float-drift {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .material-icons.routine-icon-animated { animation: float-drift 5s ease-in-out infinite; }
        .badge-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
        }
        .badge-item {
            background: #18181b;
            border: 2px solid #3f3f46;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        .badge-item.unlocked {
            border-color: #3B82F6;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .badge-item.unlocked:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
        }
        .badge-icon {
            font-size: 40px;
            margin-bottom: 8px;
            filter: grayscale(100%);
            opacity: 0.3;
        }
        .badge-item.unlocked .badge-icon {
            filter: grayscale(0%);
            opacity: 1;
            animation: badge-glow 2s ease-in-out infinite;
        }
        @keyframes badge-glow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .badge-name {
            font-size: 11px;
            font-weight: 600;
            margin-top: 4px;
            color: #71717a;
        }
        .badge-item.unlocked .badge-name {
            color: #fff;
        }
        .xp-bar {
            position: relative;
            height: 24px;
            background: #18181b;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #3f3f46;
        }
        .xp-fill {
            height: 100%;
            background: linear-gradient(90deg, #3B82F6 0%, #60A5FA 100%);
            transition: width 0.5s ease;
            position: relative;
            border-radius: 10px;
            overflow: hidden;
        }
        .xp-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
            animation: shimmer-xp 2.5s infinite;
        }
        @keyframes shimmer-xp {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .xp-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            z-index: 10;
        }
        .achievement-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.5);
            z-index: 3000;
            display: none;
            animation: slideIn 0.5s ease;
            border: 2px solid #3B82F6;
        }
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .achievement-notification.show { display: block; }
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }
        .help-modal-popup { z-index: 3000; }
        .help-modal-popup .popup-content {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            max-width: 400px;
            text-align: center;
        }
        .help-modal-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }
        .help-modal-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }
        .help-modal-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.2s;
        }
        .help-modal-btn.primary {
            background: #10B981;
            color: white;
        }
        .help-modal-btn.primary:hover {
            background: #059669;
        }
        .help-modal-btn.secondary {
            background: #3f3f46;
            color: white;
        }
        .help-modal-btn.secondary:hover {
            background: #52525b;
        }
        .whats-new-popup { z-index: 3000; }
        .whats-new-popup .popup-content {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            position: relative;
        }
        .whats-new-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .whats-new-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: white;
        }
        .whats-new-close {
            cursor: pointer;
            font-size: 24px;
            color: white;
            background: none;
            border: none;
            padding: 0;
        }
        .whats-new-item {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: left;
        }
        .whats-new-item:last-child {
            border-bottom: none;
        }
        .whats-new-icon {
            font-size: 32px;
            flex-shrink: 0;
        }
        .whats-new-text h4 {
            font-weight: bold;
            margin: 0 0 4px 0;
        }
        .whats-new-text p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        .whats-new-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            justify-content: center;
        }
        .tutorial-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 4000;
            display: none;
        }
        .tutorial-highlight {
            position: fixed;
            border: 3px solid #3B82F6;
            box-shadow: 0 0 0 9999px rgba(0,0,0,0.7);
            border-radius: 8px;
            z-index: 4001;
            animation: pulse-highlight 2s infinite;
            display: none;
        }
        @keyframes pulse-highlight {
            0%, 100% { box-shadow: 0 0 0 9999px rgba(0,0,0,0.7), 0 0 20px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 0 9999px rgba(0,0,0,0.7), 0 0 40px rgba(59, 130, 246, 0.8); }
        }
        .tutorial-tooltip {
            position: fixed;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 16px;
            border-radius: 12px;
            border: 2px solid #3B82F6;
            max-width: 320px;
            z-index: 4002;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.5);
            animation: fadeInUp 0.5s ease;
            display: none;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .tutorial-tooltip h3 {
            font-weight: bold;
            font-size: 18px;
            margin: 0 0 8px 0;
        }
        .tutorial-tooltip p {
            font-size: 14px;
            line-height: 1.5;
            margin: 0 0 12px 0;
        }
        .tutorial-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .tutorial-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
            font-size: 14px;
        }
        .tutorial-btn.skip {
            background: #3f3f46;
            color: white;
        }
        .tutorial-btn.skip:hover {
            background: #52525b;
        }
        .tutorial-btn.next {
            background: #10B981;
            color: white;
        }
        .tutorial-btn.next:hover {
            background: #059669;
        }
        .history-day-item {
            cursor: pointer;
            transition: all 0.2s;
        }
        .history-day-item:hover {
            background: #27272a;
            transform: translateX(4px);
        }
        #shareCanvas {
            display: none;
        }
        .icon-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            margin-top: 16px;
        }
        .icon-pagination-btn {
            background: #3B82F6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: bold;
            transition: all 0.2s;
        }
        .icon-pagination-btn:hover {
            background: #2563eb;
        }
        .icon-pagination-btn:disabled {
            background: #71717a;
            cursor: not-allowed;
            opacity: 0.5;
        }
        .icon-pagination-info {
            font-size: 14px;
            color: #666;
            font-weight: 600;
        }
        .icon-picker-container {
            position: relative;
            overflow: hidden;
            touch-action: pan-y;
        }
        .limit-warning-popup .popup-content {
            background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
            color: white;
            text-align: left;
        }
        .limit-warning-popup .popup-content h3 {
            color: white;
            text-align: center;
            margin-bottom: 16px;
            font-size: 22px;
        }
        .limit-warning-popup .popup-content p {
            color: white;
            margin-bottom: 12px;
        }
        .limit-warning-popup .popup-content strong {
            color: #fca5a5;
        }
        .limit-warning-popup .close-btn {
            background: #3B82F6;
            color: white;
        }
    </style>
</head>
<body>
    <div id="app">Chargement...</div>
    <canvas id="shareCanvas" width="1080" height="1920"></canvas>
    
    <!-- Popups -->
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org</p>
            <p>API : 0.dev</p>
            <p id="appVersion"></p>
            <p id="clientUUID"></p>
            <button class="close-btn" onclick="closePopup('aboutPopup')">Fermer</button>
        </div>
    </div>
    
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée. Tout est stocké sur votre appareil.</p>
            <button class="close-btn" onclick="closePopup('privacyPopup')">Fermer</button>
        </div>
    </div>
    
    <div id="editRoutinePopup" class="popup">
        <div class="popup-content" style="text-align:left;">
            <h3 style="font-weight:bold;margin-bottom:15px;text-align:center;">Modifier la routine</h3>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Nom de la routine</label>
            <input type="text" id="routineNameInput" class="task-input" placeholder="Ex: Routine du matin">
            <label style="display:block;margin:10px 0 5px;font-weight:bold;">Icône</label>
            <div class="icon-picker-container">
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="iconPicker"></div>
            </div>
            <div class="icon-pagination" id="routineIconPagination"></div>
            <button class="close-btn" onclick="saveRoutineEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button>
            <button class="close-btn" onclick="closePopup('editRoutinePopup')" style="width:100%;">Annuler</button>
        </div>
    </div>
    
    <div id="addTaskPopup" class="popup">
        <div class="popup-content" style="text-align:left;">
            <h3 class="title-genos" style="font-weight:bold;margin-bottom:20px;text-align:center;font-size:28px;">Ajouter une tâche</h3>
            <input type="text" id="taskNameInput" class="task-input" placeholder="Titre — Ex.: Méditation">
            <label style="display:block;margin:15px 0 10px;font-weight:bold;">Choisir une icône</label>
            <div class="icon-picker-container">
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin:15px 0;" id="taskIconPicker"></div>
            </div>
            <div class="icon-pagination" id="taskIconPagination"></div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Durée estimée (minutes)</label>
            <input type="number" id="taskDurationInput" class="task-input" placeholder="15" min="0">
            <button class="close-btn" onclick="saveNewTask()" style="width:100%;background:#3B82F6;color:white;">Ajouter</button>
            <button class="close-btn" onclick="closePopup('addTaskPopup')" style="width:100%;">Annuler</button>
        </div>
    </div>
    
    <div id="editTaskPopup" class="popup">
        <div class="popup-content" style="text-align:left;">
            <h3 class="task-name-sofia" style="font-weight:bold;margin-bottom:15px;text-align:center;">Modifier la tâche</h3>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Nom de la tâche</label>
            <input type="text" id="editTaskNameInput" class="task-input task-name-sofia" placeholder="Ex: Méditation">
            <label style="display:block;margin:10px 0 5px;font-weight:bold;">Icône</label>
            <div class="icon-picker-container">
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="editTaskIconPicker"></div>
            </div>
            <div class="icon-pagination" id="editTaskIconPagination"></div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Durée estimée (minutes)</label>
            <input type="number" id="editTaskDurationInput" class="task-input" placeholder="15" min="0">
            <button class="close-btn" onclick="saveTaskEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button>
            <button class="close-btn" onclick="closePopup('editTaskPopup')" style="width:100%;">Annuler</button>
        </div>
    </div>
    
    <div id="badgeDetailPopup" class="popup">
        <div class="popup-content">
            <div id="badgeDetailContent"></div>
            <button class="close-btn" onclick="closePopup('badgeDetailPopup')" style="width:100%;margin-top:15px;">Fermer</button>
        </div>
    </div>
    
    <div id="dayDetailPopup" class="popup">
        <div class="popup-content" style="text-align:left; position: relative;">
            <div class="whats-new-header">
                <h3 id="dayDetailTitle" style="font-weight:bold;margin-bottom:15px;text-align:center;"></h3>
                <button class="whats-new-close" onclick="closePopup('dayDetailPopup')" style="display:block;">✕</button>
            </div>
            <div id="dayDetailContent"></div>
        </div>
    </div>
    
    <div id="limitWarningPopup" class="popup limit-warning-popup">
        <div class="popup-content">
            <h3>⚠️ Limite atteinte</h3>
            <div id="limitWarningContent"></div>
            <button class="close-btn" onclick="closePopup('limitWarningPopup')" style="width:100%;margin-top:15px;">J'ai compris</button>
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
    
    <div id="helpModalPopup" class="popup help-modal-popup">
        <div class="popup-content">
            <div class="help-modal-title">Routines — v0.12-beta</div>
            <div class="help-modal-buttons">
                <button class="help-modal-btn primary" onclick="showWhatsNew()">Quoi de neuf ?</button>
                <button class="help-modal-btn primary" onclick="startTutorial();closePopup('helpModalPopup');">Tutoriel</button>
                <button class="help-modal-btn secondary" onclick="closePopup('helpModalPopup')">Fermer</button>
            </div>
        </div>
    </div>
    
    <div id="whatsNewPopup" class="popup whats-new-popup">
        <div class="popup-content">
            <div class="whats-new-header">
                <h2 class="whats-new-title">✨ Quoi de neuf ?</h2>
                <button class="whats-new-close" id="whatsNewCloseBtn" style="display:none;" onclick="closePopup('whatsNewPopup')">✕</button>
            </div>
            <div id="whatsNewContent"></div>
            <div class="whats-new-buttons">
                <button class="help-modal-btn primary" onclick="closePopup('whatsNewPopup');startTutorial();">Tutoriel</button>
            </div>
        </div>
    </div>
    
    <div id="tutorialOverlay" class="tutorial-overlay"></div>
    <div id="tutorialHighlight" class="tutorial-highlight"></div>
    <div id="tutorialTooltip" class="tutorial-tooltip"></div>
    
    <script src="script.js"></script>
</body>
</html>
