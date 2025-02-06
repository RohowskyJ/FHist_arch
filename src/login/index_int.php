<?php

/**
 * Login- Schirm zum Internen Bereich
 * 
 * @author Josef Rohowsky - neu 2015
 * 
 */
session_start();

$module = 'VF_Prim';

/**
 * Angleichung an den Root-Path
 *
 * @var string $path2ROOT
 */
$path2ROOT = "../";

$debug = False; // Debug output Ein/Aus Schalter

require $path2ROOT . 'login/common/BA_Funcs.lib.php';
require $path2ROOT . 'login/common/BA_HTML_Funcs.lib.php';

$flow_list = False;

$LinkDB_database  = '';
$db = LinkDB('VFH');

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES

$logo = 'JA';
$form_start = True;
$actor = "VF_C_Menu.php";
BA_HTML_header('Login zum Internen Bereich', '', 'Form', '60em'); # Parm: Titel,Subtitel,HeaderLine,Type,width
if (isset($_SESSION['VF_Prim']['l_err'])) {
    if ($_SESSION['VF_Prim']['l_err'] >= 1) {
        echo "<div class='error'>Benutzer-ID  oder Passwort falsch. Neu eingeben.<br>";
    }
}
$_SESSION['VF_Prim']['p_uid'] = '';

?>

<body class="w3-container ">
	<div class="w3-content max-width:45em; margin:5em;">

            <form id="myform" name="myform" method="post" action="VF_C_Menu.php">  
			<div class='w3-table' style=''>
				<table>
					<tr>
						<th><div class="label">Benutzer-ID eingeben:</div></th>
						<td><input placeholder='EMail-Adresse' name='emailadr' value=''
							size='25' maxlength='45' type='text' tabindex='1' autofocus
							required></td>
					</tr>
					<tr>
						<th><div class="label">Password:</div></th>
						<td><input placeholder='Passwort' name='password' value=''
							size='25' maxlength='45' type='password' tabindex='2' required></td>
					</tr>

					<tr>
						<th><div class="label">Passwort ändern:</div></th>
						<td><select name="pwchg" class="cms_inputtext" id="pwchg">
								<option selected style="color: #ff0000;" value="N" >Nein</option>
								<option style="color: #ff0000;" value="J" >Ja</option>
						</select></td>
					</tr>

				</table>

			</div>

			<p>
				Nach Eingabe von Benutzer ID und Passwort drücken Sie
				<button type='submit' name='phase' value='0' class=green>Einloggen</button>
			</p>

			</form>

	</div>
<?php
 BA_HTML_trailer();
 ?>
