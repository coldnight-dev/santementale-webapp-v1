<div class="max-w-2xl mx-auto px-4 py-4">
    
    <!-- Actions rapides -->
    <div class="mb-6">
        <button id="addDecisionBtn" class="w-full p-4 bg-blue-600 rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-blue-700 transition">
            <span class="material-symbols-outlined">add</span>
            Nouvelle décision à analyser
        </button>
    </div>

    <!-- Liste des décisions actives -->
    <div id="decisionsContainer"></div>

</div>

<!-- Popup nouvelle décision -->
<div id="newDecisionPopup" class="popup">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Nouvelle décision</h2>
            <span class="popup-close material-symbols-outlined" onclick="closePopup('newDecisionPopup')">close</span>
        </div>
        <form id="newDecisionForm">
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Quelle décision souhaitez-vous analyser ?</label>
                <input type="text" id="decisionTitle" class="w-full p-3 bg-zinc-800 border border-zinc-700 rounded-lg" placeholder="Ex: Déménager dans une nouvelle ville" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Icône</label>
                <div id="iconSelector" class="grid grid-cols-6 gap-2"></div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Importance (1-10)</label>
                <input type="range" id="decisionImportance" min="1" max="10" value="5" class="w-full">
                <div class="text-center text-2xl font-bold" id="importanceDisplay">5</div>
            </div>
            <button type="submit" class="w-full p-3 bg-blue-600 rounded-lg font-bold hover:bg-blue-700 transition">
                Créer la décision
            </button>
        </form>
    </div>
</div>

<!-- Popup détails décision -->
<div id="decisionDetailPopup" class="popup">
    <div class="popup-content" style="max-width: 600px;">
        <div class="popup-header">
            <h2 id="detailTitle"></h2>
            <span class="popup-close material-symbols-outlined" onclick="closePopup('decisionDetailPopup')">close</span>
        </div>
        
        <div id="decisionDetailContent"></div>
    </div>
</div>
