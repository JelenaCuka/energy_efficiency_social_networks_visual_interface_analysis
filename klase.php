<?php
include_once 'baza.class.php';
class EnergetskiRazredBoje { 
    public $id = '';
    public $r = ''; 
    public $g='';
    public $b='';
	public $rEnd = ''; 
    public $gEnd='';
    public $bEnd='';
	public $power='';
	public $averageScreenPercentage='';
	public $calculatedPowerConsumptionPerYearCase1='';
	public $calculatedPowerConsumptionPerYearCase2='';
	public $hexValue='';
	public $rgbValueString='';
	
    public function __construct($id,$R,$G,$B,$P,$avSP) {
        $this->id=$id;
        $this->r =$R;
        $this->g =$G;
        $this->b =$B;
		if(($R+50)>=255){$this->rEnd =255;} 
		else $this->rEnd =($R+50);
		if(($G+50)>=255){$this->gEnd =255;} 
		else $this->gEnd =($G+50);
		if(($B+50)>=255){$this->bEnd =255;} 
		else $this->bEnd =($B+50);
		$this->power =$P;
		$this->averageScreenPercentage =$avSP;
		$this->calculateYearConsumptionCase1();
		$this->calculateYearConsumptionCase2();
		$this->hexValue =calculateHexValue($R,$G,$B);
		$this->rgbValueString=rgbToString($R,$G,$B);
    }
    public function getId() {
        return $this->id;
    }
    public function getR() {
        return $this->r;
    }
    public function getG() {
        return $this->g;
    }
    public function getB() {
        return $this->b;
    }
    public function getPower() {
        return $this->power;
    }
	public function getAverageScreenPercentage() {
        return $this->averageScreenPercentage;
    }
	public function getREnd() {
        return $this->rEnd;
    }
    public function getGEnd() {
        return $this->gEnd;
    }
    public function getBEnd() {
        return $this->bEnd;
    }
	public function getHexValue() {
        return $this->hexValue;
    }
	public function getRGBString() {
        return $this->rgbValueString;
    }
    public function getCalculatedPowerConsumptionPerYearCase1() {
        return $this->calculatedPowerConsumptionPerYearCase1;
    }
    public function getCalculatedPowerConsumptionPerYearCase2() {
        return $this->calculatedPowerConsumptionPerYearCase2;
    }	
	public function calculateYearConsumptionCase1() {
		//broj korisnika društvenih mreža
		$numberOfUsers=2620000000;
		//prosječno vrijeme koje korisnik provodi na društvenoj mreži dnevno
		$averageTimePerDayOneUserSpendsOnSocialNetworksHours=2.25;
		//365 dana u godini
		// udio(%) smartphone oled zaslona na cijelom tržištu zaslona
		$OLEDmonitorsShare=0.308;
		$OtherCalculatedParameters=$averageTimePerDayOneUserSpendsOnSocialNetworksHours*$numberOfUsers*365*$OLEDmonitorsShare;
		//rezolucija (promatrani slučaj 1)
		$NumberOfPixelsCase1=750*1334;
		//vrijeme*udioNaTržištuZaslona*brojKorisnika*jediničnaPotrošnjaPikselaKlase*ProsječniUdioKlaseNaZaslonu
		$calculationResult=$OtherCalculatedParameters*$NumberOfPixelsCase1*($this->power)*($this->averageScreenPercentage/100);
        $this->calculatedPowerConsumptionPerYearCase1=$calculationResult;
    }
	public function calculateYearConsumptionCase2() {
		$averageTimePerDayOneUserSpendsOnSocialNetworksHours=2.25;
		$numberOfUsers=2620000000;
		$OLEDmonitorsShare=0.308;
		$OtherCalculatedParameters=$averageTimePerDayOneUserSpendsOnSocialNetworksHours*$numberOfUsers*365*$OLEDmonitorsShare;
		$NumberOfPixelsCase2=1080*1920;
		$calculationResult=$OtherCalculatedParameters*$NumberOfPixelsCase2*($this->power)*($this->averageScreenPercentage/100);
        $this->calculatedPowerConsumptionPerYearCase2=$calculationResult;
    }	
	public function increaseAverageScreenPercentage90Percent($percentageToAdd) {
		$this->averageScreenPercentage =$this->averageScreenPercentage+$percentageToAdd;
		//update consumption
		$this->calculateYearConsumptionCase1();
		$this->calculateYearConsumptionCase2();
    }	
	/*retutns decreased percentage not new value*/
	public function decreaseAverageScreenPercentage90Percent() {
		$percentageToDecrease=$this->averageScreenPercentage*0.9;
		$this->averageScreenPercentage =$this->averageScreenPercentage-$percentageToDecrease;
		//update consumption
		$this->calculateYearConsumptionCase1();
		$this->calculateYearConsumptionCase2();
		return $percentageToDecrease;
    }

	
}

function dohvatiPodatak($sql){
        $veza = new Baza();
        $veza->spojiDB();
        $rez= $veza->selectDB($sql);
        $prikaz2="";
        if ($rez != null) {
            while ($red = $rez->fetch_array()) {
                $prikaz2=$red;
            }
        }
	$veza->zatvoriDB(); 
    return $prikaz2[0];  
}
function calculateHexValue($R,$G,$B) {
	$rFirst=intval($R/16);
	$rSecond=$R-($rFirst*16);
	$rFirst=checkIfLetterHex($rFirst);
	$rSecond=checkIfLetterHex($rSecond);
	$gFirst=intval($G/16);
	$gSecond=$G-($gFirst*16);
	$gFirst=checkIfLetterHex($gFirst);
	$gSecond=checkIfLetterHex($gSecond);
	$bFirst=intval($B/16);
	$bSecond=$B-($bFirst*16);
	$bFirst=checkIfLetterHex($bFirst);
	$bSecond=checkIfLetterHex($bSecond);
	$hexValue="#".$rFirst.$rSecond.$gFirst.$gSecond.$bFirst.$bSecond;
	return 	$hexValue;
}
function rgbToString($R,$G,$B) {
	$rgbString="(".$R.",".$G.",".$B.")";
	return 	$rgbString;
}
function checkIfLetterHex($numberInput) {
	switch ($numberInput) {
		case 10: return "A";
		case 11: return "B";
		case 12: return "C";
		case 13: return "D";
		case 14: return "E";
		case 15: return "F";
		default: return $numberInput;
	}
}

function dohvatiEnergetskeKlase(){
	$brojZapisaZaslona=0;
	$sqlBrojZapisaZaslona="SELECT COUNT(page_data_id) FROM page_data";
	$brojZapisaZaslona=dohvatiPodatak($sqlBrojZapisaZaslona);
	
	$sql="SELECT ecc.id,ecc.r,ecc.g,ecc.b,ecc.power, AVG(pec.percentage)  FROM  energy_color_class ecc LEFT JOIN page_has_energy_classes pec ON ecc.id=pec.energy_id GROUP by 1 ORDER BY  AVG(pec.percentage) DESC";
	$veza = new Baza();
	$veza->spojiDB();
	$rez = $veza->selectDB($sql);
	$prikaz = array();
	if ($rez != null) {
		while (list($id,$r,$g,$b,$p,$avgScreen) = $rez->fetch_array()) {
			$sqlKlasaSePojavljujeNaBrojuZapisa="SELECT DISTINCT count(pg.page_data_id) FROM energy_color_class ecc LEFT JOIN page_has_energy_classes pec ON ecc.id=pec.energy_id LEFT JOIN page_data pg ON pec.page_id=pg.page_data_id WHERE energy_id=$id ";
			$KlasaSePojavljujeNaBrojuZapisa=0;
			$KlasaSePojavljujeNaBrojuZapisa=$veza->selectDB($sqlKlasaSePojavljujeNaBrojuZapisa);
			$KlasaSePojavljujeNaBrojuZapisa=$KlasaSePojavljujeNaBrojuZapisa->fetch_array();
			$KlasaSePojavljujeNaBrojuZapisa=$KlasaSePojavljujeNaBrojuZapisa[0];
			// za svaku klasu
			$udioKlaseNaBrojuZaslona=0;
			$udioKlaseNaBrojuZaslona=$KlasaSePojavljujeNaBrojuZapisa/$brojZapisaZaslona;
			/*ispis retka*/						 
			$prosjecniPostotakKlaseNaZaslonu=0;
			$prosjecniPostotakKlaseNaZaslonu=$udioKlaseNaBrojuZaslona*$avgScreen;
			$prikaz[] = new EnergetskiRazredBoje($id,$r,$g,$b,$p,$prosjecniPostotakKlaseNaZaslonu);
			//nakon korekcije postotka nije 100% sigurno da je poredak i dalje DECS pa sortirano
		}
	}
	$veza->zatvoriDB();
    if(!empty($prikaz))return $prikaz; 
}

?>