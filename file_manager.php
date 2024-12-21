<?php

function fileManager($action, $path, $newContent = '') {
    $baseDir = '/var/www';
    
    $realPath = realpath(dirname($path));
    if (!$realPath || strpos($realPath, $baseDir) !== 0) {
        echo "Errore: Il percorso $path non è valido o non rientra nella directory consentita.";
        return;
    }

    if (empty($path)) {
        echo "Errore: Il percorso non può essere vuoto.";
        return;
    }

    if (!is_writable($baseDir)) {
        echo "Errore: La directory base non è scrivibile.";
        return;
    }

    switch ($action) {
        case 'create':
            if (file_exists($path)) {
                echo "Errore: Il file $path esiste già.";
                return;
            }
            if (file_put_contents($path, '') !== false) {
                echo "File creato: $path";
            } else {
                echo "Errore durante la creazione del file: $path";
            }
            break;

        case 'create_dir':
            if (file_exists($path)) {
                echo "Errore: La cartella $path esiste già.";
                return;
            }
            if (mkdir($path, 0777, true)) {
                echo "Cartella creata: $path";
            } else {
                echo "Errore durante la creazione della cartella: $path";
            }
            break;

        case 'delete':
            if (!file_exists($path)) {
                echo "Errore: Il file o la cartella $path non esiste.";
                return;
            }
            if (is_file($path)) {
                if (!is_writable($path)) {
                    echo "Errore: Non hai i permessi per eliminare il file $path.";
                    return;
                }
                if (unlink($path)) {
                    echo "File eliminato: $path";
                } else {
                    echo "Errore durante l'eliminazione del file: $path";
                }
            } elseif (is_dir($path)) {
                if (!is_writable($path)) {
                    echo "Errore: Non hai i permessi per eliminare la cartella $path.";
                    return;
                }
                if (rmdir($path)) {
                    echo "Cartella eliminata: $path";
                } else {
                    echo "Errore durante l'eliminazione della cartella (potrebbe non essere vuota): $path";
                }
            }
            break;

        case 'modify':
            if (!file_exists($path)) {
                echo "Errore: Il file $path non esiste.";
                return;
            }
            if (!is_writable($path)) {
                echo "Errore: Non hai i permessi per modificare il file $path.";
                return;
            }
            if (file_put_contents($path, $newContent) !== false) {
                echo "File modificato: $path";
            } else {
                echo "Errore durante la modifica del file: $path";
            }
            break;

        default:
            echo "Errore: Azione non riconosciuta.";
    }
}
?>
