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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Genos:wght@400;500;600;700&family=Sofia+Sans+Condensed:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding-bottom: 40px; }
        .popup { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; display: none; align-items: center; justify-content: center; }
        .popup-content { background: white; color: black; width: 90%; max-width: 500px; padding: 20px; border-radius: 10px; text-align: center; max-height: 80vh; overflow-y: auto; }
        .popup-content p { font-size: 16px; margin-bottom: 10px; }
        .close-btn { padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .task-input { padding: 8px; width: 100%; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        .task-item { cursor: move; transition: all 0.2s; }
        .task-item:hover { background-color: #2c2c2c; }
        .calendar-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 600; }
        .calendar-day:hover { transform: scale(1.1); }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%); color: white; border-radius: 8px; text-decoration: none; transition: all 0.2s; }
        .back-btn:hover { transform: translateX(-3px); box-shadow: 0 4px 12px rgba(13, 71, 161, 0.4); }
        .icon-option { padding: 10px; border: 2px solid #ccc; border-radius: 5px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .icon-option:hover { border-color: #3B82F6; background: #f0f9ff; }
        .icon-option.selected { border-color: #3B82F6; background: #dbeafe; }
        .title-genos { font-family: 'Genos', sans-serif; }
        .title-icon-container { margin-left: auto; }
        .task-name-sofia { font-family: 'Sofia Sans Condensed', sans-serif; }
        .streak-pulse { animation: pulse-intense 1.5s ease-in-out infinite; }
        @keyframes pulse-intense {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }
        .task-progress-bar { position: relative; overflow: hidden; }
        .task-progress-fill { position: relative; overflow: hidden; }
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
        
        /* ====== NOUVEAU v0.9b : STYLES BADGES & ACHIEVEMENTS ====== */
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
        }
        .xp-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
            animation: shimmer-xp 2s infinite;
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
            z-index: 1;
        }
        
        /* Notification de déblocage */
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
        
        /* Stats charts */
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div id="app">Chargement...</div>
    
    <!-- Popups existants -->
    <div id="aboutPopup" class="popup"><div class="popup-content"><p>©2025 SanteMentale.org</p><p>API : 0.dev</p><p id="appVersion"></p><p id="clientUUID"></p><button class="close-btn" onclick="closePopup('aboutPopup')">Fermer</button></div></div>
    <div id="privacyPopup" class="popup"><div class="popup-content"><p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée. Tout est stocké sur votre appareil.</p><button class="close-btn" onclick="closePopup('privacyPopup')">Fermer</button></div></div>
    
    <div id="editRoutinePopup" class="popup">
        <div class="popup-content" style="text-align:left;">
            <h3 style="font-weight:bold;margin-bottom:15px;text-align:center;" id="editRoutineTitle">Modifier la routine</h3>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Nom de la routine</label>
            <input type="text" id="routineNameInput" class="task-input" placeholder="Ex: Routine du matin">
            <label style="display:block;margin:10px 0 5px;font-weight:bold;">Icône</label>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="iconPicker"></div>
            <button class="close-btn" onclick="saveRoutineEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button>
            <button class="close-btn" onclick="closePopup('editRoutinePopup')" style="width:100%;">Annuler</button>
        </div>
    </div>
    
    <div id="addTaskPopup" class="popup">
        <div class="popup-content" style="text-align:left;">
            <h3 class="title-genos" style="font-weight:bold;margin-bottom:20px;text-align:center;font-size:28px;">Ajouter une tâche</h3>
            <input type="text" id="taskNameInput" class="task-input" placeholder="Titre — Ex.: Méditation">
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin:15px 0;" id="taskIconPicker"></div>
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
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="editTaskIconPicker"></div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Durée estimée (minutes)</label>
            <input type="number" id="editTaskDurationInput" class="task-input" placeholder="15" min="0">
            <button class="close-btn" onclick="saveTaskEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button>
            <button class="close-btn" onclick="closePopup('editTaskPopup')" style="width:100%;">Annuler</button>
        </div>
    </div>
    
    <!-- ====== NOUVEAU v0.9b : POPUP BADGE DETAIL ====== -->
    <div id="badgeDetailPopup" class="popup">
        <div class="popup-content">
            <div id="badgeDetailContent"></div>
            <button class="close-btn" onclick="closePopup('badgeDetailPopup')" style="width:100%;margin-top:15px;">Fermer</button>
        </div>
    </div>
    
    <!-- ====== NOUVEAU v0.9b : NOTIFICATION ACHIEVEMENT ====== -->
    <div id="achievementNotification" class="achievement-notification">
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="material-icons" style="font-size:40px;">emoji_events</span>
            <div>
                <div style="font-weight:bold;font-size:16px;">Achievement débloqué !</div>
                <div id="achievementText" style="font-size:14px;margin-top:4px;"></div>
            </div>
        </div>
    </div>

    <script>
        const MODULE_REVISION = '0.9b'; // ← MISE À JOUR VERSION
        
        const ICONS = [
            'wb_sunny', 'nightlight', 'favorite', 'local_cafe', 'book',
            'fitness_center', 'water_drop', 'music_note', 'brush', 'phone',
            'email', 'shopping_cart', 'nature', 'laptop', 'sports_esports',
            'camera_alt', 'edit', 'palette', 'home', 'work',
            'restaurant', 'bed', 'self_improvement', 'spa', 'pets',
            'alarm', 'directions_run', 'school', 'local_dining', 'psychology'
        ];
        
        const TASK_ICONS = [
            'self_improvement', 'spa', 'favorite_border', 'local_cafe', 'restaurant',
            'water_drop', 'fitness_center', 'directions_run', 'bedtime', 'alarm',
            'book', 'edit', 'music_note', 'brush', 'palette',
            'shower', 'clean_hands', 'medication', 'healing', 'psychology',
            'wb_sunny', 'nightlight', 'nature', 'park', 'pets',
            'phone', 'people', 'home', 'work', 'school'
        ];
        
        // ====== NOUVEAU v0.9b : DÉFINITION DES BADGES ======
        const BADGES = [
            { id: 'first_step', name: 'Premier Pas', icon: '🎯', description: 'Compléter votre première tâche', requirement: 'tasks', threshold: 1 },
            { id: 'dedicated', name: 'Dévoué', icon: '💪', description: 'Compléter 50 tâches', requirement: 'tasks', threshold: 50 },
            { id: 'champion', name: 'Champion', icon: '🏆', description: 'Compléter 200 tâches', requirement: 'tasks', threshold: 200 },
            { id: 'legend', name: 'Légende', icon: '⭐', description: 'Compléter 500 tâches', requirement: 'tasks', threshold: 500 },
            { id: 'week_streak', name: 'Hebdomadaire', icon: '🔥', description: '7 jours de suite à >75%', requirement: 'streak', threshold: 7 },
            { id: 'month_streak', name: 'Mensuel', icon: '💎', description: '30 jours de suite à >75%', requirement: 'streak', threshold: 30 },
            { id: 'perfect_day', name: 'Journée Parfaite', icon: '✨', description: 'Compléter 100% des tâches en une journée', requirement: 'perfect_days', threshold: 1 },
            { id: 'early_bird', name: 'Lève-tôt', icon: '🌅', description: 'Compléter une routine du matin', requirement: 'morning_routines', threshold: 1 },
            { id: 'night_owl', name: 'Couche-tard', icon: '🌙', description: 'Compléter une routine du soir', requirement: 'evening_routines', threshold: 1 },
            { id: 'organizer', name: 'Organisateur', icon: '📋', description: 'Créer 3 routines personnalisées', requirement: 'custom_routines', threshold: 3 }
        ];
        
        // ====== NOUVEAU v0.9b : SYSTÈME XP & NIVEAUX ======
        const XP_PER_TASK = 10;
        const XP_PER_LEVEL = 100;

        let state = {
            routines: [],
            history: [],
            view: 'today',
            currentDate: new Date().toLocaleDateString('fr-FR'),
            editingRoutineId: null,
            addingTaskToRoutine: null,
            editingTaskRoutineId: null,
            editingTaskId: null,
            selectedRoutineIcon: 'wb_sunny',
            selectedTaskIcon: 'self_improvement',
            selectedEditTaskIcon: 'self_improvement',
            calendarDate: new Date(),
            streak: 0,
            // ====== NOUVEAU v0.9b : ÉTAT XP & BADGES ======
            xp: 0,
            level: 1,
            unlockedBadges: [],
            totalTasksCompleted: 0,
            perfectDaysCount: 0,
            statsChart: null
        };

        function initDefaultRoutines() {
            return [
                { id: 'morning', name: 'Routine Matin', icon: 'wb_sunny', tasks: [
                    { id: 't1', name: 'Se réveiller', icon: 'bedtime', duration: 0, completed: false },
                    { id: 't2', name: 'Boire un verre d\'eau', icon: 'water_drop', duration: 2, completed: false },
                    { id: 't3', name: 'Petit déjeuner', icon: 'local_cafe', duration: 20, completed: false },
                    { id: 't4', name: 'Hygiène', icon: 'shower', duration: 15, completed: false }
                ]},
                { id: 'day', name: 'Routine Journée', icon: 'laptop', tasks: [
                    { id: 't5', name: 'Planifier la journée', icon: 'edit', duration: 10, completed: false },
                    { id: 't6', name: 'Travail/Études', icon: 'laptop', duration: 240, completed: false },
                    { id: 't7', name: 'Pause déjeuner', icon: 'restaurant', duration: 45, completed: false },
                    { id: 't8', name: 'Activité physique', icon: 'fitness_center', duration: 30, completed: false }
                ]},
                { id: 'evening', name: 'Routine Soir', icon: 'nightlight', tasks: [
                    { id: 't9', name: 'Dîner', icon: 'restaurant', duration: 30, completed: false },
                    { id: 't10', name: 'Moment détente', icon: 'book', duration: 60, completed: false },
                    { id: 't11', name: 'Préparer le lendemain', icon: 'edit', duration: 15, completed: false },
                    { id: 't12', name: 'Coucher', icon: 'bedtime', duration: 0, completed: false }
                ]}
            ];
        }

        function openPopup(id) { document.getElementById(id).style.display = 'flex'; }
        function closePopup(id) { document.getElementById(id).style.display = 'none'; }

        // ====== NOUVEAU v0.9b : FONCTION GAIN XP ======
        function gainXP(amount) {
            state.xp += amount;
            const oldLevel = state.level;
            state.level = Math.floor(state.xp / XP_PER_LEVEL) + 1;
            
            localStorage.setItem('routineXP', state.xp.toString());
            localStorage.setItem('routineLevel', state.level.toString());
            
            if (state.level > oldLevel) {
                showAchievementNotification('Niveau ' + state.level + ' atteint ! 🎉');
            }
        }

        // ====== NOUVEAU v0.9b : VÉRIFICATION BADGES ======
        function checkBadges() {
            BADGES.forEach(badge => {
                if (state.unlockedBadges.includes(badge.id)) return;
                
                let unlock = false;
                
                switch(badge.requirement) {
                    case 'tasks':
                        unlock = state.totalTasksCompleted >= badge.threshold;
                        break;
                    case 'streak':
                        unlock = state.streak >= badge.threshold;
                        break;
                    case 'perfect_days':
                        unlock = state.perfectDaysCount >= badge.threshold;
                        break;
                    case 'morning_routines':
                        const morningRoutine = state.routines.find(r => r.name.toLowerCase().includes('matin'));
                        if (morningRoutine) {
                            const today = state.history.find(h => h.date === state.currentDate);
                            if (today && today.routines[morningRoutine.id]) {
                                const tasks = morningRoutine.tasks;
                                const completed = tasks.filter(t => today.routines[morningRoutine.id][t.id]).length;
                                unlock = completed === tasks.length;
                            }
                        }
                        break;
                    case 'evening_routines':
                        const eveningRoutine = state.routines.find(r => r.name.toLowerCase().includes('soir'));
                        if (eveningRoutine) {
                            const today = state.history.find(h => h.date === state.currentDate);
                            if (today && today.routines[eveningRoutine.id]) {
                                const tasks = eveningRoutine.tasks;
                                const completed = tasks.filter(t => today.routines[eveningRoutine.id][t.id]).length;
                                unlock = completed === tasks.length;
                            }
                        }
                        break;
                    case 'custom_routines':
                        const defaultIds = ['morning', 'day', 'evening'];
                        const customCount = state.routines.filter(r => !defaultIds.includes(r.id)).length;
                        unlock = customCount >= badge.threshold;
                        break;
                }
                
                if (unlock) {
                    state.unlockedBadges.push(badge.id);
                    localStorage.setItem('routineBadges', JSON.stringify(state.unlockedBadges));
                    showAchievementNotification(badge.icon + ' ' + badge.name + ' débloqué !');
                }
            });
        }

        // ====== NOUVEAU v0.9b : NOTIFICATION ACHIEVEMENT ======
        function showAchievementNotification(text) {
            const notif = document.getElementById('achievementNotification');
            document.getElementById('achievementText').textContent = text;
            notif.classList.add('show');
            setTimeout(() => {
                notif.classList.remove('show');
            }, 4000);
        }

        function toggleTask(routineId, taskId) {
            const routine = state.routines.find(r => r.id === routineId);
            if (!routine) return;
            const task = routine.tasks.find(t => t.id === taskId);
            if (!task) return;
            
            task.completed = !task.completed;
            
            const today = state.currentDate;
            let dayHistory = state.history.find(h => h.date === today);
            if (!dayHistory) {
                dayHistory = { date: today, routines: {} };
                state.history.push(dayHistory);
            }
            if (!dayHistory.routines[routineId]) {
                dayHistory.routines[routineId] = {};
            }
            dayHistory.routines[routineId][taskId] = task.completed;
            
            // ====== NOUVEAU v0.9b : GAIN XP SI COMPLÉTÉ ======
            if (task.completed) {
                gainXP(XP_PER_TASK);
                state.totalTasksCompleted++;
                localStorage.setItem('routineTotalTasks', state.totalTasksCompleted.toString());
            } else {
                // Retrait XP si décoché
                state.xp = Math.max(0, state.xp - XP_PER_TASK);
                state.totalTasksCompleted = Math.max(0, state.totalTasksCompleted - 1);
                state.level = Math.floor(state.xp / XP_PER_LEVEL) + 1;
                localStorage.setItem('routineXP', state.xp.toString());
                localStorage.setItem('routineLevel', state.level.toString());
                localStorage.setItem('routineTotalTasks', state.totalTasksCompleted.toString());
            }
            
            // Vérifier si journée parfaite
            const completion = getTodayCompletion();
            if (completion === 100) {
                state.perfectDaysCount++;
                localStorage.setItem('routinePerfectDays', state.perfectDaysCount.toString());
            }
            
            localStorage.setItem('routines', JSON.stringify(state.routines));
            localStorage.setItem('routineHistory', JSON.stringify(state.history));
            
            calcStreak();
            checkBadges(); // ← NOUVEAU : Vérifier les badges
            render();
            return false;
        }

        function openEditRoutine(routineId) {
            state.editingRoutineId = routineId;
            const routine = state.routines.find(r => r.id === routineId);
            document.getElementById('routineNameInput').value = routine.name;
            state.selectedRoutineIcon = routine.icon;
            renderIconPicker();
            openPopup('editRoutinePopup');
        }

        function saveRoutineEdit() {
            const routine = state.routines.find(r => r.id === state.editingRoutineId);
            routine.name = document.getElementById('routineNameInput').value.trim() || routine.name;
            routine.icon = state.selectedRoutineIcon;
            localStorage.setItem('routines', JSON.stringify(state.routines));
            closePopup('editRoutinePopup');
            checkBadges(); // ← NOUVEAU : Vérifier badges après édition
            render();
        }

        function renderIconPicker() {
            const picker = document.getElementById('iconPicker');
            picker.innerHTML = ICONS.map(icon =>
                '<div class="icon-option ' + (state.selectedRoutineIcon === icon ? 'selected' : '') + '" onclick="state.selectedRoutineIcon=\'' + icon + '\';renderIconPicker();"><span class="material-icons" style="font-size:24px;color:black;">' + icon + '</span></div>'
            ).join('');
        }

        function openAddTask(routineId) {
            state.addingTaskToRoutine = routineId;
            state.selectedTaskIcon = 'self_improvement';
            document.getElementById('taskNameInput').value = '';
            document.getElementById('taskDurationInput').value = '';
            renderTaskIconPicker();
            openPopup('addTaskPopup');
        }

        function renderTaskIconPicker() {
            const picker = document.getElementById('taskIconPicker');
            picker.innerHTML = TASK_ICONS.map(icon =>
                '<div class="icon-option ' + (state.selectedTaskIcon === icon ? 'selected' : '') + '" onclick="state.selectedTaskIcon=\'' + icon + '\';renderTaskIconPicker();"><span class="material-icons" style="font-size:24px;color:black;">' + icon + '</span></div>'
            ).join('');
        }

        function saveNewTask() {
            const name = document.getElementById('taskNameInput').value.trim();
            if (!name) return;
            const durationVal = document.getElementById('taskDurationInput').value;
            const duration = durationVal === '' ? 15 : parseInt(durationVal);
            const routine = state.routines.find(r => r.id === state.addingTaskToRoutine);
            const newTask = { id: 't' + Date.now(), name: name, icon: state.selectedTaskIcon, duration: duration, completed: false };
            routine.tasks.push(newTask);
            localStorage.setItem('routines', JSON.stringify(state.routines));
            closePopup('addTaskPopup');
            render();
        }

        function openEditTask(routineId, taskId) {
            state.editingTaskRoutineId = routineId;
            state.editingTaskId = taskId;
            const routine = state.routines.find(r => r.id === routineId);
            const task = routine.tasks.find(t => t.id === taskId);
            document.getElementById('editTaskNameInput').value = task.name;
            document.getElementById('editTaskDurationInput').value = task.duration;
            state.selectedEditTaskIcon = task.icon;
            renderEditTaskIconPicker();
            openPopup('editTaskPopup');
        }

        function renderEditTaskIconPicker() {
            const picker = document.getElementById('editTaskIconPicker');
            picker.innerHTML = TASK_ICONS.map(icon =>
                '<div class="icon-option ' + (state.selectedEditTaskIcon === icon ? 'selected' : '') + '" onclick="state.selectedEditTaskIcon=\'' + icon + '\';renderEditTaskIconPicker();"><span class="material-icons" style="font-size:24px;color:black;">' + icon + '</span></div>'
            ).join('');
        }

        function saveTaskEdit() {
            const routine = state.routines.find(r => r.id === state.editingTaskRoutineId);
            const task = routine.tasks.find(t => t.id === state.editingTaskId);
            task.name = document.getElementById('editTaskNameInput').value.trim() || task.name;
            const durationVal = document.getElementById('editTaskDurationInput').value;
            task.duration = durationVal === '' ? 15 : parseInt(durationVal);
            task.icon = state.selectedEditTaskIcon;
            localStorage.setItem('routines', JSON.stringify(state.routines));
            closePopup('editTaskPopup');
            render();
        }

        function deleteTask(routineId, taskId) {
            if (!confirm('Supprimer cette tâche ?')) return;
            const routine = state.routines.find(r => r.id === routineId);
            routine.tasks = routine.tasks.filter(t => t.id !== taskId);
            localStorage.setItem('routines', JSON.stringify(state.routines));
            render();
        }

        function deleteRoutine(routineId) {
            if (!confirm('Supprimer cette routine complète ?')) return;
            state.routines = state.routines.filter(r => r.id !== routineId);
            localStorage.setItem('routines', JSON.stringify(state.routines));
            render();
        }

        function addNewRoutine() {
            const name = prompt('Nom de la nouvelle routine:');
            if (!name) return;
            const newRoutine = { id: 'r' + Date.now(), name: name, icon: 'wb_sunny', tasks: [] };
            state.routines.push(newRoutine);
            localStorage.setItem('routines', JSON.stringify(state.routines));
            checkBadges(); // ← NOUVEAU : Vérifier badges
            render();
        }

        function getTodayCompletion() {
            const today = state.history.find(h => h.date === state.currentDate);
            if (!today) return 0;
            let total = 0, completed = 0;
            state.routines.forEach(r => {
                r.tasks.forEach(t => {
                    total++;
                    if (today.routines[r.id] && today.routines[r.id][t.id]) completed++;
                });
            });
            return total > 0 ? Math.round((completed / total) * 100) : 0;
        }

        function getRoutineCompletion(routineId) {
            const routine = state.routines.find(r => r.id === routineId);
            if (!routine || routine.tasks.length === 0) return 0;
            const completed = routine.tasks.filter(t => t.completed).length;
            return Math.round((completed / routine.tasks.length) * 100);
        }

        function calcStreak() {
            const dates = state.history.map(h => h.date).sort((a,b) => new Date(b.split('/').reverse().join('-')) - new Date(a.split('/').reverse().join('-')));
            let s = 0;
            const today = new Date();
            for (let i = 0; i < 365; i++) {
                const checkDate = new Date(today);
                checkDate.setDate(today.getDate() - i);
                const ds = checkDate.toLocaleDateString('fr-FR');
                const dayData = state.history.find(h => h.date === ds);
                if (dayData) {
                    let dayTotal = 0, dayCompleted = 0;
                    state.routines.forEach(r => {
                        r.tasks.forEach(t => {
                            dayTotal++;
                            if (dayData.routines[r.id] && dayData.routines[r.id][t.id]) dayCompleted++;
                        });
                    });
                    if (dayTotal > 0 && dayCompleted / dayTotal >= 0.75) s++;
                    else break;
                } else break;
            }
            state.streak = s;
            localStorage.setItem('routineStreak', s.toString());
        }

        function renderCal() {
            const y = state.calendarDate.getFullYear(), m = state.calendarDate.getMonth();
            const first = new Date(y, m, 1), last = new Date(y, m + 1, 0);
            const days = last.getDate(), start = first.getDay();
            let h = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
            h += '<div class="flex items-center justify-between mb-4">';
            h += '<button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()-1);render();" class="text-blue-400"><span class="material-icons">chevron_left</span></button>';
            h += '<h3 class="font-bold">' + first.toLocaleDateString('fr-FR', {month:'long',year:'numeric'}) + '</h3>';
            h += '<button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()+1);render();" class="text-blue-400"><span class="material-icons">chevron_right</span></button>';
            h += '</div><div class="grid grid-cols-7 gap-1 text-center text-xs mb-2 text-zinc-500"><div>D</div><div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div></div>';
            h += '<div class="grid grid-cols-7 gap-1">';
            for (let i = 0; i < start; i++) h += '<div></div>';
            for (let d = 1; d <= days; d++) {
                const dt = new Date(y, m, d), ds = dt.toLocaleDateString('fr-FR');
                const dayData = state.history.find(h => h.date === ds);
                let completion = 0;
                if (dayData) {
                    let total = 0, completed = 0;
                    state.routines.forEach(r => {
                        r.tasks.forEach(t => {
                            total++;
                            if (dayData.routines[r.id] && dayData.routines[r.id][t.id]) completed++;
                        });
                    });
                    completion = total > 0 ? completed / total : 0;
                }
                let bg = '#18181b', txt = '#a1a1aa';
                if (completion > 0) {
                    if (completion >= 0.76) { bg = '#10B981'; txt = '#fff'; }
                    else if (completion >= 0.51) { bg = '#F59E0B'; txt = '#fff'; }
                    else if (completion >= 0.26) { bg = '#EF4444'; txt = '#fff'; }
                    else { bg = '#3B82F6'; txt = '#fff'; }
                }
                h += '<div class="calendar-day" style="background-color:' + bg + ';color:' + txt + ';" title="' + Math.round(completion*100) + '%">' + d + '</div>';
            }
            h += '</div><div class="mt-3 text-xs text-zinc-500 flex gap-2"><div><span class="inline-block w-3 h-3 rounded" style="background:#3B82F6"></span> 0-25%</div><div><span class="inline-block w-3 h-3 rounded" style="background:#EF4444"></span> 26-50%</div><div><span class="inline-block w-3 h-3 rounded" style="background:#F59E0B"></span> 51-75%</div><div><span class="inline-block w-3 h-3 rounded" style="background:#10B981"></span> 76-100%</div></div></div>';
            return h;
        }

        // ====== NOUVEAU v0.9b : AFFICHAGE DÉTAIL BADGE ======
        function showBadgeDetail(badgeId) {
            const badge = BADGES.find(b => b.id === badgeId);
            if (!badge) return;
            
            const unlocked = state.unlockedBadges.includes(badgeId);
            let progress = 0;
            
            switch(badge.requirement) {
                case 'tasks':
                    progress = Math.min(100, Math.round((state.totalTasksCompleted / badge.threshold) * 100));
                    break;
                case 'streak':
                    progress = Math.min(100, Math.round((state.streak / badge.threshold) * 100));
                    break;
                case 'perfect_days':
                    progress = Math.min(100, Math.round((state.perfectDaysCount / badge.threshold) * 100));
                    break;
            }
            
            let html = '<div style="text-align:center;">';
            html += '<div style="font-size:60px;margin-bottom:15px;' + (unlocked ? '' : 'filter:grayscale(100%);opacity:0.3;') + '">' + badge.icon + '</div>';
            html += '<h3 style="font-size:24px;font-weight:bold;margin-bottom:10px;">' + badge.name + '</h3>';
            html += '<p style="color:#666;margin-bottom:15px;">' + badge.description + '</p>';
            if (unlocked) {
                html += '<div style="background:#10B981;color:white;padding:8px 16px;border-radius:8px;display:inline-block;font-weight:bold;">✓ Débloqué</div>';
            } else {
                html += '<div style="margin-top:15px;"><div style="background:#eee;height:20px;border-radius:10px;overflow:hidden;"><div style="background:#3B82F6;height:100%;width:' + progress + '%;transition:width 0.3s;"></div></div>';
                html += '<p style="margin-top:8px;font-size:14px;color:#666;">Progression : ' + progress + '%</p></div>';
            }
            html += '</div>';
            
            document.getElementById('badgeDetailContent').innerHTML = html;
            openPopup('badgeDetailPopup');
        }

        // ====== NOUVEAU v0.9b : RENDER GRAPHIQUE STATS ======
        function renderStatsChart() {
            setTimeout(() => {
                const canvas = document.getElementById('statsChart');
                if (!canvas) return;
                
                const ctx = canvas.getContext('2d');
                
                // Détruire l'ancien graphique
                if (state.statsChart) {
                    state.statsChart.destroy();
                }
                
                // Préparer les données des 7 derniers jours
                const labels = [];
                const data = [];
                const today = new Date();
                
                for (let i = 6; i >= 0; i--) {
                    const d = new Date(today);
                    d.setDate(today.getDate() - i);
                    const dateStr = d.toLocaleDateString('fr-FR');
                    labels.push(d.toLocaleDateString('fr-FR', { weekday: 'short' }));
                    
                    const dayData = state.history.find(h => h.date === dateStr);
                    if (dayData) {
                        let total = 0, completed = 0;
                        state.routines.forEach(r => {
                            r.tasks.forEach(t => {
                                total++;
                                if (dayData.routines[r.id] && dayData.routines[r.id][t.id]) completed++;
                            });
                        });
                        data.push(total > 0 ? Math.round((completed / total) * 100) : 0);
                    } else {
                        data.push(0);
                    }
                }
                
                state.statsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Progression (%)',
                            data: data,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: { color: '#71717a' },
                                grid: { color: '#27272a' }
                            },
                            x: {
                                ticks: { color: '#71717a' },
                                grid: { color: '#27272a' }
                            }
                        }
                    }
                });
            }, 100);
        }

        function render() {
            const app = document.getElementById('app');
            const clientVersion = localStorage.getItem('client_version') || '1.web';
            
            let h = '<div class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-10"><div class="max-w-2xl mx-auto px-4 py-4 flex items-center gap-3">';
            h += '<a href="/v1/outils/?v=' + clientVersion + '" class="back-btn" style="flex-shrink:0;"><span class="material-icons">arrow_back</span></a>';
            h += '<h1 class="text-3xl font-bold title-genos">Routines</h1><div class="title-icon-container"><span class="material-symbols-outlined title-icon-animated" style="color:#3B82F6;font-size:32px;">routine</span></div></div></div>';
            
            h += '<div class="bg-zinc-900 border-b border-zinc-800"><div class="max-w-2xl mx-auto px-4"><div class="flex gap-1 justify-around">';
            h += '<button onclick="state.view=\'today\';render();" class="py-4 px-3 transition-all ' + (state.view==='today'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">check_box</span></button>';
            h += '<button onclick="state.view=\'stats\';render();" class="py-4 px-3 transition-all ' + (state.view==='stats'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">bar_chart</span></button>';
            h += '<button onclick="state.view=\'manage\';render();" class="py-4 px-3 transition-all ' + (state.view==='manage'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">settings</span></button>';
            h += '<button onclick="state.view=\'calendar\';render();" class="py-4 px-3 transition-all ' + (state.view==='calendar'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">calendar_month</span></button>';
            h += '<button onclick="state.view=\'goals\';render();" class="py-4 px-3 transition-all ' + (state.view==='goals'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">emoji_events</span></button>';
            h += '</div></div></div><div class="max-w-2xl mx-auto px-4 py-6">';
            
            // ====== NOUVEAU v0.9b : BARRE XP EN HAUT DE CHAQUE VUE ======
            const xpInLevel = state.xp % XP_PER_LEVEL;
            const xpPercent = (xpInLevel / XP_PER_LEVEL) * 100;
            h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4">';
            h += '<div class="flex items-center justify-between mb-2">';
            h += '<div class="flex items-center gap-2"><span class="material-icons text-yellow-400" style="font-size:28px;">star</span><span class="font-bold text-lg">Niveau ' + state.level + '</span></div>';
            h += '<span class="text-sm text-zinc-400">' + xpInLevel + ' / ' + XP_PER_LEVEL + ' XP</span>';
            h += '</div><div class="xp-bar"><div class="xp-fill" style="width:' + xpPercent + '%"></div><div class="xp-text">' + Math.round(xpPercent) + '%</div></div></div>';
            
            if (state.view === 'today') {
                const completion = getTodayCompletion();
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4"><div class="flex items-center justify-between mb-2"><span class="font-bold">Progression du ' + new Date().toLocaleDateString('fr-FR', { day: 'numeric', month: 'long' }) + '</span><span class="font-bold text-blue-400">' + completion + '%</span></div><div class="w-full bg-zinc-800 rounded-full h-3 task-progress-bar"><div class="bg-blue-600 h-full rounded-full transition-all task-progress-fill" style="width:' + completion + '%"></div></div></div>';
                
                state.routines.forEach(routine => {
                    const rc = getRoutineCompletion(routine.id);
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4">';
                    h += '<div class="flex items-center justify-between mb-3"><div class="flex items-center gap-2"><span class="material-icons text-2xl routine-icon-animated" style="color:#3B82F6;">' + routine.icon + '</span><h3 class="font-bold">' + routine.name + '</h3></div><span class="text-sm text-zinc-500">' + rc + '%</span></div>';
                    h += '<div class="space-y-2" id="tasks-' + routine.id + '">';
                    routine.tasks.forEach(task => {
                        h += '<div class="flex items-center gap-3 p-3 bg-zinc-950 rounded-lg border border-zinc-800" data-task-id="' + task.id + '" data-routine-id="' + routine.id + '">';
                        h += '<label class="flex items-center cursor-pointer" style="user-select:none;"><input type="checkbox" ' + (task.completed ? 'checked' : '') + ' class="w-5 h-5 checkbox-task" data-routine="' + routine.id + '" data-task="' + task.id + '"></label>';
                        h += '<span class="material-icons text-xl">' + task.icon + '</span>';
                        h += '<div class="flex-1"><div class="font-semibold ' + (task.completed ? 'line-through text-zinc-500' : '') + '">' + task.name + '</div><div class="text-xs text-zinc-500">' + task.duration + ' min • +' + XP_PER_TASK + ' XP</div></div>';
                        h += '</div>';
                    });
                    h += '</div></div>';
                });
                
            } else if (state.view === 'stats') {
                calcStreak();
                
                // ====== NOUVEAU v0.9b : VUE STATS COMPLÈTE ======
                h += '<div class="space-y-4">';
                
                // Graphique 7 jours
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
                h += '<h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">show_chart</span>7 derniers jours</h3>';
                h += '<div class="chart-container"><canvas id="statsChart"></canvas></div>';
                h += '</div>';
                
                // Statistiques globales
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">trending_up</span>Résumé</h3><div class="grid grid-cols-2 gap-3">';
                h += '<div class="text-center p-3 bg-zinc-950 rounded-lg"><div class="text-4xl font-bold">' + state.history.length + '</div><div class="text-xs text-zinc-500">Jours enregistrés</div></div>';
                h += '<div class="text-center p-3 bg-blue-950 rounded-lg border border-blue-900"><div class="text-4xl font-bold text-blue-400">' + state.streak + '</div><div class="text-xs text-zinc-500">Série actuelle</div></div>';
                h += '<div class="text-center p-3 bg-green-950 rounded-lg border border-green-900"><div class="text-4xl font-bold text-green-400">' + state.totalTasksCompleted + '</div><div class="text-xs text-zinc-500">Tâches complétées</div></div>';
                h += '<div class="text-center p-3 bg-yellow-950 rounded-lg border border-yellow-900"><div class="text-4xl font-bold text-yellow-400">' + state.perfectDaysCount + '</div><div class="text-xs text-zinc-500">Journées parfaites</div></div>';
                h += '</div></div>';
                
                // Temps total investi
                let totalMinutes = 0;
                state.history.forEach(day => {
                    state.routines.forEach(r => {
                        r.tasks.forEach(t => {
                            if (day.routines[r.id] && day.routines[r.id][t.id]) {
                                totalMinutes += t.duration;
                            }
                        });
                    });
                });
                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;
                
                h += '<div class="bg-gradient-to-br from-purple-950 to-purple-900 border border-purple-800 rounded-lg p-4 text-center">';
                h += '<span class="material-icons text-purple-300" style="font-size:48px;">timer</span>';
                h += '<div class="text-3xl font-bold text-purple-200 mt-2">' + hours + 'h ' + minutes + 'min</div>';
                h += '<div class="text-sm text-purple-300 mt-1">Temps total investi</div>';
                h += '</div>';
                
                h += '</div>';
                
            } else if (state.view === 'manage') {
                h += '<div class="space-y-4">';
                state.routines.forEach(routine => {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
                    h += '<div class="flex items-center justify-between mb-3"><div class="flex items-center gap-2"><span class="material-icons text-2xl" style="color:#3B82F6;">' + routine.icon + '</span><h3 class="font-bold">' + routine.name + '</h3></div>';
                    h += '<div class="flex gap-2"><button onclick="openEditRoutine(\'' + routine.id + '\')" class="p-2 text-blue-400 hover:bg-blue-950 rounded"><span class="material-icons">edit</span></button>';
                    h += '<button onclick="deleteRoutine(\'' + routine.id + '\')" class="p-2 text-red-400 hover:bg-red-950 rounded"><span class="material-icons">delete</span></button></div></div>';
                    h += '<div class="space-y-2" id="manage-tasks-' + routine.id + '">';
                    routine.tasks.forEach(task => {
                        h += '<div class="flex items-center justify-between p-2 bg-zinc-950 rounded" data-task-id="' + task.id + '"><span class="material-icons text-zinc-600 task-handle" style="cursor:move;font-size:20px;margin-right:8px;">drag_indicator</span><div class="flex items-center gap-2 flex-1"><span class="material-icons">' + task.icon + '</span><span class="task-name-sofia">' + task.name + ' (' + task.duration + ' min)</span></div>';
                        h += '<div class="flex gap-2"><button onclick="openEditTask(\'' + routine.id + '\',\'' + task.id + '\')" class="text-blue-400 hover:bg-blue-950 rounded p-1"><span class="material-icons">edit</span></button>';
                        h += '<button onclick="deleteTask(\'' + routine.id + '\',\'' + task.id + '\')" class="text-red-400 hover:bg-red-950 rounded p-1"><span class="material-icons">delete</span></button></div></div>';
                    });
                    h += '</div><button onclick="openAddTask(\'' + routine.id + '\')" class="w-full mt-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><span class="material-icons mr-2" style="vertical-align:middle;font-size:18px;">add</span>Ajouter une tâche</button></div>';
                });
                h += '<button onclick="addNewRoutine()" class="w-full py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700"><span class="material-icons mr-2" style="vertical-align:middle;">add</span>Nouvelle Routine</button></div>';
                
            } else if (state.view === 'calendar') {
                h += renderCal();
                
            } else if (state.view === 'goals') {
                calcStreak();
                
                // ====== NOUVEAU v0.9b : VUE GOALS AVEC BADGES ======
                h += '<div class="space-y-4">';
                
                // Série
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">local_fire_department</span>Série actuelle</h3><div class="text-center">';
                h += '<div class="text-6xl font-bold text-orange-500 mb-2 streak-pulse">' + state.streak + '</div><div class="text-zinc-400">jour' + (state.streak>1?'s':'') + ' à >75%</div></div></div>';
                
                // Badges
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
                h += '<h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">emoji_events</span>Achievements (' + state.unlockedBadges.length + '/' + BADGES.length + ')</h3>';
                h += '<div class="badge-container">';
                BADGES.forEach(badge => {
                    const unlocked = state.unlockedBadges.includes(badge.id);
                    h += '<div class="badge-item ' + (unlocked ? 'unlocked' : '') + '" onclick="showBadgeDetail(\'' + badge.id + '\')">';
                    h += '<div class="badge-icon">' + badge.icon + '</div>';
                    h += '<div class="badge-name">' + badge.name + '</div>';
                    h += '</div>';
                });
                h += '</div></div>';
                
                h += '<div class="bg-blue-950 border border-blue-900 rounded-lg p-4 text-sm text-blue-200"><span class="material-icons mr-2" style="vertical-align:middle;">stars</span><strong>Objectif :</strong> Compléter au moins 75% de vos tâches chaque jour pour maintenir votre série !</div>';
                h += '</div>';
            }
            
            h += '</div><div style="margin-top:40px;padding-bottom:40px;text-align:center;">';
            h += '<p style="margin-top:30px;color:#555;font-size:14px;line-height:1.8;">App v' + clientVersion + ' • Mod v' + MODULE_REVISION + ' • Accès anticipé<br><a onclick="openPopup(\'aboutPopup\')" style="color:#0d47a1;cursor:pointer;">À propos</a> • <a onclick="openPopup(\'privacyPopup\')" style="color:#0d47a1;cursor:pointer;">Confidentialité</a><br/><span style="color:#161616;">©2025 SanteMentale.org</span></p></div></div>';
            
            app.innerHTML = h;
            
            if (state.view === 'today') {
                document.querySelectorAll('.checkbox-task').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const routineId = this.dataset.routine;
                        const taskId = this.dataset.task;
                        toggleTask(routineId, taskId);
                    });
                });
            } else if (state.view === 'manage') {
                state.routines.forEach(routine => {
                    const el = document.getElementById('manage-tasks-' + routine.id);
                    if (el) {
                        new Sortable(el, {
                            animation: 150,
                            handle: '.task-handle',
                            onEnd: function(evt) {
                                const taskId = evt.item.dataset.taskId;
                                const tasks = routine.tasks;
                                const movedTask = tasks.find(t => t.id === taskId);
                                tasks.splice(tasks.indexOf(movedTask), 1);
                                tasks.splice(evt.newIndex, 0, movedTask);
                                localStorage.setItem('routines', JSON.stringify(state.routines));
                            }
                        });
                    }
                });
            } else if (state.view === 'stats') {
                // ====== NOUVEAU v0.9b : RENDER CHART ======
                renderStatsChart();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            try {
                const savedRoutines = localStorage.getItem('routines');
                if (savedRoutines) {
                    state.routines = JSON.parse(savedRoutines);
                } else {
                    state.routines = initDefaultRoutines();
                    localStorage.setItem('routines', JSON.stringify(state.routines));
                }
                
                state.history = JSON.parse(localStorage.getItem('routineHistory') || '[]');
                
                // ====== NOUVEAU v0.9b : CHARGER XP & BADGES ======
                state.xp = parseInt(localStorage.getItem('routineXP') || '0');
                state.level = parseInt(localStorage.getItem('routineLevel') || '1');
                state.unlockedBadges = JSON.parse(localStorage.getItem('routineBadges') || '[]');
                state.totalTasksCompleted = parseInt(localStorage.getItem('routineTotalTasks') || '0');
                state.perfectDaysCount = parseInt(localStorage.getItem('routinePerfectDays') || '0');
                
                if (!localStorage.getItem('routineGoalDays')) {
                    localStorage.setItem('routineGoalDays', '7');
                }
                
                const today = state.currentDate;
                let dayHistory = state.history.find(h => h.date === today);
                if (dayHistory) {
                    state.routines.forEach(r => {
                        r.tasks.forEach(t => {
                            t.completed = !!(dayHistory.routines[r.id] && dayHistory.routines[r.id][t.id]);
                        });
                    });
                } else {
                    state.routines.forEach(r => r.tasks.forEach(t => t.completed = false));
                }
                
                if (!localStorage.getItem('device_uuid')) {
                    localStorage.setItem('device_uuid', crypto.randomUUID());
                    openPopup('privacyPopup');
                }
                
                let cv = localStorage.getItem('client_version');
                if (!cv) {
                    const up = new URLSearchParams(window.location.search), vfu = up.get('v');
                    cv = vfu || '1.web';
                    localStorage.setItem('client_version', cv);
                }
                
                document.getElementById('clientUUID').textContent = 'Client : ' + localStorage.getItem('device_uuid');
                document.getElementById('appVersion').textContent = 'App : ' + cv;
                
                calcStreak();
                checkBadges(); // ← NOUVEAU : Vérifier badges au démarrage
                render();
            } catch(e) {
                console.error('Erreur:', e);
                document.getElementById('app').innerHTML = '<div style="color:red;padding:20px;">Erreur: ' + e.message + '</div>';
            }
        });
    </script>
</body>
</html>
