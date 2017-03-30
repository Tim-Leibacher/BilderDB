<?php
/*
 *  @autor Tim Leibacher
 *  @version März 2017
 *  Dieses Modul beinhaltet Funktionen, welche die Anwendungslogik implementieren.
 */
/*
 * Beinhaltet die Anwendungslogik zur Verwaltung des Kontaktformulars
 */

function anmelden(){
    // Initialisierungen
    if (isset($_GET['action'])) $action = $_GET['action'];
    else $action = "anmelden";
    if (isset($_GET['uid'])) $uid = $_GET['uid'];
    else $uid = "";
    //Anmelden
    if($action = "anmelden") {
        anmeldenUser($action);
    }
    // Template abfüllen und Resultat zurückgeben
    setValue("phpmodule", $_SERVER['PHP_SELF']."?id=".getValue("func")."&action=$action&uid=$uid");
    return runTemplate( "../templates/".getValue("func").".htm.php" );
}

function register() {
    // Initialisierungen
    if (isset($_GET['action'])) $action = $_GET['action'];
    else $action = "new";
    if (isset($_GET['uid'])) $uid = $_GET['uid'];
    else $uid = "";
    // Wenn ein Kontakt bearbeitet werden soll
    if ($action == "edit" && $uid > 0) editUser($action, $uid);
    else newUser($action);
    // Template abfüllen und Resultat zurückgeben
    setValue("phpmodule", $_SERVER['PHP_SELF']."?id=".getValue("func")."&action=$action&uid=$uid");
    return runTemplate( "../templates/".getValue("func").".htm.php" );
}

function abmelden(){
    if (isset($_GET['action'])) $action = $_GET['action'];
    else $action = "abmelden";
    if (isset($_GET['uid'])) $uid = $_GET['uid'];
    else $uid = "";
    //Anmelden
    if($action = "abmelden") {
        meldeAb();
        return header("Location: ".$_SERVER['PHP_SELF']."?id=bilder&action=abmelden");
    }
    // Template abfüllen und Resultat zurückgeben
    setValue("phpmodule", $_SERVER['PHP_SELF']."?id=".getValue("func")."&action=$action&uid=$uid");
    return runTemplate( "../templates/".getValue("func").".htm.php" );
}

function user(){
    // Falls ein User erfolgreich eingefügt worden ist
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        // Übergebener Eintrag in DB löschen
    }
        if (isset($_GET['uid'])) {
            db_delete_user($_GET['uid']);
            meldeAb();
            return header("Location: ".$_SERVER['PHP_SELF']."?id=bilder&action=delete");
        }

    // Alle Daten (Kontakte) aus der DB lesen und der Variablen "data" zuweisen
    setValue("data", db_select_user());
    // Falls Daten vorhanden sind, Template ausführen und Resultat zurückgeben
        setValue("phpmodule", $_SERVER['PHP_SELF']."?id=".getValue("func"));
        return runTemplate("../templates/".getValue("func").".htm.php");
}

/*
 * Beinhaltet die Anwendungslogik zur Ausgabe der Kontaktliste
 */
function bilder() {
    // Falls ein User erfolgreich eingefügt worden ist
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        if ($action == "new") setValue("meldung", "Der User wurde erfolgreich eingefügt.");
        elseif ($action == "edit") setValue("meldung", "Der User wurde erfolgreich geändert.");
        elseif ($action == "abmelden") setValue("meldung", "Sie sind abgemeldet.");
        elseif ($action == "delete") setValue("meldung", "User erfolgreich gelöscht.");
        // Übergebener Eintrag in DB löschen
    }
    // Alle Daten (Kontakte) aus der DB lesen und der Variablen "data" zuweisen

        setValue("phpmodule", $_SERVER['PHP_SELF']."?id=".getValue("func"));
        return runTemplate("../templates/".getValue("func").".htm.php");
}

/*
 * Erstellt das Listenfeld Orte
 * @param       $data       Assoziativer Array mit den Werten
 * @param       $sel        Wert, welcher vorselektiert werden soll
 */

/*
 * Funktion zum Einfügen eines Kontaktes
 * @param  $action     Die Aktion (= new), um bei einem Fehler die URL wieder zu bilden
 */
function newUser($action) {
    // Es wurde auf "speichern" geklickt
    if (isset($_POST['speichern'])) {
        $fehlermeldung = checkUser();
        // Wenn ein Fehler aufgetreten ist
        if (strlen($fehlermeldung) > 0) {
            setValue("meldung", $fehlermeldung);
            setValues($_POST);
            // Wenn alles ok, wird der Kontakt eingefügt und die Kontaktliste angezeigt
        } else {
            if (db_exists_userName($_POST)) {
                db_insert_user($_POST);
                meldeAn($_POST);
                return header("Location: ".$_SERVER['PHP_SELF']."?id=bilder&action=new");
            }else{// Falls der Username schon vergeben ist
                $fehlermeldung = "Username ist schon vorhanden";
                setValue("meldung", $fehlermeldung);
                setValues($_POST);
            }

        }
        // Die Seite wurde zum 1. Mal aufgerufen (via Menü)
    }
}

function anmeldenUser($action){
    // Es wurde auf "anmelden" geklickt
    if (isset($_POST['anmelden'])) {
        $fehlermeldung = checkAnmeldeUser();
        // Wenn ein Fehler aufgetreten ist
        if (strlen($fehlermeldung) > 0) {
            setValue("meldung", $fehlermeldung);
            setValues($_POST);
            // Wenn alles ok, wird der Kontakt eingefügt und die Kontaktliste angezeigt
        } else {
            meldeAn($_POST);
            // Die Funktion wird hier verlassen und die Kontaktliste angezeigt
            return header("Location: ".$_SERVER['PHP_SELF']."?id=bilder&action=anmelden");
        }
        // Die Seite wurde zum 1. Mal aufgerufen (via Menü)
    }


}

/*
 * Funktion zum Ändern eines Kontaktes
 * @param  $action     Die Aktion (= edit), um bei einem Fehler die URL wieder zu bilden
 * @param  $kid        Die ID des Kontaktes, der geändert werden soll
 */
function editUser($action, $kid) {
    // Es wurde auf "speichern" geklickt
    if (isset($_POST['speichern'])) {
        $fehlermeldung = checkUser();
        // Wenn ein Fehler aufgetreten ist
        if (strlen($fehlermeldung) > 0) {
            setValue("meldung", $fehlermeldung);
            setValues($_POST);
            // Wenn alles ok, werden die Änderungen gespeichert und die Kontaktliste angezeigt
        } else {
            db_update_user($kid, $_POST);
            meldeAn($_POST);
            // Die Funktion wird hier verlassen und die Kontaktliste angezeigt
            return header("Location: ".$_SERVER['PHP_SELF']."?id=bilder&action=edit");
        }
        // Die Seite wurde zum 1. Mal aufgerufen (von der Kontaktliste aus)
    } else {
        // Die Daten von der DB werden in den globalen Array geschrieben
        setValue("data", db_select_username($kid));
        // Die einzelnen Attribute in den globalen Array schreiben.
        // Der 1. (und in diesem Fall einzige) Datensatz hat den Index = 0.
        if (is_array(getValue("data"))) {
            setValues(getValue("data")[0]);
        }
    }
}

/*
 * Funktion zur Eingabeprüfung eines Kontaktes
 */
function checkUser() {
    $fehlermeldung = "";

    if (!checkEmpty($_POST['username'], 3)) {
        $fehlermeldung .= "Der Username muss mind. 3 Zeichen lang sein. ";
    }
    if (!checkEmpty($_POST['password'], 3)) {
        $fehlermeldung .= "Der Passwort muss mind. 3 Zeichen lang sein.";
    }
    if ($_POST['password'] != $_POST['rPassword']) {
        $fehlermeldung .= "Passwort stimmt nicht überein.";
    }
    return $fehlermeldung;
}

function checkAnmeldeUser() {
    $fehlermeldung = "";

    if (!db_exists_userAccount($_POST)) {
        $fehlermeldung .= "User existiert nicht.";
    }
    return $fehlermeldung;
}

function meldeAn($params){
    $_SESSION['username'] = strtolower($_POST['username']);
}
function meldeAb(){
    unset($_SESSION['username']);
    session_destroy();
}





/*function kontakterfassen() {
  // Initialisierungen
  if (isset($_GET['action'])) $action = $_GET['action'];
  else $action = "new";
  if (isset($_GET['kid'])) $kid = $_GET['kid'];
  else $kid = "";
  // Wenn ein Kontakt bearbeitet werden soll
  if ($action == "edit" && $kid > 0) editKontakt($action, $kid);
  else newKontakt($action);
  // Template abfüllen und Resultat zurückgeben
  setValue("phpmodule", $_SERVER['PHP_SELF']."?id=".getValue("func")."&action=$action&kid=$kid");
  return runTemplate( "../templates/".getValue("func").".htm.php" );
}

taktliste() {
  // Falls ein Kontakt erfolgreich eingefügt worden ist
  if (isset($_GET['action'])) {
	$action = $_GET['action'];
	if ($action == "new") setValue("meldung", "Der Kontakt wurde erfolgreich eingefügt.");
	elseif ($action == "edit") setValue("meldung", "Der Kontakt wurde erfolgreich geändert.");
  // Übergebener Eintrag in DB löschen
  } elseif (isset($_GET['kid'])) {
	db_delete_kontakt($_GET['kid']);
	setValue("meldung", "Der Kontakt wurde erfolgreich gelöscht.");
  }
  // Alle Daten (Kontakte) aus der DB lesen und der Variablen "data" zuweisen
  setValue("data", db_select_kontakte());
  // Falls Daten vorhanden sind, Template ausführen und Resultat zurückgeben
  if (is_array(getValue("data"))) {
	setValue("phpmodule", $_SERVER['PHP_SELF']."?id=".getValue("func"));
	return runTemplate("../templates/".getValue("func").".htm.php");
  } else {
	return "<div class='col-md-offset-3 col-md-4 text-center'>Liste ist leer...</div><br /><br />";
  }
}


function newKontakt($action) {
  // Es wurde auf "speichern" geklickt
  if (isset($_POST['speichern'])) {
	$fehlermeldung = checkKontakt();
	// Wenn ein Fehler aufgetreten ist
	if (strlen($fehlermeldung) > 0) {
	  setValue("meldung", $fehlermeldung);
	  setValues($_POST);
	  setValue("droport", dropOrt($_POST['oid']));
	// Wenn alles ok, wird der Kontakt eingefügt und die Kontaktliste angezeigt
	} else {
	  db_insert_kontakt($_POST);
	  // Die Funktion wird hier verlassen und die Kontaktliste angezeigt
	  return header("Location: ".$_SERVER['PHP_SELF']."?id=kontaktliste&action=new");
	}
  // Die Seite wurde zum 1. Mal aufgerufen (via Menü)
  } else {
	setValue("droport", dropOrt());
  }
}

function editKontakt($action, $kid) {
  // Es wurde auf "speichern" geklickt
  if (isset($_POST['speichern'])) {
	$fehlermeldung = checkKontakt();
	// Wenn ein Fehler aufgetreten ist
	if (strlen($fehlermeldung) > 0) {
	  setValue("meldung", $fehlermeldung);
	  setValues($_POST);
	  setValue("droport", dropOrt($_POST['oid']));
	// Wenn alles ok, werden die Änderungen gespeichert und die Kontaktliste angezeigt
	} else {
	  db_update_kontakt($kid, $_POST);
	  // Die Funktion wird hier verlassen und die Kontaktliste angezeigt
	  return header("Location: ".$_SERVER['PHP_SELF']."?id=kontaktliste&action=edit");
	}
  // Die Seite wurde zum 1. Mal aufgerufen (von der Kontaktliste aus)
  } else {
	// Die Daten von der DB werden in den globalen Array geschrieben
	setValue("data", db_select_kontakt($kid));
	// Die einzelnen Attribute in den globalen Array schreiben.
	// Der 1. (und in diesem Fall einzige) Datensatz hat den Index = 0.
	if (is_array(getValue("data"))) {
	  setValues(getValue("data")[0]);
	  setValue("droport", dropOrt(getValue("data")[0]['oid']));
	}
  }
}

function checkKontakt() {
  $fehlermeldung = "";

  if (!checkEmpty($_POST['nachname'], 3)) {
	$fehlermeldung .= "Der Nachname muss mind. 3 Zeichen lang sein. ";
  }
  if (!checkEmail($_POST['email'])) {
	$fehlermeldung .= "Falsches Format E-Mail. ";
  }
  return $fehlermeldung;
}*/
?>