<div id="statsView">
    <div class="space-y-4">
        <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-4">
            <h3 class="font-bold mb-3">
                <span class="material-symbols-outlined mr-2" style="vertical-align:middle;">show_chart</span>7 derniers jours
            </h3>
            <div class="chart-container">
                <canvas id="statsChart"></canvas>
            </div>
        </div>
        
        <button id="shareBtn" class="w-full py-3 text-white font-bold rounded-lg flex items-center justify-center gap-2" style="transition:all 0.2s;background:linear-gradient(135deg,#0d47a1 0%,#1976d2 100%);">
            <span class="material-symbols-outlined">share</span>Partager
        </button>
        
        <div id="summaryCards"></div>
        
        <div id="totalTimeCard"></div>
    </div>
</div>
