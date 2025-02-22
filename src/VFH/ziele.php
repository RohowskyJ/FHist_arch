<?php
sessIon_start();
$module    = 'VF_Arch';
$path2ROOT = "../";

$debug = True; $debug = False;  // Debug output Ein/Aus Schalter

require $path2ROOT.'login/common/BA_HTML_Funcs.lib.php' ;  // Diverse Unterprogramme
require $path2ROOT.'login/common/BA_Funcs.lib.php' ;  // Diverse Unterprogramme

initial_debug(); # Wenn $debug=true - Ausgabe von Debug Informationen: $_POST, $_GET, $_FILES


$header = "<link  href='".$path2ROOT."login/common/css/frame_pict.css' rel='stylesheet' type='text/css'>";
BA_HTML_header('Ziele der Feuerwehrhistoriker in NÖ',$header,'Form','75em'); # Parm: Titel,Subtitel,HeaderLine,Type,width

?>

<body class="w3-container ">
<div class="w3-content ">  <!-- max-width:45em; margin:5em;--->
<fieldset>

<h2>Unsere Ziele im Detail</h2>

<p>
Historisch interessante Fahrzeuge sollen erhalten werden, um die technische
Entwicklung von Feuerwehrfahrzeugen und Geräten augenscheinlich darstellen
und präsentieren zu können. Dadurch wird die ideologische Basis des
Feuerwehrwesens gestärkt und die Jugend und die Junggebliebenen für
die Feuerwehrtechnik begeistert.
<br>
<font size="-3">
(Präambel, Brunn am Gebirge, 1989)
</font>
</p>

<p>Das ist ein Anliegen der Feuerwehrhistoriker, denn ein Teil unserer
Kultur ist das Bewahren von Gegenständen, die von der Geschichte
des Feuerwehrwesens erzählen.
Von der Bereitschaft von Frauen und Männern sich den Elementen
entgegenzustellen, um den Mitmenschen zu helfen, wenn sie den
Naturgewalten hilflos gegenüber stehen.
Feuer, Wasser, Eis und Sturm oder wenn sie andere Ereignisse, Erdbeben,
Unfälle auf der Straße, im Beruf oder in ihrer Freizeit
überfordern, sind freiwillige Helfer zugegen, die bereit sind ihre
Gesundheit und wenn erforderlich auch ihr Leben einzusetzen.
<br>
Diese Bereitschaft ist nicht selbstverständlich, sondern muß
gepflegt werden. Die Tätigkeit unserer Archivare ist ein Beitrag dazu.
</p>

<p>
Die Feuerwehrhistoriker in Niederösterreich führen diese
Tätigkeit weiter, die Walter Krumhaar und Dr. Hans Schneider
begonnen haben und wollen eine gezielte Sammlung der Objekte und deren
sichere  und gesicherte  Aufbewahrung erreichen. Dazu sind Partner, Freunde,
Förderer und Sponsoren erwünscht und auch erforderlich.
</p>

<p>
Eine der Aufgaben des Vereines ist, Feuerwehren zu finden, die bereit sind
an einer gezielten Sammeltätigkeit mitzuwirken und sie bei dieser
Tätigkeit zu fördern. Wenn das Interesse einer Wehr sinkt oder
der Leiter einer Sammlung diese nicht mehr betreuen kann, dann wird der
Verein helfend zur Seite stehen um die wertvollen Gegenstände für
das Feuerwehrwesen zu erhalten.
</p>

<p>
Einladen wollen wir alle, die an der Geschichte des Feuerwehrwesens interessiert
sind, besonders aber jene, die bereits historisch interessante Fahrzeuge, Geräte
und Ausrüstungsgegenstände besitzen. Das sind: Feuerwehren,
Feuerwehrmänner und -frauen, Museen und Sammlungen, Private und private Vereine,
die derzeit an die 100  Fahrzeuge und unzählige Geräte und
Ausrüstungsgegenstände besitzen.
</p>

<p>
Wenn eine Feuerwehr oder ein Privater nicht mehr für die Erhaltung sorgen kann, so
kauft der Verein "Feuerwehrhistoriker in Niederösterreich" - als eigener Rechtskörper -
das historisch interessante Objekt um einen symbolischen Euro und wird dadurch
Eigentümer, der für die Erhaltung (Restaurierung und Konservierung) und
für die Aufbewahrung sorgt und die damit verbundenen Kosten  trägt.
Der Verkäufer wird Partner der Feuerwehrhistoriker in Niederösterreich
und erhält neben anderen Vorteilungen und Ehrungen ein eingeschränktes
Verfügungsrecht (nicht vererbbar) über sein ehemaliges Objekt. Die
Partnerschaft soll auch für den Verkä;ufer attraktiv sein, indem er die
Freundschaft der Historiker nützen kann und ihm alle Anbote des Vereines
zugänglich gemacht werden.
Die Feuerwehrhistoriker in Niederösterreich geben - wenn sinnvoll oder notwendig -
so erstandene Objekte mittels Leihvertrag an Partner weiter, die für die weitere
Erhaltung sorgen. Für sie gilt das Gleiche, wie fü;r den Verkäufer.
</p>

<p>
So entsteht ein geordneter Fundus auf den für Präsentationszwecke
zurück gegriffen werden kann und der keiner räumlichen und personellen Enge
eines Museums unterliegt.
</p>

<center>
<br><br>
<a href="/VFH/index.php">
    Zur Startseite</a>
</center>

</fieldset>
</div>
<?php
 BA_HTML_trailer();
 ?>
