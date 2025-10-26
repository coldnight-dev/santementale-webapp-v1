// Vue TODAY - Module autonome
return (function() {
    'use strict';

    function getTodayCompletion() {
        const today = state.history.find(h => h.date === state.currentDate);
        if (!today) return 0;
        let total = 0, completed = 0;
        state.routines.forEach(r => r.tasks.forEach(t => {
            total++;
            if (today.routines[r.id] && today.routines[r.id][t.id]) completed++;
        }));
        return total > 0 ? Math.round((completed / total) * 100) : 0;
    }

    function getRoutineCompletion(routineId) {
        const routine = state.routines.find(r => r.id === routineId);
        if (!routine || routine.tasks.length === 0) return 0;
        const completed = routine.tasks.filter(t => t.completed).length;
        return Math.round((completed / routine.tasks.length) * 100);
    }

    function gainXP(amount) {
        state.xp += amount;
        const oldLevel = state.level;
        state.level = Math.floor(state.xp / constants.XP_PER_LEVEL) + 1;
        localStorage.setItem('routineXP', state.xp.toString());
        localStorage.setItem('routineLevel', state.level.toString());
        if (state.level > oldLevel) {
            showAchievementNotification('Niveau ' + state.level + ' atteint ! 🎉');
            triggerConfetti();
        }
    }

    function triggerConfetti() {
        if (typeof confetti !== 'undefined') {
            confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });
        }
    }

    function showAchievementNotification(text) {
        const notif = document.getElementById('achievementNotification');
        document.getElementById('achievementText').textContent = text;
        notif.classList.add('show');
        setTimeout(() => notif.classList.remove('show'), 4000);
    }

    function checkBadges() {
        constants.BADGES.forEach(badge => {
            if (state.unlockedBadges.includes(badge.id)) return;
            let unlock = false;
            switch (badge.requirement) {
                case 'tasks':
                    unlock = state.totalTasksCompleted >= badge.threshold;
                    break;
                case 'streak':
                    unlock = state.streak >= badge.threshold;
                    break;
                case 'perfect_days':
                    unlock = state.perfectDaysCount >= badge.threshold;
                    break;
                case 'morning_routines': {
                    const morningRoutine = state.routines.find(r => r.name.toLowerCase().includes('matin'));
                    if (morningRoutine) {
                        const today = state.history.find(h => h.date === state.currentDate);
                        if (today && today.routines[morningRoutine.id]) {
                            unlock = morningRoutine.tasks.length === morningRoutine.tasks.filter(t => today.routines[morningRoutine.id][t.id]).length;
                        }
                    }
                    break;
                }
                case 'evening_routines': {
                    const eveningRoutine = state.routines.find(r => r.name.toLowerCase().includes('soir'));
                    if (eveningRoutine) {
                        const today = state.history.find(h => h.date === state.currentDate);
                        if (today && today.routines[eveningRoutine.id]) {
                            unlock = eveningRoutine.tasks.length === eveningRoutine.tasks.filter(t => today.routines[eveningRoutine.id][t.id]).length;
                        }
                    }
                    break;
                }
                case 'custom_routines': {
                    const defaultIds = ['morning', 'day', 'evening'];
                    unlock = state.routines.filter(r => !defaultIds.includes(r.id)).length >= badge.threshold;
                    break;
                }
            }
            if (unlock) {
                state.unlockedBadges.push(badge.id);
                localStorage.setItem('routineBadges', JSON.stringify(state.unlockedBadges));
                showAchievementNotification(badge.icon + ' ' + badge.name + ' débloqué !');
                triggerConfetti();
            }
        });
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
                state.routines.forEach(r => r.tasks.forEach(t => {
                    dayTotal++;
                    if (dayData.routines[r.id] && dayData.routines[r.id][t.id]) dayCompleted++;
                }));
                if (dayTotal > 0 && dayCompleted / dayTotal >= 0.75) s++;
                else break;
            } else break;
        }
        state.streak = s;
        localStorage.setItem('routineStreak', s.toString());
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
        if (!dayHistory.routines[routineId]) dayHistory.routines[routineId] = {};
        dayHistory.routines[routineId][taskId] = task.completed;
        
        if (task.completed) {
            gainXP(constants.XP_PER_TASK);
            state.totalTasksCompleted++;
            localStorage.setItem('routineTotalTasks', state.totalTasksCompleted.toString());
        } else {
            state.xp = Math.max(0, state.xp - constants.XP_PER_TASK);
            state.totalTasksCompleted = Math.max(0, state.totalTasksCompleted - 1);
            state.level = Math.floor(state.xp / constants.XP_PER_LEVEL) + 1;
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
        window.renderXPBar();
    }

    function render() {
        const completion = getTodayCompletion();
        let completionColor = '#71717a';
        if (completion >= 76) completionColor = '#10B981';
        else if (completion >= 51) completionColor = '#F59E0B';
        else if (completion >= 26) completionColor = '#EF4444';
        else if (completion > 0) completionColor = '#3B82F6';
        
        let progressHtml = '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4">';
        progressHtml += '<div class="flex items-center justify-between mb-2">';
        progressHtml += '<span class="font-bold">Progression du ' + new Date().toLocaleDateString('fr-FR', { day: 'numeric', month: 'long' }) + '</span>';
        progressHtml += '<span class="font-bold" style="color:' + completionColor + ';">' + completion + '%</span></div>';
        progressHtml += '<div class="w-full bg-zinc-800 rounded-full h-3 task-progress-bar">';
        progressHtml += '<div class="bg-blue-600 h-full rounded-full transition-all task-progress-fill" style="width:' + completion + '%"></div>';
        progressHtml += '</div></div>';
        document.getElementById('todayProgressCard').innerHTML = progressHtml;
        
        let routinesHtml = '';
        state.routines.forEach(routine => {
            const rc = getRoutineCompletion(routine.id);
            let rcColor = '#71717a';
            if (rc >= 76) rcColor = '#10B981';
            else if (rc >= 51) rcColor = '#F59E0B';
            else if (rc >= 26) rcColor = '#EF4444';
            else if (rc > 0) rcColor = '#3B82F6';
            
            routinesHtml += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4">';
            routinesHtml += '<div class="flex items-center justify-between mb-3">';
            routinesHtml += '<div class="flex items-center gap-2">';
            routinesHtml += '<span class="material-symbols-outlined text-2xl" style="color:#3B82F6;">' + routine.icon + '</span>';
            routinesHtml += '<h3 class="font-bold">' + routine.name + '</h3></div>';
            routinesHtml += '<span class="text-sm font-bold" style="color:' + rcColor + ';">' + rc + '%</span></div>';
            routinesHtml += '<div class="space-y-2">';
            
            routine.tasks.forEach(task => {
                routinesHtml += '<div class="flex items-center gap-3 p-3 bg-zinc-950 rounded-lg border border-zinc-800" style="cursor:pointer;" data-routine="' + routine.id + '" data-task="' + task.id + '">';
                routinesHtml += '<span class="material-symbols-outlined" style="font-size:28.8px;">' + task.icon + '</span>';
                routinesHtml += '<div class="flex-1"><div class="font-semibold task-name-sofia ' + (task.completed ? 'line-through text-zinc-500' : '') + '">' + task.name + '</div>';
                routinesHtml += '<div class="text-xs text-zinc-600">' + task.duration + '&nbsp;min&nbsp;&nbsp;&nbsp;+' + constants.XP_PER_TASK + '&nbsp;XP</div></div>';
                routinesHtml += '<span class="material-symbols-outlined" style="font-size:36px; color:' + (task.completed ? '#10B981' : '#71717a') + ';">';
                routinesHtml += (task.completed ? 'check_box' : 'check_box_outline_blank') + '</span></div>';
            });
            
            routinesHtml += '</div></div>';
        });
        document.getElementById('todayRoutinesContainer').innerHTML = routinesHtml;
        
        // Attacher les événements
        document.querySelectorAll('[data-routine][data-task]').forEach(el => {
            el.addEventListener('click', function() {
                toggleTask(this.dataset.routine, this.dataset.task);
            });
        });
    }

    // Export du module
    return {
        init: function() {
            render();
        },
        cleanup: function() {
            // Nettoyer les événements si nécessaire
        }
    };

})();
