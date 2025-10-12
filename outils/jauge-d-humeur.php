<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SanteMentale.org - Jauge d’Humeur</title>
    <link rel="icon" type="image/x-icon" href="https://santementale.org/favicon.ico">
    <!-- Manifest PWA (désactivé pour éviter le popup installation) -->
    <link rel="manifest" href="/v1/manifest.json">
    <!-- Couleur de la barre sur mobile -->
    <meta name="theme-color" content="#2c3e50">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="https://santementale.org/logo.png">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- html2pdf.js CDN pour l'export PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        /* Réinitialisation et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
        }
        /* Conteneur principal */
        .container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            min-height: 100%;
            text-align: center;
            padding: 5%;
            width: 90%;
            margin: 0 auto;
        }
        /* Logo */
        .logo {
            width: 15vw;
            max-width: 75px;
            height: auto;
            margin-bottom: 20px;
        }
        /* Titres et textes */
        h1 {
            font-size: clamp(18px, 5.5vw, 24px);
            margin-bottom: 15px;
        }
        p {
            font-size: 4vw;
            max-width: 90%;
            line-height: 1.5;
            margin-bottom: 25px;
        }
        /* Zone d'entrée */
        .gratitude-input {
            width: 100%;
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #0d47a1;
            margin-bottom: 20px;
        }
        .gratitude-input input[type="range"] {
            width: 80%;
            max-width: 300px;
            appearance: none;
            height: 10px;
            background: #333;
            border-radius: 5px;
            outline: none;
            opacity: 0.7;
            transition: opacity .2s;
            margin-top: 10px;
        }
        .gratitude-input input[type="range"]:hover {
            opacity: 1;
        }
        .gratitude-input input[type="range"]::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            background: #0d47a1;
            border-radius: 50%;
            cursor: pointer;
        }
        .mood-value {
            font-size: 1.5em;
            color: #0d47a1;
            margin-top: 10px;
        }
        .notes {
            width: 100%;
            min-height: 100px;
            background: transparent;
            border: none;
            color: #ccc;
            font-size: 16px;
            resize: vertical;
            margin-top: 10px;
        }
        .notes:focus {
            outline: none;
        }
        .char-count {
            text-align: right;
            color: #888;
            font-size: 14px;
        }
        /* Boutons */
        .submit-btn, .reset-btn, .export-btn {
            padding: 10px 20px;
            background-color: #0d47a1;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none; /* Pas de soulignement */
        }
        .submit-btn {
            display: block; /* Seul sur sa ligne */
            width: fit-content; /* Ajuste la largeur au contenu */
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 10px; /* Espacement avant la ligne suivante */
        }
        .reset-btn {
            background-color: #c0392b;
        }
        .export-btn {
            background-color: #7f8c8d; /* Gris à la place du vert */
        }
        .button-container {
            display: flex;
            flex-direction: column; /* Empile les lignes */
            align-items: center; /* Centre horizontalement */
            gap: 10px; /* Espacement entre les lignes */
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px; /* Espacement entre Vider et Exporter */
        }
        /* Liste des entrées */
        .entries-list {
            width: 100%;
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #0d47a1;
            margin-bottom: 20px;
        }
        .entry-item {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }
        .entry-item:last-child {
            border-bottom: none;
        }
        .entry-date {
            font-size: 14px;
            color: #888;
            margin-bottom: 5px;
        }
        .entry-text {
            font-size: 16px;
            color: #ccc;
        }
        /* Notification temporaire */
        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #c0392b; /* Rouge */
            color: #fff;
            padding: 15px 25px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        /* Style pour le nom d'utilisateur */
        .username {
            color: #ddd;
            cursor: pointer;
            margin-left: 5px;
        }
        .username:hover {
            text-decoration: underline;
        }
        .pencil-icon {
            width: 14px;
            height: 14px;
            margin-right: 5px;
            vertical-align: middle;
        }
        /* Popup (À propos, Confidentialité, Username) */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .popup-content {
            background: white;
            color: black;
            width: 80%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .popup-content p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .close-btn {
            padding: 10px 20px;
            background: #ccc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        /* Style pour le lien À propos et Confidentialité */
        .about-link {
            color: #0d47a1;
            text-decoration: none;
            cursor: pointer;
        }
        .about-link:hover {
            text-decoration: underline;
        }
        /* Style pour le formulaire dans la popup */
        .username-input {
            padding: 8px;
            width: 80%;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        /* Gestion du mode paysage */
        @media (orientation: landscape) {
            body {
                transform: rotate(90deg);
                transform-origin: left top;
                width: 100vh;
                height: 100vw;
                overflow-x: hidden;
                position: absolute;
                top: 100%;
                left: 0;
            }
            .container {
                padding: 3%;
            }
        }
        /* Ajustement pour petits écrans */
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Popup À propos -->
    <div id="aboutPopup" class="popup">
        <div class="popup-content">
            <p>©2025 SanteMentale.org, tous droits réservés</p>
            <p>API : 0.dev</p>
            <p>App : <?php echo htmlspecialchars($_COOKIE['client_version'] ?? 'Non définie'); ?></p>
            <button class="close-btn" onclick="document.getElementById('aboutPopup').style.display = 'none';">Fermer</button>
        </div>
    </div>
    <!-- Popup Confidentialité -->
    <div id="privacyPopup" class="popup">
        <div class="popup-content">
            <p>SanteMentale.org respecte votre vie privée. Aucune donnée personnelle n’est collectée ni enregistrée. Toutes les données que vous entrez dans les formulaires sont stockées uniquement sur votre appareil.</p>
            <button class="close-btn" onclick="document.getElementById('privacyPopup').style.display = 'none';">Fermer</button>
        </div>
    </div>
    <!-- Popup pour modifier le nom d'utilisateur -->
    <div id="usernamePopup" class="popup">
        <div class="popup-content">
            <p>Entrez le nom à afficher sur les rapports PDF que vous générez.</p>
            <input type="text" id="usernameInput" class="username-input" placeholder="Votre nom">
            <button class="close-btn" onclick="saveUsername()">Enregistrer</button>
            <button class="close-btn" onclick="document.getElementById('usernamePopup').style.display = 'none';">Annuler</button>
        </div>
    </div>
    <!-- Notification -->
    <div id="notification" class="notification"></div>
    <!-- Contenu principal -->
    <div class="container">
        <img src="https://santementale.org/logo.png" alt="Logo SanteMentale.org" class="logo">
        <h1 id="welcomeMessage"></h1>
        <p>Enregistrez votre niveau d’humeur quotidien pour suivre votre bien-être.</p>
        <!-- Zone d'entrée -->
        <div class="gratitude-input">
            <label for="mood">Niveau d’humeur (1-10) :</label>
            <input type="range" id="mood" min="1" max="10" value="5">
            <div class="mood-value" id="moodValue">5</div>
            <textarea id="notes" class="notes" placeholder="Ajoute des notes sur ton humeur..." maxlength="200"></textarea>
            <div class="char-count">0/200</div>
        </div>
        <div class="button-container">
            <button class="submit-btn" onclick="saveMood()"><i class="fas fa-save"></i> Enregistrer</button>
            <div class="button-group">
                <button class="reset-btn" onclick="clearAll()"><i class="fas fa-trash"></i> Vider</button>
                <button class="export-btn" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Exporter en PDF</button>
            </div>
        </div>
        <!-- Liste des entrées -->
        <div id="entriesList" class="entries-list">
            <!-- Entrées dynamiques ici -->
        </div>
        <!-- Bouton Retour -->
        <a href="index.php" class="submit-btn" style="margin-top: 20px;"><i class="fas fa-arrow-left"></i> Retour</a>
        <p style="margin-top: auto; color: #333;">
            v<?php echo htmlspecialchars($_COOKIE['client_version'] ?? 'Non définie'); ?> • Accès anticipé<br>
            <a class="about-link" onclick="document.getElementById('aboutPopup').style.display = 'flex';">À propos</a> &bull; <a class="about-link" onclick="document.getElementById('privacyPopup').style.display = 'flex';">Confidentialité</a><br/>
            <span style="color: #161616;">&copy;2025 SanteMentale.org</span><br>
        </p>
    </div>
    <script>
        // Gestion du nom d'utilisateur
        document.addEventListener('DOMContentLoaded', () => {
            const usernameKey = 'username';
            let username = localStorage.getItem(usernameKey);
            if (!username) {
                localStorage.setItem(usernameKey, 'visiteur');
                username = 'visiteur';
            }
            const welcomeMessage = document.getElementById('welcomeMessage');
            welcomeMessage.innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="document.getElementById('usernamePopup').style.display = 'flex';">${username},</span>`;
            // Charger les entrées existantes
            loadEntries();
            // Mettre à jour la valeur de la jauge
            const moodInput = document.getElementById('mood');
            const moodValue = document.getElementById('moodValue');
            moodInput.addEventListener('input', () => {
                moodValue.textContent = moodInput.value;
            });
            // Mettre à jour le compteur de caractères
            const notesInput = document.getElementById('notes');
            const charCount = document.querySelector('.char-count');
            notesInput.addEventListener('input', () => {
                charCount.textContent = `${notesInput.value.length}/200`;
            });
        });

        // Fonction pour enregistrer le nom d'utilisateur
        window.saveUsername = function() {
            const input = document.getElementById('usernameInput');
            const newUsername = input.value.trim() || 'visiteur';
            localStorage.setItem('username', newUsername);
            document.getElementById('welcomeMessage').innerHTML = `<svg class="pencil-icon" viewBox="0 0 24 24" fill="#161616"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><span class="username" onclick="document.getElementById('usernamePopup').style.display = 'flex';">${newUsername},</span>`;
            document.getElementById('usernamePopup').style.display = 'none';
        };

        // Sauvegarder une entrée d'humeur
        function saveMood() {
            const moodInput = document.getElementById('mood');
            const notesInput = document.getElementById('notes');
            const mood = moodInput.value;
            const notes = notesInput.value.trim();
            if (mood) {
                const now = new Date();
                let entries = JSON.parse(localStorage.getItem('mood_entries') || '[]');
                entries.push({ date: now.getTime(), mood, notes });
                localStorage.setItem('mood_entries', JSON.stringify(entries));
                notesInput.value = '';
                loadEntries();
            } else {
                alert('Veuillez sélectionner un niveau d’humeur.');
            }
        }

        // Charger et afficher les entrées
        function loadEntries() {
            const entriesList = document.getElementById('entriesList');
            const entries = JSON.parse(localStorage.getItem('mood_entries') || '[]');
            entriesList.innerHTML = '';
            if (entries.length > 0) {
                entries.forEach(e => {
                    const date = new Date(e.date);
                    const formattedDate = `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
                    const entryDiv = document.createElement('div');
                    entryDiv.className = 'entry-item';
                    entryDiv.innerHTML = `
                        <div class="entry-date">${formattedDate}</div>
                        <div class="entry-text">${e.mood}/10 - ${e.notes || 'Aucune'}</div>
                    `;
                    entriesList.appendChild(entryDiv);
                });
            } else {
                entriesList.innerHTML = '<p>Aucune entrée pour l\'instant.</p>';
            }
        }

        // Effacer toutes les entrées
        function clearAll() {
            if (confirm('Êtes-vous sûr de vouloir effacer toutes les entrées ? Cette action est irréversible.')) {
                localStorage.removeItem('mood_entries');
                loadEntries();
            }
        }

        // Exporter en PDF
        function exportToPDF() {
            const username = localStorage.getItem('username') || 'visiteur';
            const date = new Date().toLocaleString('fr-FR');
            const fileName = `Jauge_d_Humeur_${username}_${date.split(',')[0].replace(/\//g, '-')}.pdf`;
            if (confirm(`Voulez-vous exporter votre jauge d’humeur en PDF sous le nom "${fileName}" ?`)) {
                const entries = JSON.parse(localStorage.getItem('mood_entries') || '[]');
                const content = `
                    <h1 style="text-align: center; color: #0d47a1;">Jauge d’Humeur - ${username}</h1>
                    <h2 style="text-align: center; color: #333;">Rapport généré le ${date}</h2>
                    ${entries.map(e => {
                        const date = new Date(e.date);
                        const formattedDate = `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
                        return `
                            <div style="margin: 20px 0; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                                <h3 style="color: #888;">${formattedDate}</h3>
                                <p style="color: #000;">${e.mood}/10 - ${e.notes || 'Aucune'}</p>
                            </div>
                        `;
                    }).join('')}
                `;
                const element = document.createElement('div');
                element.innerHTML = content;
                html2pdf().from(element).save(fileName);
                // Afficher la notification
                const notification = document.getElementById('notification');
                notification.textContent = `PDF "${fileName}" téléchargé ! Vérifiez votre répertoire de téléchargements.`;
                notification.style.display = 'block';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000); // Disparaît après 5 secondes
            }
        }
    </script>
</body>
</html>
