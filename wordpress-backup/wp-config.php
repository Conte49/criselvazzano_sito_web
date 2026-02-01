<?php
define('WP_AUTO_UPDATE_CORE', 'minor');// Questa impostazione è necessaria per assicurarsi di gestire in modo appropriato gli aggiornamenti di WordPress in WordPress Toolkit. Rimuovi questa riga se il sito web di WordPress non è più gestito da WordPress Toolkit.
/**
 * Il file base di configurazione di WordPress.
 *
 * Questo file viene utilizzato, durante l’installazione, dallo script
 * di creazione di wp-config.php. Non è necessario utilizzarlo solo via
 * web, è anche possibile copiare questo file in «wp-config.php» e
 * riempire i valori corretti.
 *
 * Questo file definisce le seguenti configurazioni:
 *
 * * Impostazioni MySQL
 * * Prefisso Tabella
 * * Chiavi Segrete
 * * ABSPATH
 *
 * È possibile trovare ultetriori informazioni visitando la pagina del Codex:
 *
 * @link https://codex.wordpress.org/it:Modificare_wp-config.php
 *
 * È possibile ottenere le impostazioni per MySQL dal proprio fornitore di hosting.
 *
 * @package WordPress
 */

// ** Impostazioni MySQL - È possibile ottenere queste informazioni dal proprio fornitore di hosting ** //
/** Il nome del database di WordPress */
define('DB_NAME', 'criselva_sitowp');

/** Nome utente del database MySQL */
define('DB_USER', 'crise_sitowp');

/** Password del database MySQL */
define('DB_PASSWORD', 'Gd5yn$30');

/** Hostname MySQL  */
define('DB_HOST', 'localhost');

/** Charset del Database da utilizzare nella creazione delle tabelle. */
define('DB_CHARSET', 'utf8');

/** Il tipo di Collazione del Database. Da non modificare se non si ha idea di cosa sia. */
define('DB_COLLATE', '');

/**#@+
 * Chiavi Univoche di Autenticazione e di Salatura.
 *
 * Modificarle con frasi univoche differenti!
 * È possibile generare tali chiavi utilizzando {@link https://api.wordpress.org/secret-key/1.1/salt/ servizio di chiavi-segrete di WordPress.org}
 * È possibile cambiare queste chiavi in qualsiasi momento, per invalidare tuttii cookie esistenti. Ciò forzerà tutti gli utenti ad effettuare nuovamente il login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+Uo-`M0I4Q*By9:jaG88ll]0zM1A|?Ceh(S3sn(DkR#N|KXUZ;&!`fh,}+;*^H?t');
define('SECURE_AUTH_KEY',  'o;Z2hL6m;4cDVf,g%C?lWYk06.Ja8-JW-OlrOfOhV=9%pyME. -xz2#U-K(BWz:D');
define('LOGGED_IN_KEY',    '4=6_?caf<&?vKs7n?p]ej@u!-$6_9{`>0Hy|#;02t7-M8B;g*}m@,O>OE&COaAV5');
define('NONCE_KEY',        'z+G@`jXc)g!$`^(KJtf&O@iDTv(0:dP>T|+={R~|<pS8pe]#;w>WoJ],}.I]{Q1h');
define('AUTH_SALT',        'n}*>[Q)>*b+MwvZ63:!c@fUF|!?e`Qb6-qkEh|h+v]@C#@HU,^8ST?mKEuxe%H|A');
define('SECURE_AUTH_SALT', 'l43,bC ]Er=X/hMx-`Riwe~Lm,oPd-D uZ9zfC_ 2|-,k`::<w|5uaTGy<h2*T+p');
define('LOGGED_IN_SALT',   'a=of^{cqS2sp^L3d9-Yq<G=Ez{Gq B,)Hhs71umX>uO}{I+Kdw(AJnKZx<~6dc7r');
define('NONCE_SALT',       'ulu=bJv>&~~3Wz@W!0:%)xrISV#zG.;GcH^!3WkL2clnn27;6kG^-})6zzb4[ivl');

/**#@-*/

/**
 * Prefisso Tabella del Database WordPress.
 *
 * È possibile avere installazioni multiple su di un unico database
 * fornendo a ciascuna installazione un prefisso univoco.
 * Solo numeri, lettere e sottolineatura!
 */
$table_prefix  = 'wp_crsede_';

/**
 * Per gli sviluppatori: modalità di debug di WordPress.
 *
 * Modificare questa voce a TRUE per abilitare la visualizzazione degli avvisi
 * durante lo sviluppo.
 * È fortemente raccomandato agli svilupaptori di temi e plugin di utilizare
 * WP_DEBUG all’interno dei loro ambienti di sviluppo.
 */
define('WP_DEBUG', false);

/* Finito, interrompere le modifiche! Buon blogging. */

/** Path assoluto alla directory di WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Imposta le variabili di WordPress ed include i file. */
require_once(ABSPATH . 'wp-settings.php');