<?php if (!defined('IN_PHPBB')) exit; ?>Subject: Benvingut a "<?php echo (isset($this->_rootref['SITENAME'])) ? $this->_rootref['SITENAME'] : ''; ?>"

<?php echo (isset($this->_rootref['WELCOME_MSG'])) ? $this->_rootref['WELCOME_MSG'] : ''; ?>


Si us plau, guardeu aquest correu electrònic per a la vostra informació. Les dades del vostre compte són les següents:

----------------------------
Nom d'usuari: <?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>


URL del fòrum: <?php echo (isset($this->_rootref['U_BOARD'])) ? $this->_rootref['U_BOARD'] : ''; ?>

----------------------------

La vostra contrasenya està emmagatzemada de forma segura a la base de dades i no podeu recuperar el text desencriptat. Si l'oblideu, podeu reiniciar-la utilitzant el correu electrònic associat al vostre compte.

Gràcies per registrar-vos.

<?php echo (isset($this->_rootref['EMAIL_SIG'])) ? $this->_rootref['EMAIL_SIG'] : ''; ?>