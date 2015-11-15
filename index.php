<?php


class KsiazkaTelefoniczna {
    protected $baza = array();
	protected $rekordow = 0;
	private $od = 0;
	private $komunikaty = array();
	protected $conf = array(
		'plik' => "baza4.csv"
	);

    public function __construct( /*...*/ ) {
	
		$this->otworz();
		$this->zadanie();
    }
	

public function zadanie(){
 
 if ($_POST["zadanie"] == 'dodaj'){
 
	$numer = htmlspecialchars($_POST["numer"]);
	$osoba = htmlspecialchars($_POST["osoba"]);
 
	$this->dodaj($numer,$osoba);

	$this->komunikaty[] = "Pomyślnie dodano numer ".$numer." należący do".$osoba.".";

 }
 
 if ($_GET["zadanie"] == 'usun'){
	$id = $_GET["id"];
	$numer = htmlspecialchars($_GET["numer"]);
	$osoba = htmlspecialchars($_GET["osoba"]);
 	
	$this->usun($id);

	$this->komunikaty[] = "Pomyślnie usunięto numer ".$numer." należący do".$osoba.".";
 
 } 

 if (isset($_POST["od"])){
	$this->od = $this->conf['od'];
 }
 
 
}


	
public function otworz(){

	if( ! file_exists($this->conf['plik']))
		return;

	if (($handle = fopen($this->conf['plik'], "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			array_push($this->baza, $data);
		}
		fclose($handle);
	}
	
	
}

public function zapisz(){
$fp = fopen($this->conf['plik'], 'w');


$data = $this->baza;
foreach ( $data as $line ) {

    fputcsv($fp, $line, ",");

	
	}
fclose($fp);
}

public function usun($id){
	if( ! count($this->baza) > 0)
	return;
	
	foreach($this->baza as $k => $r){
		if($r[0]==$id)
			unset($this->baza[$k]);
	}

	$this->zapisz();

}

public function dodaj($numer, $nazwisko){

if(count($this->baza) > 0)
{
	$ostatni = end($this->baza);
	$id = $ostatni[0]+1;
}else
{
	$id = 1;
}

$nowy = array($id, $numer, $nazwisko);

array_push($this->baza, $nowy);

$this->zapisz();

}


public function wyswietlKomunikaty(){
	echo '<div class="komunikaty">';
	foreach($this->komunikaty as $k){
		echo '<p>'.$k.'</p>';
	}
	echo "</div>";
}

public function wyswietlTabele(){

	$b = $this->baza;

	echo '<table class="telefony">';
	//echo '<tr>';
	foreach($b as $r){
		echo '<tr>';
		$id=$r[0];
		$numer=$r[1];
		$osoba=$r[2];
		echo '<td>'.$numer.'</td><td>'.$osoba.'</td>';		
		echo '<td><a href="index.php?zadanie=usun&id='.$id.'&numer='.urlencode($numer).'&osoba='.urlencode($osoba).'">';
		echo '<img src="usun.png" alt="Usuń"/></a></td>';
		echo '</tr>'.PHP_EOL;
	}
	echo '</table>';
}

public function wyswietlFormularz(){

$nieprawidlowynumer='+481234567';
$nieprawidlowaosoba='Kowalski';

 if($_POST['zadanie']=='dodaj'){
	$nieprawidlowynumer = htmlspecialchars($_POST["numer"]);
	$nieprawidlowaosoba = htmlspecialchars($_POST["osoba"]);
	}
 if($_GET['zadanie']=='usun'){
	$nieprawidlowynumer = htmlspecialchars($_GET["numer"]);
	$nieprawidlowaosoba = htmlspecialchars($_GET["osoba"]);
	}	
?>
<center>
 <form action="index.php" method="POST">
Numer kontaktowy:
<input type="text" name ="numer" value="<?=$nieprawidlowynumer ?>">
Osoba kontaktowa:
<input type="text" name="osoba" value="<?=$nieprawidlowaosoba?>">
<input type="hidden" name="zadanie" value="dodaj">
<input type="submit" value="Dodaj">
</form> 
</center>
<?php
}

}
//////////////////////////////////////////////////
?><html>
<head>
<title>Książka Telefoniczna</title>

<style type="text/css">

h1{margin:20px;}
.telefony{
margin:auto;
padding: 4px;
border:2px solid #ddd;
width:50%;
}

.telefony td{
border:1px solid #eee;
padding: 3px 20px;
}

.komunikaty{
width:50%;
margin:20px auto;
border:2px solid #ddd;
}

.komunikaty p{
margin:20px;
}

</style>
</head>
<body>
<center><h1>Książka Telefoniczna</h1></center>

<?php
$phonebook = new KsiazkaTelefoniczna();

// $phonebook->otworz();


$phonebook->wyswietlFormularz();
$phonebook->wyswietlTabele();
$phonebook->wyswietlKomunikaty();

	echo '<center>14.02.2015</center>';

?>
</body>
</html>
