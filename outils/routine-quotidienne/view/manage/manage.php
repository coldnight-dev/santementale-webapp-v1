<div id="manageView">
    <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4 mb-4 text-center">
        <p class="text-zinc-400 italic">C'est sur cette page que vous définissez vos routines et les tâches associées.</p>
    </div>
    <div id="routinesList" class="space-y-4"></div>
    <button id="addRoutineBtn" class="w-full py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700">
        <span class="material-symbols-outlined mr-2" style="vertical-align:middle;">add</span>Nouvelle Routine
    </button>
    <div id="capacityCard"></div>
</div>

<!-- Popups de gestion -->
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
        <button class="close-btn" onclick="window.saveRoutineEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button>
        <button class="close-btn" onclick="window.closePopup('editRoutinePopup')" style="width:100%;">Annuler</button>
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
        <button class="close-btn" onclick="window.saveNewTask()" style="width:100%;background:#3B82F6;color:white;">Ajouter</button>
        <button class="close-btn" onclick="window.closePopup('addTaskPopup')" style="width:100%;">Annuler</button>
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
        <button class="close-btn" onclick="window.saveTaskEdit()" style="width:100%;background:#3B82F6;color:white;">Enregistrer</button>
        <button class="close-btn" onclick="window.closePopup('editTaskPopup')" style="width:100%;">Annuler</button>
    </div>
</div>

<div id="limitWarningPopup" class="popup limit-warning-popup">
    <div class="popup-content">
        <h3>⚠️ Limite atteinte</h3>
        <div id="limitWarningContent"></div>
        <button class="close-btn" onclick="window.closePopup('limitWarningPopup')" style="width:100%;margin-top:15px;">J'ai compris</button>
    </div>
</div>
