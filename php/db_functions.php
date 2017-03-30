<?php
/*
 *  @autor Tim Leibacher
 *  @version März 2017
 *  Dieses Modul beinhaltet sämtliche Datenbankfunktionen.
 *  Die Funktionen formulieren die SQL-Anweisungen und rufen dann die Funktionen
 *  sqlQuery() und sqlSelect() aus dem Modul basic_functions.php auf.
 */

/*
 * Datenbankfunktionen zur Tabelle orte
 */

/*
 * Datenbankfunktionen zur Tabelle kontakte
 */


function db_insert_user($params) {
        $sql = "insert into user (username, password)
        values ('".strtolower(escapeSpecialChars($params['username']))."','".saltPassword(escapeSpecialChars($params['password']))."')";
        sqlQuery($sql);

}
function db_select_user() {
    $sql = "select uid, username, password
			from user
			order by username";
    return sqlSelect($sql);
}
function db_select_username($uid) {
    $sql = "select uid, username
			from user
			WHERE uid = $uid";
    return sqlSelect($sql);
}
function db_update_user($uid, $params) {
    $sql = "update user
			set username = '".strtolower(escapeSpecialChars($params['username']))."',
				password = '".saltPassword(escapeSpecialChars($params['password']))."'
			where uid = $uid";
    sqlQuery($sql);
}

function db_exists_userName($params){
    $sql = "select * from user where username='".escapeSpecialChars($params['username'])."';";
    if (sqlSelect($sql) == ""){
        return true;
    }else return false;

}

function db_exists_userAccount($params){
    $sql = "select username,password
            from user
            where username ='".escapeSpecialChars($params['username'])."' and password = '".saltPassword(escapeSpecialChars($params['password']))."';";
    if (sqlSelect($sql) == ""){
        return false;
    }else return true;
}

function db_delete_user($uid) {
    if (isCleanNumber($uid)) sqlQuery("delete from user where uid=".$uid);
}

//function db_insert_kontakt($oid, $params) {
/*function db_insert_kontakt($params) {
    $sql = "insert into kontakte (nachname, vorname, strasse, oid, email, tel)
            values ('".escapeSpecialChars($params['nachname'])."','".escapeSpecialChars($params['vorname'])."',
					'".escapeSpecialChars($params['strasse'])."', ".$params['oid'].",
					'".escapeSpecialChars($params['email'])."','".escapeSpecialChars($params['tel'])."')";
    sqlQuery($sql);
}

function db_update_kontakt($kid, $params) {
    $sql = "update kontakte
			set nachname = '".escapeSpecialChars($params['nachname'])."',
				vorname = '".escapeSpecialChars($params['vorname'])."',
				strasse = '".escapeSpecialChars($params['strasse'])."',
				oid = ".$params['oid'].",
				email = '".escapeSpecialChars($params['email'])."',
				tel = '".escapeSpecialChars($params['tel'])."'
			where kid = $kid";
    sqlQuery($sql);
}

function db_select_kontakt($kid) {
	$sql = "select * from kontakte where kid = $kid";
	return sqlSelect($sql);
}

function db_select_kontakte() {
	$sql = "select k.kid, k.nachname, k.vorname, k.strasse, o.plz, o.ort, k.email, k.tel
			from kontakte k
			left join orte o on o.oid = k.oid
			order by nachname, vorname";
	return sqlSelect($sql);
}

function db_delete_kontakt($kid) {
    if (isCleanNumber($kid)) sqlQuery("delete from kontakte where kid=".$kid);
}*/
?>