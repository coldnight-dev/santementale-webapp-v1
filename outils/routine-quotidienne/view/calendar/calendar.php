<div id="calendarView">
    <div id="calendarGrid"></div>
    <div style="margin-top:20px;"></div>
    <div id="historyList"></div>
</div>

<!-- Popup détail du jour -->
<div id="dayDetailPopup" class="popup day-detail-popup">
    <div class="popup-content" style="text-align:left; position:relative;">
        <div class="whats-new-header">
            <h3 id="dayDetailTitle" style="font-weight:bold;margin-bottom:15px;text-align:center;color:#000;"></h3>
            <button class="whats-new-close" onclick="window.closeDayDetail()" style="display:block;">✕</button>
        </div>
        <div id="dayDetailContent"></div>
        <button class="close-btn" onclick="window.closeDayDetail()" style="width:100%;margin-top:15px;">Fermer</button>
    </div>
</div>
