<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs JS - Routines</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: monospace; 
            background: #0a0a0a; 
            color: #fff; 
            padding: 20px; 
        }
        h1 { 
            color: #3B82F6; 
            margin-bottom: 20px; 
            font-size: 24px; 
        }
        .controls {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        button {
            padding: 10px 20px;
            background: #3B82F6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover { background: #2563eb; }
        button.danger { background: #ef4444; }
        button.danger:hover { background: #dc2626; }
        .log-container {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 15px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .log-entry {
            background: #0a0a0a;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .log-entry.error { border-left-color: #ef4444; }
        .log-entry.warn { border-left-color: #f59e0b; }
        .log-entry.info { border-left-color: #3B82F6; }
        .log-time {
            color: #666;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .log-type {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .log-type.error { background: #7f1d1d; color: #fca5a5; }
        .log-type.warn { background: #78350f; color: #fcd34d; }
        .log-type.info { background: #1e3a8a; color: #93c5fd; }
        .log-message {
            color: #fff;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .log-details {
            color: #999;
            font-size: 11px;
            padding-top: 10px;
            border-top: 1px solid #333;
        }
        .empty {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .stats {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
            background: #0a0a0a;
            border-radius: 6px;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #3B82F6;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>📋 Logs JavaScript - Routines v0.12-alpha</h1>
    
    <?php
    $logFile = __DIR__ . '/js-errors.log';
    $logExists = file_exists($logFile);
    $logSize = $logExists ? filesize($logFile) : 0;
    $logContent = $logExists ? file_get_contents($logFile) : '';
    $logLines = $logExists ? count(file($logFile)) : 0;
    
    // Traiter l'action de suppression
    if (isset($_POST['clear']) && $_POST['clear'] === 'true') {
        file_put_contents($logFile, '');
        header('Location: view-logs.php');
        exit;
    }
    
    // Statistiques
    $errorCount = substr_count($logContent, '[ERROR]');
    $warningCount = substr_count($logContent, '[WARN]');
    $viewErrorCount = substr_count($logContent, 'view_load_error');
    ?>
    
    <div class="stats">
        <div class="stat-item">
            <div class="stat-value"><?php echo $logLines; ?></div>
            <div class="stat-label">Entrées totales</div>
        </div>
        <div class="stat-item">
            <div class="stat-value" style="color:#ef4444;"><?php echo $errorCount; ?></div>
            <div class="stat-label">Erreurs</div>
        </div>
        <div class="stat-item">
            <div class="stat-value" style="color:#f59e0b;"><?php echo $warningCount; ?></div>
            <div class="stat-label">Warnings</div>
        </div>
        <div class="stat-item">
            <div class="stat-value" style="color:#dc2626;"><?php echo $viewErrorCount; ?></div>
            <div class="stat-label">Erreurs de vue</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo round($logSize / 1024, 1); ?> KB</div>
            <div class="stat-label">Taille fichier</div>
        </div>
    </div>
    
    <div class="controls">
        <button onclick="location.reload()">🔄 Actualiser</button>
        <button onclick="downloadLogs()">💾 Télécharger</button>
        <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer tous les logs ?');">
            <input type="hidden" name="clear" value="true">
            <button type="submit" class="danger">🗑️ Vider les logs</button>
        </form>
        <button onclick="window.location.href='index.php'">← Retour à l'app</button>
    </div>
    
    <div class="log-container">
        <?php if (!$logExists || $logSize === 0): ?>
            <div class="empty">
                <p style="font-size:48px;margin-bottom:20px;">✅</p>
                <p>Aucune erreur enregistrée</p>
                <p style="font-size:12px;color:#666;margin-top:10px;">Les erreurs JavaScript seront automatiquement capturées ici</p>
            </div>
        <?php else:
            // Lire et afficher les logs (les plus récents en premier)
            $entries = explode("  ---\n\n", $logContent);
            $entries = array_reverse($entries);
            $entries = array_filter($entries);
            
            foreach ($entries as $entry):
                if (empty(trim($entry))) continue;
                
                // Parser l'entrée
                preg_match('/\[(.*?)\] \[(.*?)\]/', $entry, $matches);
                $time = $matches[1] ?? 'N/A';
                $type = strtolower($matches[2] ?? 'info');
                
                $class = 'error';
                if (strpos($type, 'warn') !== false) $class = 'warn';
                elseif (strpos($type, 'info') !== false) $class = 'info';
                ?>
                <div class="log-entry <?php echo $class; ?>">
                    <div class="log-time"><?php echo htmlspecialchars($time); ?></div>
                    <div class="log-type <?php echo $class; ?>"><?php echo strtoupper($type); ?></div>
                    <div class="log-message"><?php echo nl2br(htmlspecialchars($entry)); ?></div>
                </div>
            <?php endforeach;
        endif; ?>
    </div>
    
    <script>
        function downloadLogs() {
            window.location.href = 'log-error.php?download=1';
        }
        
        // Auto-scroll vers le bas au chargement
        window.addEventListener('load', () => {
            const container = document.querySelector('.log-container');
            if (container.scrollHeight > container.clientHeight) {
                container.scrollTop = 0; // Les plus récents sont en haut
            }
        });
    </script>
</body>
</html>
