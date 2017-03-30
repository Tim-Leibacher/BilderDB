<?php
/*
 *  @autor Tim Leibacher
 *  @version März 2017
 *  Dieses Modul definert alle Konfigurationsparameter und stellt die DB-Verbindung her
 */

setValue("cfg_func_list", array("bilder","anmelden","register","abmelden","user"));

if (isset($_SESSION['username'])){
    $test = $_SESSION['username'];
    // Funktionen
    //setValue("cfg_func_list", array("bilder","abmelden","user"));
// Inhalt des Menus
    setValue("cfg_menu_list", array("bilder"=>"Bilder","user"=>"$test","abmelden"=>"Abmelden"));
}else{
    //setValue("cfg_func_list", array("bilder","anmelden","register"));
    setValue("cfg_menu_list", array("bilder"=>"Bilder","anmelden"=>"Anmelden","register"=>"Registration"));
}




// Datenbankverbindung herstellen
$db = mysqli_connect("127.0.0.1", "root", "gibbiX12345", "bilderdb_4h_leibacher");
if (!$db) die("Verbindungsfehler: ".mysqli_connect_error());
setValue("cfg_db", $db);
?>
