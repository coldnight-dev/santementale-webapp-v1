#!/bin/bash

# Dossier de travail
REPO_DIR="/var/www/html/santementale.org/_app/v1"

# Vérifie si le répertoire existe
if [ ! -d "$REPO_DIR" ]; then
    echo "Erreur : Le répertoire $REPO_DIR n'existe pas."
    exit 1
fi

# Navigue dans le répertoire
cd "$REPO_DIR" || exit 1

# Récupère les changements distants
git pull origin main

# Vérifie s'il y a des changements
if ! git status --porcelain | grep -q .; then
    echo "Aucun changement détecté. Rien à faire."
    exit 0
fi

# Ajoute tous les changements (fichiers modifiés, ajoutés ou supprimés)
git add .

# Crée un commit avec un message timestampé
COMMIT_MSG="Update - $(date '+%Y-%m-%d %H:%M:%S EDT')"
git commit -m "$COMMIT_MSG"

# Pousse vers GitHub
git push origin main

# Vérifie le statut final
if [ $? -eq 0 ]; then
    echo "Synchronisation avec GitHub réussie à $(date '+%Y-%m-%d %H:%M:%S EDT')."
else
    echo "Erreur lors de la synchronisation avec GitHub."
    exit 1
fi
