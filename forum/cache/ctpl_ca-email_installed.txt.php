<?php if (!defined('IN_PHPBB')) exit; ?>Subject: S'ha instal·lat el phpBB

Felicitats,

Heu instal·lat correctament el phpBB en el vostre servidor.

Aquest correu electrònic conté informació important sobre la instal·lació i és recomanable que el conserveu per a futures referències. La vostra contrasenya està emmagatzemada de forma segura a la base de dades i no podeu recuperar el text desencriptat. Si l'oblideu, podeu reiniciar-la utilitzant el correu electrònic associat al vostre compte.

----------------------------
Nom d'usuari: <?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>


URL del fòrum: <?php echo (isset($this->_rootref['U_BOARD'])) ? $this->_rootref['U_BOARD'] : ''; ?>

----------------------------

Podeu trobar informació útil sobre el phpBB al directori "docs" i a la pàgina d'assistència de phpBB.com - http://www.phpbb.com/support/

Per mantenir els fòrums segurs és molt recomanable que mantingueu el fòrum actualitzat amb les versions més recents. Per a la vostra comoditat, hi ha una llista de correu disponible a la pàgina referenciada a sobre.

<?php echo (isset($this->_rootref['EMAIL_SIG'])) ? $this->_rootref['EMAIL_SIG'] : ''; ?>