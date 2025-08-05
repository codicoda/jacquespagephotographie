#!/bin/bash 
set -e
echo "Déploiement en cours..."
git fetch origin
git reset --hard origin/main
echo "Déploiement terminé."