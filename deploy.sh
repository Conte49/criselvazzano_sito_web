#!/bin/bash

# Script di deploy per new.criselvazzanodentro.it
# Esegui con: ./deploy.sh

echo "[DEPLOY] Inizio deploy su www.criselvazzanodentro.it"

# Build del progetto
echo "[BUILD] Build del frontend..."
cd frontend
npm run build

if [ $? -ne 0 ]; then
    echo "[ERRORE] Errore durante la build"
    exit 1
fi

echo "[OK] Build completata"

# Upload via FTP
echo "[UPLOAD] Upload dei file via FTP..."

cd ..

lftp -u criselva,xY1uzS452k new.criselvazzanodentro.it <<EOF
set ftp:ssl-allow no
cd www.criselvazzanodentro.it
mirror -R --only-newer --verbose --exclude data/ frontend/dist .
mirror -R --only-newer --verbose admin admin
bye
EOF

if [ $? -eq 0 ]; then
    echo "[OK] Deploy completato con successo!"
    echo "[INFO] Sito disponibile su: https://www.criselvazzanodentro.it"
    echo "[INFO] Admin disponibile su: https://www.criselvazzanodentro.it/admin/"
else
    echo "[ERRORE] Errore durante l'upload FTP"
    exit 1
fi
