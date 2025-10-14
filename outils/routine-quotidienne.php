<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Routines - SanteMentale.org</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Genos:wght@400;500;600;700&family=Sofia+Sans+Condensed:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding-bottom: 40px; }
        .popup { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; display: none; align-items: center; justify-content: center; }
        .popup-content { background: white; color: black; width: 90%; max-width: 500px; padding: 20px; border-radius: 10px; max-height: 80vh; overflow-y: auto; }
        .close-btn { padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .task-input { padding: 8px; width: 100%; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%); color: white; border-radius: 8px; text-decoration: none; }
        .back-btn:hover { transform: translateX(-3px); }
        .icon-option { padding: 10px; border: 2px solid #ccc; border-radius: 5px; cursor: pointer; display: inline-flex; }
        .icon-option:hover { border-color: #3B82F6; background: #f0f9ff; }
        .icon-option.selected { border-color: #3B82F6; background: #dbeafe; }
        .title-genos { font-family: 'Genos', sans-serif; }
        .xp-fill { height: 100%; background: linear-gradient(90deg, #3B82F6 0%, #60A5FA 100%); position: relative; z-index: 1; }
        .xp-fill::after { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%); animation: shimmer 2s infinite; z-index: 2; }
        @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
        .xp-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 12px; font-weight: 700; color: #fff; z-index: 3; }
        .badge-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px; }
        .badge-item { background: #18181b; border: 2px solid #3f3f46; border-radius: 12px; padding: 12px; text-align: center; cursor: pointer; }
        .badge-item.unlocked { border-color: #3B82F6; background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); }
        .badge-icon { font-size: 40px; margin-bottom: 8px; filter: grayscale(100%); opacity: 0.3; }
        .badge-item.unlocked .badge-icon { filter: grayscale(0%); opacity: 1; }
        .badge-name { font-size: 11px; font-weight: 600; margin-top: 4px; color: #71717a; }
        .badge-item.unlocked .badge-name { color: #fff; }
        .achievement-notification { position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: white; padding: 16px 20px; border-radius: 12px; z-index: 3000; display: none; border: 2px solid #3B82F6; }
        .achievement-notification.show { display: block; }
        .help-icon { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: transparent; border: 2px solid #3B82F6; border-radius: 50%; cursor: pointer; font-size: 14px; font-weight: bold; color: #3B82F6; margin-left: 8px; }
        .help-icon:hover { background: #3B82F6; color: white; transform: scale(1.1); }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 5999; display: none; }
        .modal-overlay.show { display: block; }
        .help-modal { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; color: black; padding: 24px; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); z-index: 6000; display: none; max-width: 400px; width: 90%; }
        .help-modal.show { display: block; }
        .help-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .help-modal-header h3 { font-size: 22px; font-weight: bold; color: #3B82F6; margin: 0; }
        .help-close { background: none; border: none; font-size: 24px; color: #999; cursor: pointer; padding: 0; width: 32px; height: 32px; }
        .help-close:hover { background: #f5f5f5; color: #333; }
        .help-option { display: flex; align-items: center; gap: 15px; padding: 16px; background: #f8f9fa; border-radius: 12px; margin-bottom: 12px; cursor: pointer; border: 2px solid transparent; }
        .help-option:hover { background: #e3f2fd; border-color: #3B82F6; transform: translateX(5px); }
        .help-option-icon { font-size: 32px; }
        .help-option-content h4 { font-size: 16px; font-weight: 600; margin: 0 0 4px 0; color: #333; }
        .help-option-content p { font-size: 13px; color: #666; margin: 0; }
        .tutorial-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 5000; display: none; }
        .tutorial-overlay.active { display: block; }
        .tutorial-highlight { position: absolute; border: 3px solid #3B82F6; border-radius: 12px; box-shadow: 0 0 0 9999px rgba(0,0,0,0.85), 0 0 30px rgba(59, 130, 246, 0.8); z-index: 5001; pointer-events: none; display: none; }
        .tutorial-tooltip { position: fixed; background: white; color: black; padding: 20px; border-radius: 12px; max-width: 90%; width: 350px; z-index: 5002; box-shadow: 0 10px 40px rgba(0,0,0,0.3); display: none; }
        .tutorial-tooltip h3 { font-size: 20px; font-weight: bold; margin: 0 0 10px 0; color: #3B82F6; }
        .tutorial-tooltip p { font-size: 14px; line-height: 1.6; margin: 0 0 15px 0; }
        .tutorial-buttons { display: flex; gap: 10px; }
        .tutorial-btn { padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .tutorial-btn.skip { background: #e5e5e5; color: #666; }
        .tutorial-btn.next { background: #3B82F6; color: white; flex: 1; }
        .chart-container { position: relative; height: 300px; margin-top: 20px; }
    </style>
</head>
<body>
    <div id="app">Chargement...</div>
    <div id="aboutPopup" class="popup"><div class="popup-content"><p>©2025 SanteMentale.org</p><p>API : 0.dev</p><p id="appVersion"></p><p id="clientUUID"></p><button class="close-btn" onclick="closePopup('aboutPopup')">Fermer</button></div></div>
    <div id="privacyPopup" class="popup"><div class="popup-content"><p>SanteMentale.org respecte votre vie privée. Aucune donnée n'est collectée. Tout est stocké localement.</p><button class="close-btn" onclick="closePopup('privacyPopup')">Fermer</button></div></div>
    <div id="editRoutinePopup" class="popup"><div class="popup-content" style="text-align:left;"><h3 style="font-weight:bold;margin-bottom:15px;text-align:center;">Modifier routine</h3><input type="text" id="routineNameInput" class="task-input" placeholder="Nom"><label style="display:block;margin:10px 0 5px;font-weight:bold;">Icône</label><div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="iconPicker"></div><button class="close-btn" onclick="saveRoutineEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button><button class="close-btn" onclick="closePopup('editRoutinePopup')" style="width:100%;">Annuler</button></div></div>
    <div id="addTaskPopup" class="popup"><div class="popup-content" style="text-align:left;"><h3 style="font-weight:bold;margin-bottom:15px;text-align:center;">Ajouter tâche</h3><input type="text" id="taskNameInput" class="task-input" placeholder="Nom"><div style="display:flex;flex-wrap:wrap;gap:10px;margin:15px 0;" id="taskIconPicker"></div><label style="display:block;margin-bottom:5px;font-weight:bold;">Durée (min)</label><input type="number" id="taskDurationInput" class="task-input" placeholder="15" min="0"><button class="close-btn" onclick="saveNewTask()" style="width:100%;background:#3B82F6;color:white;">Ajouter</button><button class="close-btn" onclick="closePopup('addTaskPopup')" style="width:100%;">Annuler</button></div></div>
    <div id="editTaskPopup" class="popup"><div class="popup-content" style="text-align:left;"><h3 style="font-weight:bold;margin-bottom:15px;text-align:center;">Modifier tâche</h3><input type="text" id="editTaskNameInput" class="task-input"><label style="display:block;margin:10px 0 5px;font-weight:bold;">Icône</label><div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="editTaskIconPicker"></div><label style="display:block;margin-bottom:5px;font-weight:bold;">Durée (min)</label><input type="number" id="editTaskDurationInput" class="task-input" min="0"><button class="close-btn" onclick="saveTaskEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button><button class="close-btn" onclick="closePopup('editTaskPopup')" style="width:100%;">Annuler</button></div></div>
    <div id="badgeDetailPopup" class="popup"><div class="popup-content"><div id="badgeDetailContent"></div><button class="close-btn" onclick="closePopup('badgeDetailPopup')" style="width:100%;margin-top:15px;">Fermer</button></div></div>
    <div id="whatsNewPopup" class="popup"><div class="popup-content"><h2 style="font-size:28px;font-weight:bold;color:#3B82F6;margin-bottom:20px;">Quoi de neuf v0.10</h2><div style="text-align:left;"><div style="background:#f0f9ff;padding:15px;border-radius:10px;border-left:4px solid #3B82F6;margin-bottom:15px;"><div style="font-size:18px;font-weight:bold;color:#1e40af;margin-bottom:5px;">📊 Stats avancées</div><p style="color:#666;margin:0;">Graphiques et temps investi</p></div><div style="background:#f0f9ff;padding:15px;border-radius:10px;border-left:4px solid #3B82F6;margin-bottom:15px;"><div style="font-size:18px;font-weight:bold;color:#1e40af;margin-bottom:5px;">🏆 Rewards</div><p style="color:#666;margin:0;">Badges et niveaux XP</p></div><div style="background:#f0f9ff;padding:15px;border-radius:10px;border-left:4px solid #3B82F6;margin-bottom:15px;"><div style="font-size:18px;font-weight:bold;color:#1e40af;margin-bottom:5px;">🎊 Célébrations</div><p style="color:#666;margin:0;">Confettis à 100%</p></div></div><div style="display:flex;gap:10px;margin-top:20px;"><button class="close-btn" onclick="closePopup('whatsNewPopup')" style="flex:1;background:#e5e5e5;">Plus tard</button><button class="close-btn" onclick="startTutorial();closePopup('whatsNewPopup');" style="flex:1;background:#3B82F6;color:white;">Découvrir</button></div></div></div>
    <div id="modalOverlay" class="modal-overlay" onclick="closeHelpModal()"></div>
    <div id="helpModal" class="help-modal"><div class="help-modal-header"><h3>Aide</h3><button class="help-close" onclick="closeHelpModal()">×</button></div><div class="help-option" onclick="startTutorial();closeHelpModal();"><div class="help-option-icon">🎓</div><div class="help-option-content"><h4>Tutoriel</h4><p>Guide interactif</p></div></div><div class="help-option" onclick="openPopup('whatsNewPopup');closeHelpModal();"><div class="help-option-icon">✨</div><div class="help-option-content"><h4>Nouveautés</h4><p>Découvrez v0.10</p></div></div></div>
    <div id="tutorialOverlay" class="tutorial-overlay"></div>
    <div id="tutorialHighlight" class="tutorial-highlight"></div>
    <div id="tutorialTooltip" class="tutorial-tooltip"></div>
    <div id="achievementNotification" class="achievement-notification"><div style="display:flex;align-items:center;gap:12px;"><span class="material-icons" style="font-size:40px;">emoji_events</span><div><div style="font-weight:bold;">Achievement!</div><div id="achievementText" style="font-size:14px;margin-top:4px;"></div></div></div></div>

    <script>
        const MODULE_REVISION = '0.10';
        const ICONS = ['wb_sunny', 'nightlight', 'favorite', 'local_cafe', 'book', 'fitness_center', 'water_drop', 'music_note', 'brush', 'phone', 'email', 'shopping_cart', 'nature', 'laptop', 'sports_esports', 'camera_alt', 'edit', 'palette', 'home', 'work', 'restaurant', 'bed', 'self_improvement', 'spa', 'pets', 'alarm', 'directions_run', 'school', 'local_dining', 'psychology'];
        const TASK_ICONS = ['self_improvement', 'spa', 'favorite_border', 'local_cafe', 'restaurant', 'water_drop', 'fitness_center', 'directions_run', 'bedtime', 'alarm', 'book', 'edit', 'music_note', 'brush', 'palette', 'shower', 'clean_hands', 'medication', 'healing', 'psychology', 'wb_sunny', 'nightlight', 'nature', 'park', 'pets', 'phone', 'people', 'home', 'work', 'school'];
        const BADGES = [
            { id: 'first_step', name: 'Premier Pas', icon: '🎯', req: 'tasks', threshold: 1 },
            { id: 'dedicated', name: 'Dévoué', icon: '💪', req: 'tasks', threshold: 50 },
            { id: 'champion', name: 'Champion', icon: '🏆', req: 'tasks', threshold: 200 },
            { id: 'legend', name: 'Légende', icon: '⭐', req: 'tasks', threshold: 500 },
            { id: 'week_streak', name: 'Hebdomadaire', icon: '🔥', req: 'streak', threshold: 7 },
            { id: 'month_streak', name: 'Mensuel', icon: '💎', req: 'streak', threshold: 30 },
            { id: 'perfect_day', name: 'Journée Parfaite', icon: '✨', req: 'perfect', threshold: 1 },
            { id: 'early_bird', name: 'Lève-tôt', icon: '🌅', req: 'morning', threshold: 1 },
            { id: 'night_owl', name: 'Couche-tard', icon: '🌙', req: 'evening', threshold: 1 },
            { id: 'organizer', name: 'Organisateur', icon: '📋', req: 'custom', threshold: 3 },
            { id: 'secret_konami', name: 'Récompense mystère', icon: '❓', req: 'secret', threshold: 1 }
        ];
        const XP_PER_TASK = 10, XP_PER_LEVEL = 100;
        const TUTORIAL_STEPS = [
            { target: '.xp-bar', title: '⭐ XP & Niveaux', text: '+10 XP par tâche!' },
            { target: 'button[onclick*="stats"]', title: '📊 Statistiques', text: 'Graphiques et temps.' },
            { target: 'button[onclick*="goals"]', title: '🏆 Achievements', text: '11 badges!' },
            { target: '.help-icon', title: '❓ Aide', text: 'Tutoriel et nouveautés.' }
        ];
        const KONAMI = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];

        let state = { routines: [], history: [], view: 'today', currentDate: new Date().toLocaleDateString('fr-FR'), calendarDate: new Date(), streak: 0, xp: 0, level: 1, unlockedBadges: [], totalTasksCompleted: 0, perfectDaysCount: 0, statsChart: null, konamiCode: [], selectedRoutineIcon: 'wb_sunny', selectedTaskIcon: 'self_improvement', selectedEditTaskIcon: 'self_improvement', editingRoutineId: null, addingTaskToRoutine: null, editingTaskRoutineId: null, editingTaskId: null };

        function initRoutines() {
            return [
                { id: 'morning', name: 'Routine Matin', icon: 'wb_sunny', tasks: [{ id: 't1', name: 'Se réveiller', icon: 'bedtime', duration: 0, completed: false }, { id: 't2', name: 'Eau', icon: 'water_drop', duration: 2, completed: false }, { id: 't3', name: 'Petit-déj', icon: 'local_cafe', duration: 20, completed: false }, { id: 't4', name: 'Hygiène', icon: 'shower', duration: 15, completed: false }] },
                { id: 'day', name: 'Routine Journée', icon: 'laptop', tasks: [{ id: 't5', name: 'Planifier', icon: 'edit', duration: 10, completed: false }, { id: 't6', name: 'Travail', icon: 'laptop', duration: 240, completed: false }, { id: 't7', name: 'Déjeuner', icon: 'restaurant', duration: 45, completed: false }, { id: 't8', name: 'Sport', icon: 'fitness_center', duration: 30, completed: false }] },
                { id: 'evening', name: 'Routine Soir', icon: 'nightlight', tasks: [{ id: 't9', name: 'Dîner', icon: 'restaurant', duration: 30, completed: false }, { id: 't10', name: 'Détente', icon: 'book', duration: 60, completed: false }, { id: 't11', name: 'Préparer demain', icon: 'edit', duration: 15, completed: false }, { id: 't12', name: 'Dormir', icon: 'bedtime', duration: 0, completed: false }] }
            ];
        }

        function openPopup(id) { document.getElementById(id).style.display = 'flex'; }
        function closePopup(id) { document.getElementById(id).style.display = 'none'; }
        function openHelpModal() { document.getElementById('modalOverlay').classList.add('show'); document.getElementById('helpModal').classList.add('show'); }
        function closeHelpModal() { document.getElementById('modalOverlay').classList.remove('show'); document.getElementById('helpModal').classList.remove('show'); }

        function confetti_launch() {
            const count = 200, defaults = { origin: { y: 0.7 } };
            function fire(ratio, opts) { confetti(Object.assign({}, defaults, opts, { particleCount: Math.floor(count * ratio) })); }
            fire(0.25, { spread: 26, startVelocity: 55 });
            fire(0.2, { spread: 60 });
            fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
            fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
            fire(0.1, { spread: 120, startVelocity: 45 });
        }

        function gainXP(amt) {
            state.xp += amt;
            const old = state.level;
            state.level = Math.floor(state.xp / XP_PER_LEVEL) + 1;
            localStorage.setItem('routineXP', state.xp);
            localStorage.setItem('routineLevel', state.level);
            if (state.level > old) { showNotif('Niveau ' + state.level + ' 🎉'); confetti_launch(); }
        }

        function checkBadges() {
            BADGES.forEach(b => {
                if (state.unlockedBadges.includes(b.id)) return;
                let unlock = false;
                if (b.req === 'tasks') unlock = state.totalTasksCompleted >= b.threshold;
                else if (b.req === 'streak') unlock = state.streak >= b.threshold;
                else if (b.req === 'perfect') unlock = state.perfectDaysCount >= b.threshold;
                else if (b.req === 'custom') unlock = state.routines.filter(r => !['morning', 'day', 'evening'].includes(r.id)).length >= b.threshold;
                
                if (unlock) {
                    state.unlockedBadges.push(b.id);
                    localStorage.setItem('routineBadges', JSON.stringify(state.unlockedBadges));
                    const nm = b.id === 'secret_konami' ? '🎮 Konami Master' : b.icon + ' ' + b.name;
                    showNotif(nm + ' débloqué!');
                    confetti_launch();
                }
            });
        }

        function showNotif(txt) {
            const n = document.getElementById('achievementNotification');
            document.getElementById('achievementText').textContent = txt;
            n.classList.add('show');
            setTimeout(() => n.classList.remove('show'), 4000);
        }

        function toggleTask(rid, tid) {
            const r = state.routines.find(x => x.id === rid);
            const t = r.tasks.find(x => x.id === tid);
            t.completed = !t.completed;
            
            const today = state.currentDate;
            let dayH = state.history.find(h => h.date === today);
            if (!dayH) { dayH = { date: today, routines: {} }; state.history.push(dayH); }
            if (!dayH.routines[rid]) dayH.routines[rid] = {};
            dayH.routines[rid][tid] = t.completed;
            
            if (t.completed) { gainXP(XP_PER_TASK); state.totalTasksCompleted++; }
            else { state.xp = Math.max(0, state.xp - XP_PER_TASK); state.totalTasksCompleted = Math.max(0, state.totalTasksCompleted - 1); state.level = Math.floor(state.xp / XP_PER_LEVEL) + 1; }
            localStorage.setItem('routineTotalTasks', state.totalTasksCompleted);
            
            const comp = getTodayCompletion();
            if (comp === 100) { state.perfectDaysCount++; confetti_launch(); showNotif('🎊 Journée parfaite!'); }
            
            localStorage.setItem('routines', JSON.stringify(state.routines));
            localStorage.setItem('routineHistory', JSON.stringify(state.history));
            calcStreak();
            checkBadges();
            render();
        }

        function openEditRoutine(rid) {
            state.editingRoutineId = rid;
            const r = state.routines.find(x => x.id === rid);
            document.getElementById('routineNameInput').value = r.name;
            state.selectedRoutineIcon = r.icon;
            renderIconPicker();
            openPopup('editRoutinePopup');
        }

        function saveRoutineEdit() {
            const r = state.routines.find(x => x.id === state.editingRoutineId);
            r.name = document.getElementById('routineNameInput').value.trim() || r.name;
            r.icon = state.selectedRoutineIcon;
            localStorage.setItem('routines', JSON.stringify(state.routines));
            closePopup('editRoutinePopup');
            checkBadges();
            render();
        }

        function renderIconPicker() {
            const p = document.getElementById('iconPicker');
            p.innerHTML = ICONS.map(i => '<div class="icon-option ' + (state.selectedRoutineIcon === i ? 'selected' : '') + '" onclick="state.selectedRoutineIcon=\'' + i + '\';renderIconPicker();"><span class="material-icons" style="font-size:24px;color:black;">' + i + '</span></div>').join('');
        }

        function openAddTask(rid) {
            state.addingTaskToRoutine = rid;
            state.selectedTaskIcon = 'self_improvement';
            document.getElementById('taskNameInput').value = '';
            document.getElementById('taskDurationInput').value = '';
            renderTaskIconPicker();
            openPopup('addTaskPopup');
        }

        function renderTaskIconPicker() {
            const p = document.getElementById('taskIconPicker');
            p.innerHTML = TASK_ICONS.map(i => '<div class="icon-option ' + (state.selectedTaskIcon === i ? 'selected' : '') + '" onclick="state.selectedTaskIcon=\'' + i + '\';renderTaskIconPicker();"><span class="material-icons" style="font-size:24px;color:black;">' + i + '</span></div>').join('');
        }

        function saveNewTask() {
            const n = document.getElementById('taskNameInput').value.trim();
            if (!n) return;
            const d = parseInt(document.getElementById('taskDurationInput').value) || 15;
            const r = state.routines.find(x => x.id === state.addingTaskToRoutine);
            r.tasks.push({ id: 't' + Date.now(), name: n, icon: state.selectedTaskIcon, duration: d, completed: false });
            localStorage.setItem('routines', JSON.stringify(state.routines));
            closePopup('addTaskPopup');
            render();
        }

        function openEditTask(rid, tid) {
            state.editingTaskRoutineId = rid;
            state.editingTaskId = tid;
            const r = state.routines.find(x => x.id === rid);
            const t = r.tasks.find(x => x.id === tid);
            document.getElementById('editTaskNameInput').value = t.name;
            document.getElementById('editTaskDurationInput').value = t.duration;
            state.selectedEditTaskIcon = t.icon;
            renderEditTaskIconPicker();
            openPopup('editTaskPopup');
        }

        function renderEditTaskIconPicker() {
            const p = document.getElementById('editTaskIconPicker');
            p.innerHTML = TASK_ICONS.map(i => '<div class="icon-option ' + (state.selectedEditTaskIcon === i ? 'selected' : '') + '" onclick="state.selectedEditTaskIcon=\'' + i + '\';renderEditTaskIconPicker();"><span class="material-icons" style="font-size:24px;color:black;">' + i + '</span></div>').join('');
        }

        function saveTaskEdit() {
            const r = state.routines.find(x => x.id === state.editingTaskRoutineId);
            const t = r.tasks.find(x => x.id === state.editingTaskId);
            t.name = document.getElementById('editTaskNameInput').value.trim() || t.name;
            t.duration = parseInt(document.getElementById('editTaskDurationInput').value) || 15;
            t.icon = state.selectedEditTaskIcon;
            localStorage.setItem('routines', JSON.stringify(state.routines));
            closePopup('editTaskPopup');
            render();
        }

        function deleteTask(rid, tid) {
            if (!confirm('Supprimer?')) return;
            const r = state.routines.find(x => x.id === rid);
            r.tasks = r.tasks.filter(t => t.id !== tid);
            localStorage.setItem('routines', JSON.stringify(state.routines));
            render();
        }

        function deleteRoutine(rid) {
            if (!confirm('Supprimer?')) return;
            state.routines = state.routines.filter(r => r.id !== rid);
            localStorage.setItem('routines', JSON.stringify(state.routines));
            render();
        }

        function addNewRoutine() {
            const n = prompt('Nom:');
            if (!n) return;
            state.routines.push({ id: 'r' + Date.now(), name: n, icon: 'wb_sunny', tasks: [] });
            localStorage.setItem('routines', JSON.stringify(state.routines));
            checkBadges();
            render();
        }

        function getTodayCompletion() {
            const today = state.history.find(h => h.date === state.currentDate);
            if (!today) return 0;
            let total = 0, completed = 0;
            state.routines.forEach(r => { r.tasks.forEach(t => { total++; if (today.routines[r.id] && today.routines[r.id][t.id]) completed++; }); });
            return total > 0 ? Math.round((completed / total) * 100) : 0;
        }

        function getRoutineCompletion(rid) {
            const r = state.routines.find(x => x.id === rid);
            if (!r || r.tasks.length === 0) return 0;
            const c = r.tasks.filter(t => t.completed).length;
            return Math.round((c / r.tasks.length) * 100);
        }

        function calcStreak() {
            let s = 0;
            const today = new Date();
            for (let i = 0; i < 365; i++) {
                const d = new Date(today);
                d.setDate(today.getDate() - i);
                const ds = d.toLocaleDateString('fr-FR');
                const dayD = state.history.find(h => h.date === ds);
                if (dayD) {
                    let total = 0, completed = 0;
                    state.routines.forEach(r => { r.tasks.forEach(t => { total++; if (dayD.routines[r.id] && dayD.routines[r.id][t.id]) completed++; }); });
                    if (total > 0 && completed / total >= 0.75) s++;
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
            let h = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><div class="flex justify-between mb-4"><button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()-1);render();" class="text-blue-400"><span class="material-icons">chevron_left</span></button><h3 class="font-bold">' + first.toLocaleDateString('fr-FR', {month:'long',year:'numeric'}) + '</h3><button onclick="state.calendarDate.setMonth(state.calendarDate.getMonth()+1);render();" class="text-blue-400"><span class="material-icons">chevron_right</span></button></div><div class="grid grid-cols-7 gap-1 text-center text-xs mb-2 text-zinc-500"><div>D</div><div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div></div><div class="grid grid-cols-7 gap-1">';
            for (let i = 0; i < start; i++) h += '<div></div>';
            for (let d = 1; d <= days; d++) {
                const dt = new Date(y, m, d), ds = dt.toLocaleDateString('fr-FR');
                const dayD = state.history.find(h => h.date === ds);
                let comp = 0;
                if (dayD) {
                    let total = 0, completed = 0;
                    state.routines.forEach(r => { r.tasks.forEach(t => { total++; if (dayD.routines[r.id] && dayD.routines[r.id][t.id]) completed++; }); });
                    comp = total > 0 ? completed / total : 0;
                }
                let bg = '#18181b', txt = '#a1a1aa';
                if (comp > 0) { if (comp >= 0.76) { bg = '#10B981'; txt = '#fff'; } else if (comp >= 0.51) { bg = '#F59E0B'; txt = '#fff'; } else if (comp >= 0.26) { bg = '#EF4444'; txt = '#fff'; } else { bg = '#3B82F6'; txt = '#fff'; } }
                h += '<div class="calendar-day" style="background:' + bg + ';color:' + txt + ';">' + d + '</div>';
            }
            h += '</div></div>';
            return h;
        }

        function showBadgeDetail(bid) {
            const b = BADGES.find(x => x.id === bid);
            const unlocked = state.unlockedBadges.includes(bid);
            let nm = b.name, icon = b.icon, desc = b.req === 'tasks' ? b.threshold + ' tâches' : b.threshold + ' jours';
            if (bid === 'secret_konami' && !unlocked) { nm = 'Récompense mystère'; icon = '❓'; }
            else if (bid === 'secret_konami' && unlocked) { nm = 'Konami Master'; icon = '🎮'; desc = 'Code découvert!'; }
            let prog = 0;
            if (b.req === 'tasks') prog = Math.min(100, Math.round((state.totalTasksCompleted / b.threshold) * 100));
            else if (b.req === 'streak') prog = Math.min(100, Math.round((state.streak / b.threshold) * 100));
            else if (b.req === 'perfect') prog = Math.min(100, Math.round((state.perfectDaysCount / b.threshold) * 100));
            let html = '<div style="text-align:center;"><div style="font-size:60px;margin-bottom:15px;' + (unlocked ? '' : 'filter:grayscale(100%);opacity:0.3;') + '">' + icon + '</div><h3 style="font-size:24px;font-weight:bold;margin-bottom:10px;">' + nm + '</h3><p style="color:#666;margin-bottom:15px;">' + desc + '</p>';
            if (unlocked) html += '<div style="background:#10B981;color:white;padding:8px 16px;border-radius:8px;display:inline-block;">✓ Débloqué</div>';
            else if (bid !== 'secret_konami') html += '<div style="margin-top:15px;"><div style="background:#eee;height:20px;border-radius:10px;"><div style="background:#3B82F6;height:100%;width:' + prog + '%;"></div></div><p style="margin-top:8px;font-size:14px;color:#666;">Progression: ' + prog + '%</p></div>';
            html += '</div>';
            document.getElementById('badgeDetailContent').innerHTML = html;
            openPopup('badgeDetailPopup');
        }

        function renderChart() {
            setTimeout(() => {
                const c = document.getElementById('statsChart');
                if (!c) return;
                const ctx = c.getContext('2d');
                if (state.statsChart) state.statsChart.destroy();
                const labels = [], data = [];
                const today = new Date();
                for (let i = 6; i >= 0; i--) {
                    const d = new Date(today);
                    d.setDate(today.getDate() - i);
                    labels.push(d.toLocaleDateString('fr-FR', { weekday: 'short' }));
                    const dayD = state.history.find(h => h.date === d.toLocaleDateString('fr-FR'));
                    if (dayD) {
                        let total = 0, completed = 0;
                        state.routines.forEach(r => { r.tasks.forEach(t => { total++; if (dayD.routines[r.id] && dayD.routines[r.id][t.id]) completed++; }); });
                        data.push(total > 0 ? Math.round((completed / total) * 100) : 0);
                    } else data.push(0);
                }
                state.statsChart = new Chart(ctx, { type: 'line', data: { labels: labels, datasets: [{ label: 'Progression', data: data, borderColor: '#3B82F6', backgroundColor: 'rgba(59, 130, 246, 0.1)', tension: 0.4, fill: true, pointRadius: 5 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100, ticks: { color: '#71717a' }, grid: { color: '#27272a' } }, x: { ticks: { color: '#71717a' }, grid: { color: '#27272a' } } } } });
            }, 100);
        }

        function startTutorial() {
            state.view = 'today';
            render();
            setTimeout(() => showTutStep(0), 500);
        }

        function showTutStep(step) {
            if (step >= TUTORIAL_STEPS.length) return endTutorial();
            const data = TUTORIAL_STEPS[step];
            const el = document.querySelector(data.target);
            if (!el) return showTutStep(step + 1);
            const overlay = document.getElementById('tutorialOverlay');
            const highlight = document.getElementById('tutorialHighlight');
            const tooltip = document.getElementById('tutorialTooltip');
            overlay.classList.add('active');
            const rect = el.getBoundingClientRect();
            highlight.style.display = 'block';
            highlight.style.top = (rect.top - 10) + 'px';
            highlight.style.left = (rect.left - 10) + 'px';
            highlight.style.width = (rect.width + 20) + 'px';
            highlight.style.height = (rect.height + 20) + 'px';
            tooltip.innerHTML = '<h3>' + data.title + '</h3><p>' + data.text + '</p><div class="tutorial-buttons"><button class="tutorial-btn skip" onclick="endTutorial()">Passer</button><button class="tutorial-btn next" onclick="showTutStep(' + (step + 1) + ')">' + (step === TUTORIAL_STEPS.length - 1 ? 'Terminer' : 'Suivant') + '</button></div>';
            tooltip.style.display = 'block';
            let top = rect.bottom + 20, left = Math.max(10, Math.min(window.innerWidth - 360, rect.left));
            tooltip.style.top = top + 'px';
            tooltip.style.left = left + 'px';
        }

        function endTutorial() {
            document.getElementById('tutorialOverlay').classList.remove('active');
            document.getElementById('tutorialHighlight').style.display = 'none';
            document.getElementById('tutorialTooltip').style.display = 'none';
        }

        document.addEventListener('keydown', (e) => {
            state.konamiCode.push(e.key);
            if (state.konamiCode.length > KONAMI.length) state.konamiCode.shift();
            if (JSON.stringify(state.konamiCode) === JSON.stringify(KONAMI)) {
                if (!state.unlockedBadges.includes('secret_konami')) {
                    state.unlockedBadges.push('secret_konami');
                    localStorage.setItem('routineBadges', JSON.stringify(state.unlockedBadges));
                    showNotif('🎮 Konami Master!');
                    confetti_launch();
                    render();
                }
                state.konamiCode = [];
            }
        });

        function render() {
            const app = document.getElementById('app');
            const cv = localStorage.getItem('client_version') || '1.web';
            let h = '<div class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-10"><div class="max-w-2xl mx-auto px-4 py-4 flex items-center"><a href="/v1/outils/?v=' + cv + '" class="back-btn"><span class="material-icons">arrow_back</span></a><h1 class="text-3xl font-bold title-genos">Routines<span class="help-icon" onclick="openHelpModal()" title="Aide">?</span></h1></div></div>';
            h += '<div class="bg-zinc-900 border-b border-zinc-800"><div class="max-w-2xl mx-auto px-4"><div class="flex gap-1 justify-around">';
            h += '<button onclick="state.view=\'today\';render();" class="py-4 px-3 ' + (state.view==='today'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons">check_box</span></button>';
            h += '<button onclick="state.view=\'stats\';render();" class="py-4 px-3 ' + (state.view==='stats'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons">bar_chart</span></button>';
            h += '<button onclick="state.view=\'manage\';render();" class="py-4 px-3 ' + (state.view==='manage'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons">settings</span></button>';
            h += '<button onclick="state.view=\'calendar\';render();" class="py-4 px-3 ' + (state.view==='calendar'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons">calendar_month</span></button>';
            h += '<button onclick="state.view=\'goals\';render();" class="py-4 px-3 ' + (state.view==='goals'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons">emoji_events</span></button>';
            h += '</div></div></div><div class="max-w-2xl mx-auto px-4 py-6">';
            const xp = (state.xp % XP_PER_LEVEL), xpPct = (xp / XP_PER_LEVEL) * 100;
            h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4 xp-bar"><div class="flex justify-between mb-2"><span>Niveau ' + state.level + '</span><span class="text-sm text-zinc-400">' + xp + '/' + XP_PER_LEVEL + ' XP</span></div><div style="position:relative;height:24px;background:#18181b;border-radius:12px;border:2px solid #3f3f46;overflow:hidden;"><div class="xp-fill" style="width:' + xpPct + '%"></div><div class="xp-text">' + Math.round(xpPct) + '%</div></div></div>';
            
            if (state.view === 'today') {
                const comp = getTodayCompletion();
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4"><div class="flex justify-between mb-2"><span>Aujourd\'hui</span><span class="text-blue-400 font-bold">' + comp + '%</span></div><div style="width:100%;height:12px;background:#18181b;border-radius:6px;overflow:hidden;"><div style="height:100%;background:#3B82F6;width:' + comp + '%;"></div></div></div>';
                state.routines.forEach(r => {
                    const rc = getRoutineCompletion(r.id);
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4"><div class="flex justify-between mb-3"><div class="flex items-center gap-2"><span class="material-icons">' + r.icon + '</span><h3 class="font-bold">' + r.name + '</h3></div><span class="text-sm text-zinc-500">' + rc + '%</span></div><div class="space-y-2">';
                    r.tasks.forEach(t => {
                        h += '<div class="flex items-center gap-3 p-3 bg-zinc-950 rounded-lg"><label style="user-select:none;"><input type="checkbox" ' + (t.completed ? 'checked' : '') + ' class="w-5 h-5 checkbox-task" data-routine="' + r.id + '" data-task="' + t.id + '"></label><span class="material-icons">' + t.icon + '</span><div class="flex-1"><div class="font-semibold ' + (t.completed ? 'line-through text-zinc-500' : '') + '">' + t.name + '</div><div class="text-xs text-zinc-500">' + t.duration + ' min&nbsp;&nbsp;&nbsp;<span style="color:#6b7280;">+' + XP_PER_TASK + ' XP</span></div></div></div>';
                    });
                    h += '</div></div>';
                });
            } else if (state.view === 'stats') {
                calcStreak();
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4"><h3 class="font-bold mb-3">7 derniers jours</h3><div class="chart-container"><canvas id="statsChart"></canvas></div></div>';
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4"><div class="grid grid-cols-2 gap-3"><div class="text-center p-3 bg-zinc-950 rounded"><div class="text-3xl font-bold">' + state.history.length + '</div><div class="text-xs text-zinc-500">Jours</div></div><div class="text-center p-3 bg-blue-950 rounded"><div class="text-3xl font-bold text-blue-400">' + state.streak + '</div><div class="text-xs text-zinc-500">Série</div></div><div class="text-center p-3 bg-green-950 rounded"><div class="text-3xl font-bold text-green-400">' + state.totalTasksCompleted + '</div><div class="text-xs text-zinc-500">Tâches</div></div><div class="text-center p-3 bg-yellow-950 rounded"><div class="text-3xl font-bold text-yellow-400">' + state.perfectDaysCount + '</div><div class="text-xs text-zinc-500">100%</div></div></div></div>';
                let total = 0;
                state.history.forEach(day => { state.routines.forEach(r => { r.tasks.forEach(t => { if (day.routines[r.id] && day.routines[r.id][t.id]) total += t.duration; }); }); });
                const hrs = Math.floor(total / 60), mins = total % 60;
                h += '<div class="bg-purple-950 border border-purple-800 rounded-lg p-4 text-center"><span class="material-icons text-purple-300" style="font-size:48px;">timer</span><div class="text-3xl font-bold text-purple-200 mt-2">' + hrs + 'h ' + mins + 'min</div><div class="text-sm text-purple-300 mt-1">Durée cumulée des tâches</div></div>';
            } else if (state.view === 'manage') {
                h += '<div class="space-y-4">';
                state.routines.forEach(r => {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><div class="flex justify-between mb-3"><div class="flex items-center gap-2"><span class="material-icons">' + r.icon + '</span><h3 class="font-bold">' + r.name + '</h3></div><div><button onclick="openEditRoutine(\'' + r.id + '\')" class="p-2 text-blue-400"><span class="material-icons">edit</span></button><button onclick="deleteRoutine(\'' + r.id + '\')" class="p-2 text-red-400"><span class="material-icons">delete</span></button></div></div><div class="space-y-2">';
                    r.tasks.forEach(t => {
                        h += '<div class="flex justify-between p-2 bg-zinc-950 rounded"><div class="flex items-center gap-2"><span class="material-icons">' + t.icon + '</span><span>' + t.name + '</span></div><div><button onclick="openEditTask(\'' + r.id + '\',\'' + t.id + '\')" class="text-blue-400"><span class="material-icons">edit</span></button><button onclick="deleteTask(\'' + r.id + '\',\'' + t.id + '\')" class="text-red-400"><span class="material-icons">delete</span></button></div></div>';
                    });
                    h += '</div><button onclick="openAddTask(\'' + r.id + '\')" class="w-full mt-3 py-2 bg-blue-600 rounded text-white">+ Tâche</button></div>';
                });
                h += '<button onclick="addNewRoutine()" class="w-full py-3 bg-green-600 text-white font-bold rounded">+ Routine</button></div>';
            } else if (state.view === 'calendar') h += renderCal();
            else if (state.view === 'goals') {
                calcStreak();
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4"><h3 class="font-bold mb-3">Série: <span class="text-5xl font-bold text-orange-500">' + state.streak + '</span></h3></div>';
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3">Achievements (' + state.unlockedBadges.length + '/' + BADGES.length + ')</h3><div class="badge-container">';
                BADGES.forEach(b => {
                    const u = state.unlockedBadges.includes(b.id);
                    let icon = b.icon;
                    if (b.id === 'secret_konami' && !u) icon = '❓';
                    else if (b.id === 'secret_konami' && u) icon = '🎮';
                    h += '<div class="badge-item ' + (u ? 'unlocked' : '') + '" onclick="showBadgeDetail(\'' + b.id + '\')"><div class="badge-icon">' + icon + '</div><div class="badge-name">' + (b.id === 'secret_konami' && !u ? 'Récompense mystère' : b.name) + '</div></div>';
                });
                h += '</div></div>';
            }
            
            h += '</div>';
            app.innerHTML = h;
            
            if (state.view === 'today') {
                document.querySelectorAll('.checkbox-task').forEach(cb => {
                    cb.addEventListener('change', function() { toggleTask(this.dataset.routine, this.dataset.task); });
                });
            } else if (state.view === 'stats') renderChart();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const sr = localStorage.getItem('routines');
            state.routines = sr ? JSON.parse(sr) : initRoutines();
            state.history = JSON.parse(localStorage.getItem('routineHistory') || '[]');
            state.xp = parseInt(localStorage.getItem('routineXP') || '0');
            state.level = Math.floor(state.xp / XP_PER_LEVEL) + 1;
            state.unlockedBadges = JSON.parse(localStorage.getItem('routineBadges') || '[]');
            state.totalTasksCompleted = parseInt(localStorage.getItem('routineTotalTasks') || '0');
            state.perfectDaysCount = parseInt(localStorage.getItem('routinePerfectDays') || '0');
            
            const lastV = localStorage.getItem('routineLastSeenVersion');
            if (lastV !== MODULE_REVISION) { setTimeout(() => { openPopup('whatsNewPopup'); localStorage.setItem('routineLastSeenVersion', MODULE_REVISION); }, 1000); }
            
            let dh = state.history.find(h => h.date === state.currentDate);
            if (dh) { state.routines.forEach(r => { r.tasks.forEach(t => { t.completed = !!(dh.routines[r.id] && dh.routines[r.id][t.id]); }); }); }
            else { state.routines.forEach(r => r.tasks.forEach(t => t.completed = false)); }
            
            if (!localStorage.getItem('device_uuid')) { localStorage.setItem('device_uuid', crypto.randomUUID()); openPopup('privacyPopup'); }
            
            let cv = localStorage.getItem('client_version');
            if (!cv) { const p = new URLSearchParams(window.location.search); cv = p.get('v') || '1.web'; localStorage.setItem('client_version', cv); }
            
            document.getElementById('clientUUID').textContent = 'Client : ' + localStorage.getItem('device_uuid');
            document.getElementById('appVersion').textContent = 'App : ' + cv;
            
            calcStreak();
            checkBadges();
            render();
        });
    </script>
</body>
</html>
