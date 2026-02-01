#!/bin/bash

# Script di deploy per new.criselvazzanodentro.it
# Esegui con: ./deploy.sh

echo "🚀 Inizio deploy su www.criselvazzanodentro.it"

# Build del progetto
echo "📦 Build del frontend..."
cd frontend
npm run build

if [ $? -ne 0 ]; then
    echo "❌ Errore durante la build"
    exit 1
fi

echo "✅ Build completata"

# Upload via FTP
echo "📤 Upload dei file via FTP..."

cd ..

lftp -u criselva,xY1uzS452k new.criselvazzanodentro.it <<EOF
set ftp:ssl-allow no
cd www.criselvazzanodentro.it
mirror -R --only-newer --verbose --exclude data/ frontend/dist .
mirror -R --only-newer --verbose admin admin
bye
EOF

if [ $? -eq 0 ]; then
    echo "✅ Deploy completato con successo!"
    echo "🌐 Sito disponibile su: https://www.criselvazzanodentro.it"
    echo "🔐 Admin disponibile su: https://www.criselvazzanodentro.it/admin/"
else
    echo "❌ Errore durante l'upload FTP"
    exit 1
fi
