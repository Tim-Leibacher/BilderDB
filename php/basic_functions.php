<?php
/*
 *  @autor Tim Leibacher
 *  @version März 2017
 *  Dieses Modul stellt grundlegende Funktionen zur Verfügung und ist damit
 *  Bestandteil des MVC-IET-gibb
 */

/*
 * Liefert die über den Parameter "id" definierte Funktion zurück
 */
function getId() {
  if (isset($_GET['id'])) return $_GET['id'];
  else return "";
}

/*
 * Gibt den Inhalt eines POST-Attributes zurück
 */
function getPost($attr, $defvalue="") {
	$value = $defvalue;
	if (isset($_POST[$attr])) {
		if (!empty($_POST[$attr])) $value = $_POST[$attr];
	}
	return $value;
}

/* Führt ein HTML-Template aus und gibt das Produkt zurück
 * @param     $template     Filename des Templates
 * @param     $params       Assoziativer Array mit Werten, welche im Template eingefügt werden.
 *                          key: Name der Variable, value: Wert
 */
function runTemplate($template) {
  ob_start();
  require($template);
  $inhalt=ob_get_contents();
  ob_end_clean();
  return $inhalt;
}

/*
 * Einen Wert im globalen Array $params speichern.
 * @param       $key        Schlüssel des Wertes (Index im globalen Array
 * @param       $value      Wert des Wertes
 *
 */
function setValue($key, $value) {
  global $params;
  $params[$key] = $value;
}

/*
 * Mehrere Werte im globalen Array $params speichern.
 * @param       $list      Assoziativer Array mit den zu speichernden Werten
 *
 */
function setValues($list) {
  global $params;
  if (count($list)) {
	foreach ($list as $k=>$v) {
	  $params[$k] = $v;
	}
  }
}

/*
 * Wert aus dem globalen Array lesen
 * @param       $field      Index des gewünschten Wetes
 *
 */
function getValue($key) {
  global $params;
  if (isset($params[$key])) return $params[$key];
  else return "";
}

/*
 * Erstellt das Menu und gibt dieses aus. Wird im Haupttemplate aufgerufen.
 * @param   $mlist      Array mit den Menueinträgen. key: ID (Funktion), value: Menuoption
 */
function getMenu($mlist) {
  $menu = "";
  if (count($mlist)) {
	$active_link = getValue("func");
	if (empty($active_link)) $active_link=key($mlist);
	foreach ($mlist as $element=>$option) {
	  $active = "";
	  if ($element == $active_link) $active = " class='active'";
	  $menu .= "<li$active><a href='".$_SERVER['PHP_SELF']."?id=".$element."'>$option</a></li>";
	}
	return $menu;
  }
}

/*
 * Wert aus dem globalen Array lesen und in HTML-Syntax umwandeln
 * @param       $field      Index des gewünschten Wetes
 *
 */
function getHtmlValue($key) {
    global $params;
	if (isset($params[$key])) return htmlentities($params[$key]);
	else return "";
}

/*
 * Bereitet einen Text für die Ausgabe in HTML vor
*/
function htmlTextAufbereiten($value) {
	return nl2br(htmlentities($value));
}

/**
 * Übergebenen String escapen (Sicherheitsgründe!) und zurückgeben
 * @param   $attribut       Attribut, das in eine Tabelle eingefügt werden soll
 */
function escapeSpecialChars($attribut) {
	return mysqli_real_escape_string(getValue("cfg_db"), $attribut);
}

/**
 * Übergebene SQL-Anweisung auf der DB ausführen und Resultat zurückgeben.
 * @param   $sql       Select-Befehl, welcher ausgeführt werden soll
 */
function sqlSelect($sql) {
 	$result = mysqli_query(getValue("cfg_db"), $sql);
 	if (!$result) die("Fehler: ".mysqli_error());
	if (mysqli_num_rows($result) > 0) {
		while ($row=mysqli_fetch_assoc($result)) $data[]=$row;
	} else $data = "";
	mysqli_free_result($result);
	return $data;
}

/**
 * Führt einen SQL-Befehl aus.
 * @param   $sql    SQL-Befehl, welcher ausgeführt werden soll
 */
 function sqlQuery($sql) {
	$result = mysqli_query(getValue("cfg_db"), $sql);
 	if (!$result) die(mysqli_error(getValue("cfg_db"))."<pre>".$sql."</pre>");
}

/**
 * Aktives php-Modul noch einmal aufrufen.
 * @param   $id     ID der Funktion, welche aufgerufen werden soll
 */
function redirect($id="") {
    if (!empty($id)) $id="?id=$id";
    header("Location: ".$_SERVER['PHP_SELF'].$id);
    exit();
}

/**
 * Prüft, ob ein Eingabewert leer ist oder nicht.
 * @param   $value      Eingabewert
 * @param   $minlength  Minimale Länge der Eingabe
 */
function checkEmpty($value, $minlength=Null) {
    if (empty($value)) return false;
    if ($minlength != Null && strlen($value) < $minlength) return false;
    else return true;
}

/**
 * Prüft, ob ein Eingabewert eine bestimmte Länge hat.
 * @param   $value      Eingabewert
 * @param   $length     Geforderte Länge
 */
function checkLength($value, $length) {
    if (strlen($value) != $length) return false;
    else return true;
}

/**
 * Prüft, ob eine Name (Nachname, Vorname, Ort, etc.) korrekt ist oder nicht.
 * @param   $value      Eingabewert
 * @param   $empty      Der Name kann leer sein (true) oder nicht (false)
 */
function checkName($value, $empty=false) {
  $pattern_name = "/^[a-zA-ZäöüAÖÜ \-]{2,}$/";
  if ($empty && empty($value)) return true;
  if (preg_match($pattern_name, $value)) return true;
  else return false;
}

/**
 * Prüft, ob eine Postleitzahl korrekt ist oder nicht.
 * @param   $value      Eingabewert
 * @param   $empty      Die Postleitzahl kann leer sein (true) oder nicht (false)
 */
function checkPLZ($value, $empty=false) {
  $pattern_plz = "/^[0-9]{4}$/";
  if ($empty && empty($value)) return true;
  if (empty($value)) return false;
  if (preg_match($pattern_plz, $value)) return true;
  else return false; 
}

/**
 * Prüft, ob eine Emailadresse korrekt ist oder nicht.
 * @param   $value      Eingabewert
 * @param   $empty      Die Email-Adresse kann leer sein (true) oder nicht (false)
 */
function checkEmail($value, $empty=false) {
  $pattern_email = "/^[^@\s<&>]+@([-a-z0-9]+\.)+[a-z]{2,}$/i";
  if ($empty && empty($value)) return true;
  if (preg_match($pattern_email, $value)) return true;
  else return false;
}

/**
 * Prüft, ob es sich beim übergebenen Wert um eine Zahl handelt.
 * @param   $value      übergebender Wert
 */
function isNumber($value) {
  if (is_numeric($value)) return true;
  else return false;
}

/**
 * Prüft, ob es sich beim übergebenen Wert um eine positive Ganzzahl handelt (ohne e,+,-).
 * @param   $value      übergebender Wert
 */
function isCleanNumber($value) {
  if (!is_numeric($value)) return false;
  $pattern_number = "/^[0-9]*$/";
  if (preg_match($pattern_number, $value)) return true;
  else return false;
}

/**
 * Prüft, ob ein Eingabewert eine Zahl ist. Eine Leereingabe ist erlaubt.
 * @param   $value         Eingabewert
 * @param   $minlength     Minimale Länge der Zahl
 */
function checkCleanNumberEmpty($value, $minlength=0) {
  if (empty($value)) return true;
  if (!isCleanNumber($value) || strlen($value) < $minlength) return false;
  else return true;
}
function saltPassword($password)
{
    return hash('md5',"!fE:434_Tgs" . $password ."B7!ui67BEDb8");
}
?>