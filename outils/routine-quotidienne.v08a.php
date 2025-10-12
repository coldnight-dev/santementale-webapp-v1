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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <style>
        body { background-color: #000; color: #fff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding-bottom: 40px; }
        .popup { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; display: none; align-items: center; justify-content: center; }
        .popup-content { background: white; color: black; width: 90%; max-width: 500px; padding: 20px; border-radius: 10px; text-align: center; max-height: 80vh; overflow-y: auto; }
        .popup-content p { font-size: 16px; margin-bottom: 10px; }
        .close-btn { padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .username-input, .task-input { padding: 8px; width: 100%; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        .task-item { cursor: move; transition: all 0.2s; }
        .task-item:hover { background-color: #2c2c2c; }
        .calendar-day { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 0.875rem; font-weight: 600; }
        .calendar-day:hover { transform: scale(1.1); }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%); color: white; border-radius: 8px; text-decoration: none; transition: all 0.2s; }
        .back-btn:hover { transform: translateX(-3px); box-shadow: 0 4px 12px rgba(13, 71, 161, 0.4); }
        .icon-option { padding: 10px; border: 2px solid #ccc; border-radius: 5px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .icon-option:hover { border-color: #3B82F6; background: #f0f9ff; }
        .icon-option.selected { border-color: #3B82F6; background: #dbeafe; }
    </style>
</head>
<body>
    <div id="app">Chargement...</div>
    <div id="aboutPopup" class="popup"><div class="popup-content"><p>©2025 SanteMentale.org</p><p>API : 0.dev</p><p id="appVersion"></p><p id="clientUUID"></p><button class="close-btn" onclick="closePopup('aboutPopup')">Fermer</button></div></div>
    <div id="privacyPopup" class="popup"><div class="popup-content"><p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n'est collectée. Tout est stocké sur votre appareil.</p><button class="close-btn" onclick="closePopup('privacyPopup')">Fermer</button></div></div>
    <div id="usernamePopup" class="popup"><div class="popup-content"><p>Nom à afficher sur les rapports PDF</p><input type="text" id="usernameInput" class="username-input" placeholder="Votre nom"><button class="close-btn" onclick="saveUsername()">Enregistrer</button><button class="close-btn" onclick="closePopup('usernamePopup')">Annuler</button></div></div>
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
            <h3 style="font-weight:bold;margin-bottom:15px;text-align:center;">Ajouter une tâche</h3>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Nom de la tâche</label>
            <input type="text" id="taskNameInput" class="task-input" placeholder="Ex: Méditation">
            <label style="display:block;margin:10px 0 5px;font-weight:bold;">Icône</label>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="taskIconPicker"></div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Durée estimée (minutes)</label>
            <input type="number" id="taskDurationInput" class="task-input" placeholder="15" min="0">
            <button class="close-btn" onclick="saveNewTask()" style="width:100%;background:#3B82F6;color:white;">Ajouter</button>
            <button class="close-btn" onclick="closePopup('addTaskPopup')" style="width:100%;">Annuler</button>
        </div>
    </div>
    <div id="editTaskPopup" class="popup">
        <div class="popup-content" style="text-align:left;">
            <h3 style="font-weight:bold;margin-bottom:15px;text-align:center;">Modifier la tâche</h3>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Nom de la tâche</label>
            <input type="text" id="editTaskNameInput" class="task-input" placeholder="Ex: Méditation">
            <label style="display:block;margin:10px 0 5px;font-weight:bold;">Icône</label>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;" id="editTaskIconPicker"></div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;">Durée estimée (minutes)</label>
            <input type="number" id="editTaskDurationInput" class="task-input" placeholder="15" min="0">
            <button class="close-btn" onclick="saveTaskEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button>
            <button class="close-btn" onclick="closePopup('editTaskPopup')" style="width:100%;">Annuler</button>
        </div>
    </div>
    <script>
        const MODULE_REVISION = '0.8a';
        const ICONS = [
            'wb_sunny', 'nightlight', 'favorite', 'local_cafe', 'book',
            'fitness_center', 'water_drop', 'music_note', 'brush', 'phone',
            'email', 'shopping_cart', 'nature', 'laptop', 'sports_esports',
            'camera_alt', 'edit', 'palette', 'home', 'work',
            'restaurant', 'bed', 'self_improvement', 'spa', 'pets',
            'alarm', 'directions_run', 'school', 'local_dining', 'psychology'
        ];
        const TASK_ICONS = [
            'list_alt', 'water_drop', 'local_cafe', 'egg', 'shopping_cart',
            'book', 'edit', 'laptop', 'fitness_center', 'favorite',
            'bedtime', 'brush', 'music_note', 'phone', 'people',
            'home', 'local_florist', 'restaurant', 'shower', 'directions_walk',
            'self_improvement', 'healing', 'medication', 'clean_hands', 'alarm_on',
            'event_note', 'schedule', 'timer', 'event_available', 'task_alt'
        ];
        let state = {
            routines: [],
            history: [],
            view: 'today',
            username: 'visiteur',
            currentDate: new Date().toLocaleDateString('fr-FR'),
            editingRoutineId: null,
            addingTaskToRoutine: null,
            editingTaskRoutineId: null,
            editingTaskId: null,
            selectedRoutineIcon: 'wb_sunny',
            selectedTaskIcon: 'list_alt',
            selectedEditTaskIcon: 'list_alt',
            calendarDate: new Date(),
            streak: 0
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
                    { id: 't11', name: 'Préparer le lendemain', icon: 'list_alt', duration: 15, completed: false },
                    { id: 't12', name: 'Coucher', icon: 'bedtime', duration: 0, completed: false }
                ]}
            ];
        }
        function openPopup(id) { document.getElementById(id).style.display = 'flex'; }
        function closePopup(id) { document.getElementById(id).style.display = 'none'; }
        function openUsernamePopup() { document.getElementById('usernameInput').value = state.username; openPopup('usernamePopup'); }
        function saveUsername() { state.username = document.getElementById('usernameInput').value.trim() || 'visiteur'; localStorage.setItem('username', state.username); closePopup('usernamePopup'); render(); }
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
            localStorage.setItem('routines', JSON.stringify(state.routines));
            localStorage.setItem('routineHistory', JSON.stringify(state.history));
            calcStreak();
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
            state.selectedTaskIcon = 'list_alt';
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
        function exportPDF() {
            const html = '<div style="padding:20px;font-family:Arial;"><h1 style="text-align:center;color:#3B82F6;">Checklist Routines</h1><h2 style="text-align:center;color:#666;">' + state.username + '</h2><p style="text-align:center;color:#999;">' + new Date().toLocaleDateString('fr-FR') + '</p>' + state.routines.map(r => '<div style="margin:20px 0;"><h3 style="color:#3B82F6;">' + r.name + '</h3>' + r.tasks.map(t => '<div style="margin:10px 0;padding:10px;border:1px solid #ccc;border-radius:5px;display:flex;align-items:center;"><input type="checkbox" style="width:20px;height:20px;margin-right:10px;"><span>' + t.name + ' (' + t.duration + ' min)</span></div>').join('') + '</div>').join('') + '</div>';
            const el = document.createElement('div'); el.innerHTML = html;
            html2pdf().from(el).save('Routines_' + state.username + '_' + new Date().toISOString().split('T')[0] + '.pdf');
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
        function render() {
            const app = document.getElementById('app');
            const clientVersion = localStorage.getItem('client_version') || '1.web';
            let h = '<div class="bg-zinc-900 border-b border-zinc-800 sticky top-0 z-10"><div class="max-w-2xl mx-auto px-4 py-4 flex items-center gap-3">';
            h += '<a href="/v1/outils/?v=' + clientVersion + '" class="back-btn" style="flex-shrink:0;"><span class="material-icons">arrow_back</span></a>';
            h += '<h1 class="text-xl font-bold flex items-center gap-2"><span class="material-icons" style="color:#3B82F6;">checklist</span>Routines</h1></div></div>';
            h += '<div class="bg-zinc-900 border-b border-zinc-800"><div class="max-w-2xl mx-auto px-4"><div class="flex gap-1 justify-around">';
            h += '<button onclick="state.view=\'today\';render();" class="py-4 px-3 transition-all ' + (state.view==='today'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">check_box</span></button>';
            h += '<button onclick="state.view=\'stats\';render();" class="py-4 px-3 transition-all ' + (state.view==='stats'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">bar_chart</span></button>';
            h += '<button onclick="state.view=\'manage\';render();" class="py-4 px-3 transition-all ' + (state.view==='manage'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">settings</span></button>';
            h += '<button onclick="state.view=\'calendar\';render();" class="py-4 px-3 transition-all ' + (state.view==='calendar'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">calendar_month</span></button>';
            h += '<button onclick="state.view=\'goals\';render();" class="py-4 px-3 transition-all ' + (state.view==='goals'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">emoji_events</span></button>';
            h += '</div></div></div><div class="max-w-2xl mx-auto px-4 py-6">';
            if (state.view === 'today') {
                const completion = getTodayCompletion();
                h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4"><div class="flex items-center justify-between mb-2"><span class="font-bold">Progression du jour</span><span class="font-bold text-blue-400">' + completion + '%</span></div><div class="w-full bg-zinc-800 rounded-full h-3"><div class="bg-blue-600 h-full rounded-full transition-all" style="width:' + completion + '%"></div></div></div>';
                state.routines.forEach(routine => {
                    const rc = getRoutineCompletion(routine.id);
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4">';
                    h += '<div class="flex items-center justify-between mb-3"><div class="flex items-center gap-2"><span class="material-icons text-2xl" style="color:#3B82F6;">' + routine.icon + '</span><h3 class="font-bold">' + routine.name + '</h3></div><span class="text-sm text-zinc-500">' + rc + '%</span></div>';
                    h += '<div class="space-y-2" id="tasks-' + routine.id + '">';
                    routine.tasks.forEach(task => {
                        h += '<div class="flex items-center gap-3 p-3 bg-zinc-950 rounded-lg border border-zinc-800" data-task-id="' + task.id + '" data-routine-id="' + routine.id + '">';
                        h += '<label class="flex items-center cursor-pointer" style="user-select:none;"><input type="checkbox" ' + (task.completed ? 'checked' : '') + ' class="w-5 h-5 checkbox-task" data-routine="' + routine.id + '" data-task="' + task.id + '"></label>';
                        h += '<span class="material-icons text-xl">' + task.icon + '</span>';
                        h += '<div class="flex-1"><div class="font-semibold ' + (task.completed ? 'line-through text-zinc-500' : '') + '">' + task.name + '</div><div class="text-xs text-zinc-500">' + task.duration + ' min</div></div>';
                        h += '<span class="material-icons text-zinc-600 task-handle" style="cursor:move;font-size:20px;">drag_indicator</span>';
                        h += '</div>';
                    });
                    h += '</div></div>';
                });
            } else if (state.view === 'stats') {
                calcStreak();
                h += '<div class="space-y-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">trending_up</span>Résumé</h3><div class="grid grid-cols-2 gap-3">';
                h += '<div class="text-center p-3 bg-zinc-950 rounded-lg"><div class="text-2xl font-bold">' + state.history.length + '</div><div class="text-xs text-zinc-500">Jours enregistrés</div></div>';
                h += '<div class="text-center p-3 bg-blue-950 rounded-lg border border-blue-900"><div class="text-2xl font-bold text-blue-400">' + state.streak + '</div><div class="text-xs text-zinc-500">Série (>75%)</div></div></div></div>';
                h += '<button onclick="exportPDF()" class="w-full py-3 bg-red-600 text-white font-bold rounded-lg flex items-center justify-center gap-2"><span class="material-icons">picture_as_pdf</span> Export PDF Checklist</button></div>';
            } else if (state.view === 'manage') {
                h += '<div class="space-y-4">';
                state.routines.forEach(routine => {
                    h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
                    h += '<div class="flex items-center justify-between mb-3"><div class="flex items-center gap-2"><span class="material-icons text-2xl" style="color:#3B82F6;">' + routine.icon + '</span><h3 class="font-bold">' + routine.name + '</h3></div>';
                    h += '<div class="flex gap-2"><button onclick="openEditRoutine(\'' + routine.id + '\')" class="p-2 text-blue-400 hover:bg-blue-950 rounded"><span class="material-icons">edit</span></button>';
                    h += '<button onclick="deleteRoutine(\'' + routine.id + '\')" class="p-2 text-red-400 hover:bg-red-950 rounded"><span class="material-icons">delete</span></button></div></div>';
                    h += '<div class="space-y-2">';
                    routine.tasks.forEach(task => {
                        h += '<div class="flex items-center justify-between p-2 bg-zinc-950 rounded"><div class="flex items-center gap-2"><span class="material-icons">' + task.icon + '</span><span>' + task.name + ' (' + task.duration + ' min)</span></div>';
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
                h += '<div class="space-y-4"><div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">local_fire_department</span>Série actuelle</h3><div class="text-center">';
                h += '<div class="text-6xl font-bold text-blue-500 mb-2">' + state.streak + '</div><div class="text-zinc-400">jour' + (state.streak>1?'s':'') + ' à >75%</div></div></div>';
                h += '<div class="bg-blue-950 border border-blue-900 rounded-lg p-4 text-sm text-blue-200"><span class="material-icons mr-2" style="vertical-align:middle;">stars</span><strong>Objectif :</strong> Compléter au moins 75% de vos tâches chaque jour pour maintenir votre série !</div></div>';
            }
            h += '</div><div style="margin-top:40px;padding-bottom:40px;text-align:center;">';
            h += '<p style="margin-top:30px;color:#555;font-size:14px;line-height:1.8;"><svg style="width:14px;height:14px;margin-right:5px;display:inline-block;vertical-align:middle;" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span style="color:#ddd;cursor:pointer;" onclick="openUsernamePopup()">' + state.username + '</span><br>App v' + clientVersion + ' • Mod v' + MODULE_REVISION + ' • Accès anticipé<br><a onclick="openPopup(\'aboutPopup\')" style="color:#0d47a1;cursor:pointer;">À propos</a> • <a onclick="openPopup(\'privacyPopup\')" style="color:#0d47a1;cursor:pointer;">Confidentialité</a><br/><span style="color:#161616;">©2025 SanteMentale.org</span></p></div></div>';
            app.innerHTML = h;
            if (state.view === 'today') {
                state.routines.forEach(routine => {
                    const el = document.getElementById('tasks-' + routine.id);
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
                document.querySelectorAll('.checkbox-task').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const routineId = this.dataset.routine;
                        const taskId = this.dataset.task;
                        toggleTask(routineId, taskId);
                    });
                });
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
                state.username = localStorage.getItem('username') || 'visiteur';
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
                render();
            } catch(e) {
                console.error('Erreur:', e);
                document.getElementById('app').innerHTML = '<div style="color:red;padding:20px;">Erreur: ' + e.message + '</div>';
            }
        });
    </script>
</body>
</html>
