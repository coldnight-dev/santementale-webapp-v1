#!/bin/bash

# Récupérer le nom de la branche locale
BRANCHE_LOCALE=$(git rev-parse --abbrev-ref HEAD)

# Récupérer la branche distante suivie automatiquement
BRANCHE_DISTANTE=$(git rev-parse --abbrev-ref --symbolic-full-name @{u} 2>/dev/null)

if [ -z "$BRANCHE_DISTANTE" ]; then
    echo "❌ La branche locale '$BRANCHE_LOCALE' n'a pas de branche distante configurée."
    exit 1
fi

echo "⚠️ Attention : cette opération remplacera tous les fichiers locaux non enregistrés par la version distante '$BRANCHE_DISTANTE'."
read -p "Souhaitez-vous continuer ? (O/N) : " confirm

if [[ "$confirm" =~ ^[Oo]$ ]]; then
    echo "Récupération des dernières modifications depuis le dépôt distant et mise à jour des fichiers locaux..."
    git fetch origin && git reset --hard "$BRANCHE_DISTANTE"
    echo "✅ Opération terminée. Votre répertoire local est désormais synchronisé avec '$BRANCHE_DISTANTE'."
else
    echo "❌ Opération annulée."
fi
