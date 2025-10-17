// ========================================
// ROUTINES v0.11 - Script FINAL COMPLET
// ========================================

const MODULE_REVISION = '0.11';
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
const XP_PER_TASK = 10;
const XP_PER_LEVEL = 100;
const TUTORIAL_STEPS = [
    {
        target: '.xp-bar',
        title: 'Système XP & Niveaux',
        description: 'Gagnez de l\'expérience en complétant des tâches et débloquez des niveaux ! Chaque tâche complétée = +10 XP.',
        anchor: 'bottom'
    },
    {
        target: '[onclick*="stats"]',
        title: 'Onglet Statistiques',
        description: 'Consultez votre progression, votre série de jours et le temps total investi dans vos routines.',
        anchor: 'bottom'
    },
    {
        target: '[onclick*="goals"]',
        title: 'Onglet Achievements',
        description: 'Débloquez des badges exclusifs en atteignant des objectifs. 10 achievements à conquérir !',
        anchor: 'bottom'
    },
    {
        target: 'h1',
        title: 'Besoin d\'aide ?',
        description: 'Cliquez sur le bouton d\'aide pour accéder au menu. Vous pouvez relancer le tutoriel à tout moment !',
        anchor: 'bottom'
    }
];
const WHATS_NEW = [
    { icon: '📸', title: 'Partage de progression', desc: 'Générez une belle image de vos stats à partager' },
    { icon: '📅', title: 'Historique détaillé', desc: 'Naviguez dans vos mois précédents et consultez chaque jour' },
    { icon: '⭐', title: 'Système XP & Niveaux', desc: 'Progressez en complétant des tâches' },
    { icon: '🎉', title: 'Confettis à 100%', desc: 'Célébrez vos journées parfaites' }
];

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
    xp: 0,
    level: 1,
    unlockedBadges: [],
    totalTasksCompleted: 0,
    perfectDaysCount: 0,
    statsChart: null,
    tutorialStep: -1,
    lastSeenVersion: '0',
    isFirstTimeView: false
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

function showWhatsNew() {
    closePopup('helpModalPopup');
    renderWhatsNew(true);
    openPopup('whatsNewPopup');
}

function startTutorial() {
    state.tutorialStep = 0;
    showTutorialStep();
}

function showTutorialStep() {
    if (state.tutorialStep >= TUTORIAL_STEPS.length) {
        closeTutorial();
        return;
    }
    const step = TUTORIAL_STEPS[state.tutorialStep];
    const target = document.querySelector(step.target);

    if (!target) {
        state.tutorialStep++;
        showTutorialStep();
        return;
    }

    const overlay = document.getElementById('tutorialOverlay');
    const highlight = document.getElementById('tutorialHighlight');
    const tooltip = document.getElementById('tutorialTooltip');

    overlay.style.display = 'block';
    highlight.style.display = 'block';

    const rect = target.getBoundingClientRect();
    highlight.style.left = (rect.left - 5) + 'px';
    highlight.style.top = (rect.top - 5) + 'px';
    highlight.style.width = (rect.width + 10) + 'px';
    highlight.style.height = (rect.height + 10) + 'px';

    let tooltipHTML = '<h3>' + step.title + '</h3>';
    tooltipHTML += '<p>' + step.description + '</p>';
    tooltipHTML += '<div class="tutorial-buttons">';
    tooltipHTML += '<button class="tutorial-btn skip" onclick="skipTutorial()">Passer</button>';
    tooltipHTML += '<button class="tutorial-btn next" onclick="nextTutorialStep()">Suivant</button>';
    tooltipHTML += '</div>';
    tooltip.innerHTML = tooltipHTML;
    tooltip.style.display = 'block';

    let tooltipLeft = rect.left + rect.width / 2 - 160;
    let tooltipTop = rect.top - 180;

    if (step.anchor === 'bottom') {
        tooltipTop = rect.bottom + 20;
    }

    tooltipLeft = Math.max(10, Math.min(tooltipLeft, window.innerWidth - 330));
    tooltipTop = Math.max(10, tooltipTop);

    tooltip.style.left = tooltipLeft + 'px';
    tooltip.style.top = tooltipTop + 'px';
}

function nextTutorialStep() {
    state.tutorialStep++;
    showTutorialStep();
}

function skipTutorial() {
    closeTutorial();
}

function closeTutorial() {
    const overlay = document.getElementById('tutorialOverlay');
    const highlight = document.getElementById('tutorialHighlight');
    const tooltip = document.getElementById('tutorialTooltip');
    overlay.style.display = 'none';
    highlight.style.display = 'none';
    tooltip.style.display = 'none';
    state.tutorialStep = -1;
    localStorage.setItem('routineLastSeenVersion', MODULE_REVISION);
}

function renderWhatsNew(showClose) {
    let content = '';
    WHATS_NEW.forEach(item => {
        content += '<div class="whats-new-item">';
        content += '<div class="whats-new-icon">' + item.icon + '</div>';
        content += '<div class="whats-new-text"><h4>' + item.title + '</h4><p>' + item.desc + '</p></div>';
        content += '</div>';
    });
    document.getElementById('whatsNewContent').innerHTML = content;
    const closeBtn = document.getElementById('whatsNewCloseBtn');
    if (showClose) {
        closeBtn.style.display = 'block';
    } else {
        closeBtn.style.display = 'none';
    }
}

// ========================================
// NOUVEAU v0.11 : PARTAGE DE PROGRESSION
// ========================================

function generateShareImage() {
    const canvas = document.createElement('canvas');
    canvas.width = 1200;
    canvas.height = 1600;
    const ctx = canvas.getContext('2d');

    // Fond dégradé
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    gradient.addColorStop(0, '#1e3a8a');
    gradient.addColorStop(1, '#1e40af');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Titre
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 80px sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('Ma Progression Routines', canvas.width / 2, 150);

    // Niveau
    ctx.font = 'bold 120px sans-serif';
    ctx.fillStyle = '#fbbf24';
    ctx.fillText('⭐ Niveau ' + state.level, canvas.width / 2, 320);

    // XP
    const xpInLevel = state.xp % XP_PER_LEVEL;
    ctx.font = '50px sans-serif';
    ctx.fillStyle = '#ffffff';
    ctx.fillText(xpInLevel + ' / ' + XP_PER_LEVEL + ' XP', canvas.width / 2, 400);

    // Barre XP
    const barWidth = 800;
    const barHeight = 60;
    const barX = (canvas.width - barWidth) / 2;
    const barY = 450;

    ctx.fillStyle = '#18181b';
    ctx.fillRect(barX, barY, barWidth, barHeight);

    const fillWidth = (xpInLevel / XP_PER_LEVEL) * barWidth;
    const fillGradient = ctx.createLinearGradient(barX, 0, barX + barWidth, 0);
    fillGradient.addColorStop(0, '#3B82F6');
    fillGradient.addColorStop(1, '#60A5FA');
    ctx.fillStyle = fillGradient;
    ctx.fillRect(barX, barY, fillWidth, barHeight);

    // Stats
    const statsY = 600;
    ctx.font = 'bold 60px sans-serif';
    ctx.fillStyle = '#ffffff';

    // Série
    ctx.fillText('🔥 ' + state.streak + ' jours', canvas.width / 2, statsY);

    // Tâches
    ctx.font = '50px sans-serif';
    ctx.fillText(state.totalTasksCompleted + ' tâches complétées', canvas.width / 2, statsY + 80);

    // Journées parfaites
    ctx.fillText('✨ ' + state.perfectDaysCount + ' journées parfaites', canvas.width / 2, statsY + 160);

    // Top 3 badges
    ctx.font = 'bold 50px sans-serif';
    ctx.fillText('Mes meilleurs badges', canvas.width / 2, statsY + 280);

    const topBadges = state.unlockedBadges.slice(0, 3);
    let badgeY = statsY + 360;
    topBadges.forEach((badgeId, index) => {
        const badge = BADGES.find(b => b.id === badgeId);
        if (badge) {
            ctx.font = '80px sans-serif';
            ctx.fillText(badge.icon, canvas.width / 2 - 300 + (index * 300), badgeY);
            ctx.font = '40px sans-serif';
            ctx.fillText(badge.name, canvas.width / 2 - 300 + (index * 300), badgeY + 80);
        }
    });

    // Footer
    ctx.font = '40px sans-serif';
    ctx.fillStyle = 'rgba(255,255,255,0.7)';
    ctx.fillText('SanteMentale.org', canvas.width / 2, canvas.height - 100);

    // Télécharger
    canvas.toBlob(blob => {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'ma-progression-routines.png';
        a.click();
        URL.revokeObjectURL(url);
    });
}

// ========================================
// NOUVEAU v0.11 : HISTORIQUE DÉTAILLÉ
// ========================================

function showDayDetail(dateStr) {
    const dayData = state.history.find(h => h.date === dateStr);
    if (!dayData) return;

    document.getElementById('dayDetailTitle').textContent = 'Détails du ' + dateStr;

    let html = '';
    let totalTasks = 0;
    let completedTasks = 0;

    state.routines.forEach(routine => {
        const routineData = dayData.routines[routine.id];
        if (!routineData) return;

        html += '<div style="margin-bottom:20px;">';
        html += '<h4 style="font-weight:bold;margin-bottom:10px;display:flex;align-items:center;gap:8px;">';
        html += '<span class="material-icons" style="color:#3B82F6;">' + routine.icon + '</span>';
        html += routine.name + '</h4>';
        html += '<div style="margin-left:32px;">';

        routine.tasks.forEach(task => {
            totalTasks++;
            const completed = routineData[task.id];
            if (completed) completedTasks++;

            html += '<div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;padding:8px;background:' + (completed ? '#dcfce7' : '#fee2e2') + ';border-radius:6px;">';
            html += '<span class="material-icons" style="font-size:20px;color:' + (completed ? '#10B981' : '#EF4444') + ';">' + (completed ? 'check_circle' : 'cancel') + '</span>';
            html += '<span style="color:#000;">' + task.name + '</span>';
            html += '</div>';
        });

        html += '</div></div>';
    });

    const completion = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
    html = '<div style="background:#3B82F6;color:white;padding:12px;border-radius:8px;margin-bottom:20px;text-align:center;font-weight:bold;font-size:18px;">Complétion : ' + completion + '%</div>' + html;

    document.getElementById('dayDetailContent').innerHTML = html;
    openPopup('dayDetailPopup');
}

function renderHistoryList() {
    const sortedHistory = state.history.slice().sort((a, b) => {
        const dateA = new Date(a.date.split('/').reverse().join('-'));
        const dateB = new Date(b.date.split('/').reverse().join('-'));
        return dateB - dateA;
    });

    let html = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
    html += '<h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">history</span>Historique complet</h3>';

    if (sortedHistory.length === 0) {
        html += '<p class="text-zinc-500 text-center py-8">Aucun historique disponible</p>';
    } else {
        html += '<div class="space-y-2">';
        sortedHistory.forEach(day => {
            let total = 0, completed = 0;
            state.routines.forEach(r => {
                r.tasks.forEach(t => {
                    total++;
                    if (day.routines[r.id] && day.routines[r.id][t.id]) completed++;
                });
            });
            const completion = total > 0 ? Math.round((completed / total) * 100) : 0;

            let bgColor = '#18181b';
            let textColor = '#71717a';
            if (completion >= 76) { bgColor = '#064e3b'; textColor = '#10B981'; }
            else if (completion >= 51) { bgColor = '#78350f'; textColor = '#F59E0B'; }
            else if (completion >= 26) { bgColor = '#7f1d1d'; textColor = '#EF4444'; }
            else if (completion > 0) { bgColor = '#1e3a8a'; textColor = '#3B82F6'; }

            html += '<div class="history-day-item p-3 rounded-lg border border-zinc-800" style="background:' + bgColor + ';cursor:pointer;" onclick="showDayDetail(\'' + day.date + '\')">';
            html += '<div class="flex justify-between items-center">';
            html += '<span class="font-semibold" style="color:' + textColor + ';">' + day.date + '</span>';
            html += '<span style="color:' + textColor + ';font-weight:bold;">' + completion + '%</span>';
            html += '</div></div>';
        });
        html += '</div>';
    }

    html += '</div>';
    return html;
}

// ========================================
// FONCTIONS EXISTANTES
// ========================================

function gainXP(amount) {
    state.xp += amount;
    const oldLevel = state.level;
    state.level = Math.floor(state.xp / XP_PER_LEVEL) + 1;
    localStorage.setItem('routineXP', state.xp.toString());
    localStorage.setItem('routineLevel', state.level.toString());
    if (state.level > oldLevel) {
        showAchievementNotification('Niveau ' + state.level + ' atteint ! 🎉');
        triggerConfetti();
    }
}

function triggerConfetti() {
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
}

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
            triggerConfetti();
        }
    });
}

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
    if (task.completed) {
        gainXP(XP_PER_TASK);
        state.totalTasksCompleted++;
        localStorage.setItem('routineTotalTasks', state.totalTasksCompleted.toString());
    } else {
        state.xp = Math.max(0, state.xp - XP_PER_TASK);
        state.totalTasksCompleted = Math.max(0, state.totalTasksCompleted - 1);
        state.level = Math.floor(state.xp / XP_PER_LEVEL) + 1;
        localStorage.setItem('routineXP', state.xp.toString());
        localStorage.setItem('routineLevel', state.level.toString());
        localStorage.setItem('routineTotalTasks', state.totalTasksCompleted.toString());
    }
    const completion = getTodayCompletion();
    if (completion === 100) {
        state.perfectDaysCount++;
        localStorage.setItem('routinePerfectDays', state.perfectDaysCount.toString());
        triggerConfetti();
    }
    localStorage.setItem('routines', JSON.stringify(state.routines));
    localStorage.setItem('routineHistory', JSON.stringify(state.history));
    calcStreak();
    checkBadges();
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
    checkBadges();
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
    checkBadges();
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

function renderStatsChart() {
    setTimeout(() => {
        const canvas = document.getElementById('statsChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        if (state.statsChart) {
            state.statsChart.destroy();
        }
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
    h += '<h1 class="text-3xl font-bold title-genos">Routines <button onclick="openPopup(\'helpModalPopup\')" style="background:none;border:none;cursor:pointer;font-size:18px;color:#3B82F6;font-weight:bold;padding:0;margin-left:4px;vertical-align:baseline;">?</button></h1>';
    h += '<div style="margin-left:auto;"><span class="material-symbols-outlined title-icon-animated" style="color:#3B82F6;font-size:32px;">routine</span></div></div></div>';
    h += '<div class="bg-zinc-900 border-b border-zinc-800"><div class="max-w-2xl mx-auto px-4"><div class="flex gap-1 justify-around">';
    h += '<button onclick="state.view=\'today\';render();" class="py-4 px-3 transition-all ' + (state.view==='today'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">check_box</span></button>';
    h += '<button onclick="state.view=\'stats\';render();" class="py-4 px-3 transition-all ' + (state.view==='stats'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">bar_chart</span></button>';
    h += '<button onclick="state.view=\'manage\';render();" class="py-4 px-3 transition-all ' + (state.view==='manage'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">settings</span></button>';
    h += '<button onclick="state.view=\'calendar\';render();" class="py-4 px-3 transition-all ' + (state.view==='calendar'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">calendar_month</span></button>';
    h += '<button onclick="state.view=\'goals\';render();" class="py-4 px-3 transition-all ' + (state.view==='goals'?'text-blue-400 border-b-2 border-blue-500':'text-zinc-500') + '"><span class="material-icons" style="font-size:24px;">emoji_events</span></button>';
    h += '</div></div></div><div class="max-w-2xl mx-auto px-4 py-6">';
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
                h += '<div class="flex-1"><div class="font-semibold ' + (task.completed ? 'line-through text-zinc-500' : '') + '">' + task.name + '</div><div class="text-xs text-zinc-600">' + task.duration + '&nbsp;min&nbsp;&nbsp;&nbsp;+' + XP_PER_TASK + '&nbsp;XP</div></div>';
                h += '</div>';
            });
            h += '</div></div>';
        });
    } else if (state.view === 'stats') {
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
        state.xp = parseInt(localStorage.getItem('routineXP') || '0');
        state.level = parseInt(localStorage.getItem('routineLevel') || '1');
        state.unlockedBadges = JSON.parse(localStorage.getItem('routineBadges') || '[]');
        state.totalTasksCompleted = parseInt(localStorage.getItem('routineTotalTasks') || '0');
        state.perfectDaysCount = parseInt(localStorage.getItem('routinePerfectDays') || '0');
        state.lastSeenVersion = localStorage.getItem('routineLastSeenVersion') || '0';

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
        checkBadges();
        render();

        if (state.lastSeenVersion !== MODULE_REVISION) {
            state.isFirstTimeView = true;
            setTimeout(() => {
                renderWhatsNew(false);
                openPopup('whatsNewPopup');
            }, 1000);
        }
    } catch(e) {
        console.error('Erreur:', e);
        document.getElementById('app').innerHTML = '<div style="color:red;padding:20px;">Erreur: ' + e.message + '</div>';
    }
}); (state.view === 'stats') {
        calcStreak();
        h += '<div class="space-y-4">';
        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
        h += '<h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">show_chart</span>7 derniers jours</h3>';
        h += '<div class="chart-container"><canvas id="statsChart"></canvas></div>';
        h += '</div>';
        h += '<button onclick="generateShareImage()" class="w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:from-purple-700 hover:to-pink-700 flex items-center justify-center gap-2" style="transition:all 0.2s;"><span class="material-icons">share</span>Partager ma progression</button>';
        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">trending_up</span>Résumé</h3><div class="grid grid-cols-2 gap-3">';
        h += '<div class="text-center p-3 bg-zinc-950 rounded-lg"><div class="text-4xl font-bold">' + state.history.length + '</div><div class="text-xs text-zinc-500">Jours enregistrés</div></div>';
        h += '<div class="text-center p-3 bg-blue-950 rounded-lg border border-blue-900"><div class="text-4xl font-bold text-blue-400">' + state.streak + '</div><div class="text-xs text-zinc-500">Série actuelle</div></div>';
        h += '<div class="text-center p-3 bg-green-950 rounded-lg border border-green-900"><div class="text-4xl font-bold text-green-400">' + state.totalTasksCompleted + '</div><div class="text-xs text-zinc-500">Tâches complétées</div></div>';
        h += '<div class="text-center p-3 bg-yellow-950 rounded-lg border border-yellow-900"><div class="text-4xl font-bold text-yellow-400">' + state.perfectDaysCount + '</div><div class="text-xs text-zinc-500">Journées parfaites</div></div>';
        h += '</div></div>';
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
        h += '<div class="text-sm text-purple-300 mt-1">Temps total consacré à vos routines</div>';
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
        h += '<div style="margin-top:20px;"></div>';
        h += renderHistoryList();
    } else if (state.view === 'goals') {
        calcStreak();
        h += '<div class="space-y-4">';
        h += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4"><h3 class="font-bold mb-3"><span class="material-icons mr-2" style="vertical-align:middle;">local_fire_department</span>Série actuelle</h3><div class="text-center">';
        h += '<div class="text-6xl font-bold text-orange-500 mb-2 streak-pulse">' + state.streak + '</div><div class="text-zinc-400">jour' + (state.streak>1?'s':'') + ' à >75%</div></div></div>';
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
// ========================================
// PARTIE 2/2 - SUITE DU SCRIPT v0.11
// À COLLER APRÈS LA LIGNE "} else if" du premier fichier
// ========================================

} else if (state.view === 'stats') {
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
        state.xp = parseInt(localStorage.getItem('routineXP') || '0');
        state.level = parseInt(localStorage.getItem('routineLevel') || '1');
        state.unlockedBadges = JSON.parse(localStorage.getItem('routineBadges') || '[]');
        state.totalTasksCompleted = parseInt(localStorage.getItem('routineTotalTasks') || '0');
        state.perfectDaysCount = parseInt(localStorage.getItem('routinePerfectDays') || '0');
        state.lastSeenVersion = localStorage.getItem('routineLastSeenVersion') || '0';

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
        checkBadges();
        render();

        if (state.lastSeenVersion !== MODULE_REVISION) {
            state.isFirstTimeView = true;
            setTimeout(() => {
                renderWhatsNew(false);
                openPopup('whatsNewPopup');
            }, 1000);
        }
    } catch(e) {
        console.error('Erreur:', e);
        document.getElementById('app').innerHTML = '<div style="color:red;padding:20px;">Erreur: ' + e.message + '</div>';
    }
});

// FIN DU SCRIPT v0.11
