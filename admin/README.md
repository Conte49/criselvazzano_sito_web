# Pannello Admin CRI Selvazzano

## Accesso
URL: `http://new.criselvazzanodentro.it/admin/`

**Credenziali default:**
- Password: `password`

## Cambiare Password

Genera un nuovo hash:
```php
<?php
echo password_hash('tua_nuova_password', PASSWORD_DEFAULT);
?>
```

Sostituisci l'hash in `config.php` alla riga:
```php
define('ADMIN_PASSWORD_HASH', 'nuovo_hash_qui');
```

## Funzionalità

- ✅ Login sicuro con password hash
- ✅ Lista news con ricerca
- ✅ Crea/Modifica/Elimina news
- ✅ Upload immagine featured
- ✅ Editor WYSIWYG (TinyMCE)
- ✅ Genera automaticamente slug
- ✅ Salva in posts.json e media.json

## Deploy

Carica la cartella `admin/` via FTP nella root del sito.

Assicurati che i permessi siano:
- `admin/*.php` → 644
- `frontend/src/data/*.json` → 644 (scrivibili da PHP)
- `frontend/public/news-images/` → 755 (scrivibile da PHP)
