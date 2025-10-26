// Vue MANAGE - Module autonome
return (function() {
    'use strict';

    let editingRoutineId = null;
    let addingTaskToRoutine = null;
    let editingTaskRoutineId = null;
    let editingTaskId = null;
    let selectedRoutineIcon = 'wb_sunny';
    let selectedTaskIcon = 'self_improvement';
    let selectedEditTaskIcon = 'self_improvement';
    let routineIconPage = 0;
    let taskIconPage = 0;
    let editTaskIconPage = 0;

    function getTotalTaskCount() {
        let total = 0;
        state.routines.forEach(routine => {
            total += routine.tasks.length;
        });
        return total;
    }

    function showLimitWarning(type) {
        let content = '';
        if (type === 'routines') {
            content = '<p><strong>Limite de routines atteinte !</strong></p>';
            content += '<p>Vous avez atteint la limite de <strong>' + constants.MAX_ROUTINES + ' routines</strong>.</p>';
            content += '<p style="margin-top:16px;">💡 <strong>Conseil :</strong> Essayez de regrouper vos activités similaires dans une même routine. Par exemple :</p>';
            content += '<ul style="margin-left:20px;margin-top:8px;line-height:1.8;">';
            content += '<li>Routine "Bien-être" : méditation + yoga + lecture</li>';
            content += '<li>Routine "Productivité" : planification + travail + pause</li>';
            content += '<li>Routine "Soins personnels" : douche + skincare + coiffure</li>';
            content += '</ul>';
        } else if (type === 'tasks') {
            content = '<p><strong>Limite de tâches atteinte !</strong></p>';
            content += '<p>Vous avez atteint la limite de <strong>' + constants.MAX_TASKS + ' tâches</strong> au total.</p>';
            content += '<p style="margin-top:16px;">💡 <strong>Conseil :</strong> Combinez plusieurs petites tâches en une seule. Par exemple :</p>';
            content += '<ul style="margin-left:20px;margin-top:8px;line-height:1.8;">';
            content += '<li>"Se brosser les dents" + "Se raser" + "Prendre une douche" = <strong>"Hygiène matinale"</strong></li>';
            content += '<li>"Ranger le bureau" + "Organiser les documents" = <strong>"Organisation de l\'espace"</strong></li>';
            content += '<li>"Étirements" + "Respiration" + "Méditation" = <strong>"Routine bien-être"</strong></li>';
            content += '</ul>';
            content += '<p style="margin-top:16px;">Cela vous aidera à mieux structurer vos routines et à rester concentré sur l\'essentiel ! ✨</p>';
        }
        document.getElementById('limitWarningContent').innerHTML = content;
        window.openPopup('limitWarningPopup');
    }

    function renderIconPaginationControls(iconArray, currentPage, onPageChange, containerId) {
        const totalPages = Math.ceil(iconArray.length / constants.ICONS_PER_PAGE);
        const container = document.getElementById(containerId);
        if (!container) return;
        
        let html = '';
        if (totalPages > 1) {
            html += '<button class="icon-pagination-btn" ' + (currentPage === 0 ? 'disabled' : '') + ' data-action="' + onPageChange + '" data-dir="-1">';
            html += '<span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span></button>';
            html += '<span class="icon-pagination-info">Page ' + (currentPage + 1) + ' / ' + totalPages + '</span>';
            html += '<button class="icon-pagination-btn" ' + (currentPage === totalPages - 1 ? 'disabled' : '') + ' data-action="' + onPageChange + '" data-dir="1">';
            html += '<span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span></button>';
        }
        container.innerHTML = html;
        
        container.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                const dir = parseInt(this.dataset.dir);
                if (action === 'changeRoutineIconPage') changeRoutineIconPage(dir);
                else if (action === 'changeTaskIconPage') changeTaskIconPage(dir);
                else if (action === 'changeEditTaskIconPage') changeEditTaskIconPage(dir);
            });
        });
    }

    function changeRoutineIconPage(direction) {
        const totalPages = Math.ceil(constants.ICONS.length / constants.ICONS_PER_PAGE);
        routineIconPage = Math.max(0, Math.min(totalPages - 1, routineIconPage + direction));
        renderIconPicker();
    }

    function changeTaskIconPage(direction) {
        const totalPages = Math.ceil(constants.TASK_ICONS.length / constants.ICONS_PER_PAGE);
        taskIconPage = Math.max(0, Math.min(totalPages - 1, taskIconPage + direction));
        renderTaskIconPicker();
    }

    function changeEditTaskIconPage(direction) {
        const totalPages = Math.ceil(constants.TASK_ICONS.length / constants.ICONS_PER_PAGE);
        editTaskIconPage = Math.max(0, Math.min(totalPages - 1, editTaskIconPage + direction));
        renderEditTaskIconPicker();
    }

    function renderIconPicker() {
        const startIdx = routineIconPage * constants.ICONS_PER_PAGE;
        const endIdx = Math.min(startIdx + constants.ICONS_PER_PAGE, constants.ICONS.length);
        const iconsToShow = constants.ICONS.slice(startIdx, endIdx);
        
        const html = iconsToShow.map(icon =>
            '<div class="icon-option ' + (selectedRoutineIcon === icon ? 'selected' : '') + '" data-icon="' + icon + '">' +
            '<span class="material-symbols-outlined" style="font-size:24px;color:black;">' + icon + '</span></div>'
        ).join('');
        
        document.getElementById('iconPicker').innerHTML = html;
        renderIconPaginationControls(constants.ICONS, routineIconPage, 'changeRoutineIconPage', 'routineIconPagination');
        
        document.querySelectorAll('#iconPicker [data-icon]').forEach(el => {
            el.addEventListener('click', function() {
                selectedRoutineIcon = this.dataset.icon;
                renderIconPicker();
            });
        });
        
        addSwipeToContainer('iconPicker', () => changeRoutineIconPage(1), () => changeRoutineIconPage(-1));
    }

    function renderTaskIconPicker() {
        const startIdx = taskIconPage * constants.ICONS_PER_PAGE;
        const endIdx = Math.min(startIdx + constants.ICONS_PER_PAGE, constants.TASK_ICONS.length);
        const iconsToShow = constants.TASK_ICONS.slice(startIdx, endIdx);
        
        const html = iconsToShow.map(icon =>
            '<div class="icon-option ' + (selectedTaskIcon === icon ? 'selected' : '') + '" data-icon="' + icon + '">' +
            '<span class="material-symbols-outlined" style="font-size:24px;color:black;">' + icon + '</span></div>'
        ).join('');
        
        document.getElementById('taskIconPicker').innerHTML = html;
        renderIconPaginationControls(constants.TASK_ICONS, taskIconPage, 'changeTaskIconPage', 'taskIconPagination');
        
        document.querySelectorAll('#taskIconPicker [data-icon]').forEach(el => {
            el.addEventListener('click', function() {
                selectedTaskIcon = this.dataset.icon;
                renderTaskIconPicker();
            });
        });
        
        addSwipeToContainer('taskIconPicker', () => changeTaskIconPage(1), () => changeTaskIconPage(-1));
    }

    function renderEditTaskIconPicker() {
        const startIdx = editTaskIconPage * constants.ICONS_PER_PAGE;
        const endIdx = Math.min(startIdx + constants.ICONS_PER_PAGE, constants.TASK_ICONS.length);
        const iconsToShow = constants.TASK_ICONS.slice(startIdx, endIdx);
        
        const html = iconsToShow.map(icon =>
            '<div class="icon-option ' + (selectedEditTaskIcon === icon ? 'selected' : '') + '" data-icon="' + icon + '">' +
            '<span class="material-symbols-outlined" style="font-size:24px;color:black;">' + icon + '</span></div>'
        ).join('');
        
        document.getElementById('editTaskIconPicker').innerHTML = html;
        renderIconPaginationControls(constants.TASK_ICONS, editTaskIconPage, 'changeEditTaskIconPage', 'editTaskIconPagination');
        
        document.querySelectorAll('#editTaskIconPicker [data-icon]').forEach(el => {
            el.addEventListener('click', function() {
                selectedEditTaskIcon = this.dataset.icon;
                renderEditTaskIconPicker();
            });
        });
        
        addSwipeToContainer('editTaskIconPicker', () => changeEditTaskIconPage(1), () => changeEditTaskIconPage(-1));
    }

    function addSwipeToContainer(containerId, onSwipeLeft, onSwipeRight) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        let touchStartX = 0;
        let touchEndX = 0;
        
        container.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        container.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchEndX < touchStartX - 50 && onSwipeLeft) onSwipeLeft();
            if (touchEndX > touchStartX + 50 && onSwipeRight) onSwipeRight();
        }, { passive: true });
    }

    window.openEditRoutine = function(routineId) {
        editingRoutineId = routineId;
        routineIconPage = 0;
        const routine = state.routines.find(r => r.id === routineId);
        document.getElementById('routineNameInput').value = routine.name;
        selectedRoutineIcon = routine.icon;
        renderIconPicker();
        window.openPopup('editRoutinePopup');
    };

    window.saveRoutineEdit = function() {
        const routine = state.routines.find(r => r.id === editingRoutineId);
        routine.name = document.getElementById('routineNameInput').value.trim() || routine.name;
        routine.icon = selectedRoutineIcon;
        localStorage.setItem('routines', JSON.stringify(state.routines));
        window.closePopup('editRoutinePopup');
        render();
    };

    window.deleteRoutine = function(routineId) {
        if (!confirm('Supprimer cette routine complète ?')) return;
        state.routines = state.routines.filter(r => r.id !== routineId);
        localStorage.setItem('routines', JSON.stringify(state.routines));
        render();
    };

    window.openAddTask = function(routineId) {
        if (getTotalTaskCount() >= constants.MAX_TASKS) {
            showLimitWarning('tasks');
            return;
        }
        addingTaskToRoutine = routineId;
        selectedTaskIcon = 'self_improvement';
        taskIconPage = 0;
        document.getElementById('taskNameInput').value = '';
        document.getElementById('taskDurationInput').value = '';
        renderTaskIconPicker();
        window.openPopup('addTaskPopup');
    };

    window.saveNewTask = function() {
        const name = document.getElementById('taskNameInput').value.trim();
        if (!name) return;
        if (getTotalTaskCount() >= constants.MAX_TASKS) {
            showLimitWarning('tasks');
            return;
        }
        const durationVal = document.getElementById('taskDurationInput').value;
        const duration = durationVal === '' ? 15 : parseInt(durationVal);
        const routine = state.routines.find(r => r.id === addingTaskToRoutine);
        const newTask = { id: 't' + Date.now(), name: name, icon: selectedTaskIcon, duration: duration, completed: false };
        routine.tasks.push(newTask);
        localStorage.setItem('routines', JSON.stringify(state.routines));
        window.closePopup('addTaskPopup');
        render();
    };

    window.openEditTask = function(routineId, taskId) {
        editingTaskRoutineId = routineId;
        editingTaskId = taskId;
        editTaskIconPage = 0;
        const routine = state.routines.find(r => r.id === routineId);
        const task = routine.tasks.find(t => t.id === taskId);
        document.getElementById('editTaskNameInput').value = task.name;
        document.getElementById('editTaskDurationInput').value = task.duration;
        selectedEditTaskIcon = task.icon;
        renderEditTaskIconPicker();
        window.openPopup('editTaskPopup');
    };

    window.saveTaskEdit = function() {
        const routine = state.routines.find(r => r.id === editingTaskRoutineId);
        const task = routine.tasks.find(t => t.id === editingTaskId);
        task.name = document.getElementById('editTaskNameInput').value.trim() || task.name;
        const durationVal = document.getElementById('editTaskDurationInput').value;
        task.duration = durationVal === '' ? 15 : parseInt(durationVal);
        task.icon = selectedEditTaskIcon;
        localStorage.setItem('routines', JSON.stringify(state.routines));
        window.closePopup('editTaskPopup');
        render();
    };

    window.deleteTask = function(routineId, taskId) {
        if (!confirm('Supprimer cette tâche ?')) return;
        const routine = state.routines.find(r => r.id === routineId);
        routine.tasks = routine.tasks.filter(t => t.id !== taskId);
        localStorage.setItem('routines', JSON.stringify(state.routines));
        render();
    };

    window.addNewRoutine = function() {
        if (state.routines.length >= constants.MAX_ROUTINES) {
            showLimitWarning('routines');
            return;
        }
        const name = prompt('Nom de la nouvelle routine:');
        if (!name) return;
        const newRoutine = { id: 'r' + Date.now(), name: name, icon: 'wb_sunny', tasks: [] };
        state.routines.push(newRoutine);
        localStorage.setItem('routines', JSON.stringify(state.routines));
        render();
    };

    function render() {
        let html = '';
        
        state.routines.forEach(routine => {
            html += '<div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">';
            html += '<div class="flex items-center justify-between mb-3">';
            html += '<div class="flex items-center gap-2">';
            html += '<span class="material-symbols-outlined text-2xl" style="color:#3B82F6;">' + routine.icon + '</span>';
            html += '<h3 class="font-bold">' + routine.name + '</h3></div>';
            html += '<div class="flex gap-2">';
            html += '<button class="p-2 text-blue-400 hover:bg-blue-950 rounded" data-action="editRoutine" data-id="' + routine.id + '">';
            html += '<span class="material-symbols-outlined">edit</span></button>';
            html += '<button class="p-2 text-red-400 hover:bg-red-950 rounded" data-action="deleteRoutine" data-id="' + routine.id + '">';
            html += '<span class="material-symbols-outlined">delete</span></button></div></div>';
            html += '<div class="space-y-2" id="manage-tasks-' + routine.id + '">';
            
            routine.tasks.forEach(task => {
                html += '<div class="flex items-center justify-between p-2 bg-zinc-950 rounded" data-task-id="' + task.id + '">';
                html += '<span class="material-symbols-outlined text-zinc-600 task-handle" style="cursor:move;font-size:20px;margin-right:8px;">drag_indicator</span>';
                html += '<div class="flex items-center gap-2 flex-1">';
                html += '<span class="material-symbols-outlined">' + task.icon + '</span>';
                html += '<span class="task-name-sofia">' + task.name + ' (' + task.duration + ' min)</span></div>';
                html += '<div class="flex gap-2">';
                html += '<button class="text-blue-400 hover:bg-blue-950 rounded p-1" data-action="editTask" data-routine="' + routine.id + '" data-task="' + task.id + '">';
                html += '<span class="material-symbols-outlined">edit</span></button>';
                html += '<button class="text-red-400 hover:bg-red-950 rounded p-1" data-action="deleteTask" data-routine="' + routine.id + '" data-task="' + task.id + '">';
                html += '<span class="material-symbols-outlined">delete</span></button></div></div>';
            });
            
            html += '</div>';
            html += '<button class="w-full mt-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" data-action="addTask" data-id="' + routine.id + '">';
            html += '<span class="material-symbols-outlined mr-2" style="vertical-align:middle;font-size:18px;">add</span>Ajouter une tâche</button></div>';
        });
        
        document.getElementById('routinesList').innerHTML = html;
        
        const totalTasks = getTotalTaskCount();
        let capacityHtml = '<div class="bg-gradient-to-br from-blue-950 to-blue-900 border border-blue-800 rounded-lg p-4 mt-4 text-center">';
        capacityHtml += '<p class="text-sm text-blue-200 mb-2"><strong>Capacité utilisée</strong></p>';
        capacityHtml += '<div class="flex justify-around items-center">';
        capacityHtml += '<div><div class="text-2xl font-bold text-blue-300">' + state.routines.length + ' / ' + constants.MAX_ROUTINES + '</div>';
        capacityHtml += '<div class="text-[10px] text-blue-300">Routines</div></div>';
        capacityHtml += '<div><div class="text-2xl font-bold text-blue-300">' + totalTasks + ' / ' + constants.MAX_TASKS + '</div>';
        capacityHtml += '<div class="text-[10px] text-blue-300">Tâches</div></div>';
        capacityHtml += '</div></div>';
        document.getElementById('capacityCard').innerHTML = capacityHtml;
        
        document.querySelectorAll('[data-action]').forEach(el => {
            el.addEventListener('click', function() {
                const action = this.dataset.action;
                const id = this.dataset.id;
                const routine = this.dataset.routine;
                const task = this.dataset.task;
                
                if (action === 'editRoutine') window.openEditRoutine(id);
                else if (action === 'deleteRoutine') window.deleteRoutine(id);
                else if (action === 'addTask') window.openAddTask(id);
                else if (action === 'editTask') window.openEditTask(routine, task);
                else if (action === 'deleteTask') window.deleteTask(routine, task);
            });
        });
        
        state.routines.forEach(routine => {
            const el = document.getElementById('manage-tasks-' + routine.id);
            if (el && typeof Sortable !== 'undefined') {
                new Sortable(el, {
                    animation: 150,
                    handle: '.task-handle',
                    onEnd: function (evt) {
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
        
        const addRoutineBtn = document.getElementById('addRoutineBtn');
        if (addRoutineBtn) {
            addRoutineBtn.addEventListener('click', window.addNewRoutine);
        }
    }

    return {
        init: function() {
            render();
        },
        cleanup: function() {
            // Nettoyer
        }
    };

})();
