<?php
include_once 'klase.php';
$prikaz=dohvatiEnergetskeKlase();
$potrosnjaEnergijeUGodiniDanaS1=0;
$potrosnjaEnergijeUGodiniDanaS2=0;
$potrosnjaEnergijeUGodiniDanaS3=0;
$potrosnjaEnergijeUGodiniDanaS4=0;
/*iZRACUN UKUPNE POTROSNJE U 1 G*/
for ($i = 0; $i < count($prikaz); $i++) {
	$potrosnjaEnergijeUGodiniDanaS1+=$prikaz[$i]->getCalculatedPowerConsumptionPerYearCase1();
	$potrosnjaEnergijeUGodiniDanaS2+=$prikaz[$i]->getCalculatedPowerConsumptionPerYearCase2();	
}
//sort silazno po potrosnji
if(count($prikaz)>0){
	usort($prikaz,function($first,$second){
		return $first->calculatedPowerConsumptionPerYearCase1 < $second->calculatedPowerConsumptionPerYearCase1;
	});
}
	function array_clone($array) {
		return array_map(function($element) {
			return ((is_array($element))
				? call_user_func(__FUNCTION__, $element)
				: ((is_object($element))
					? clone $element
					: $element
				)
			);
		}, $array);
	}
	$modificiraneKlase = array_clone($prikaz) ;
	/*SMANJUJE PRIKAZ 2 NAJVECE KLASE PO POTROSNJI ZA 90 POSTO I POVECAVA IZNOS ODABRANE KLASE MANJE POTROSNJE ZA TAJ POSTOTAK*/
	$postotakPromjeneBoje=0;
	$postotakSmanjenjaBojeNajveciPotrosac=$modificiraneKlase[0]->decreaseAverageScreenPercentage90Percent();
	$postotakSmanjenjaBojeDrugiNajveciPotrosac=$modificiraneKlase[1]->decreaseAverageScreenPercentage90Percent();
	$postotakPromjeneBoje=$postotakSmanjenjaBojeNajveciPotrosac+$postotakSmanjenjaBojeDrugiNajveciPotrosac;

	for ($i = 0; $i < count($modificiraneKlase); $i++) {
		if($modificiraneKlase[$i]->getR()==51&&$modificiraneKlase[$i]->getG()==51&&$modificiraneKlase[$i]->getB()==51){
			$modificiraneKlase[$i]->increaseAverageScreenPercentage90Percent($postotakPromjeneBoje);
		}
	}	
	/*iZRACUN UKUPNE POTROSNJE za upotreba tamnije boje U 1 G*/
	for ($i = 0; $i < count($modificiraneKlase); $i++) {
		$potrosnjaEnergijeUGodiniDanaS3+=$modificiraneKlase[$i]->getCalculatedPowerConsumptionPerYearCase1();
		$potrosnjaEnergijeUGodiniDanaS4+=$modificiraneKlase[$i]->getCalculatedPowerConsumptionPerYearCase2();	
	}
	//sort silazno po potrosnji
	if(count($modificiraneKlase)>0){
		usort($modificiraneKlase,function($first,$second){
			return $first->calculatedPowerConsumptionPerYearCase1 < $second->calculatedPowerConsumptionPerYearCase1;
		});
	}		
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
    crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>ColorAnalysis</title>
   <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark p-0">
    <div class="container">
      <a href="index.php" class="nav-link text-secondary "> Početna </a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav">
          <li class="nav-item px-2">
            <a href="razredi.php" class="nav-link">Razredi</a>
          </li>
            <li class="nav-item px-2">
            <a href="efikasnost.php" class="nav-link">Efikasnost</a>
          </li>
		    <li class="nav-item px-2">
            <a href="izracun.php" class="nav-link active font-weight-bold">Izračuni</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HEADER -->
  <header id="main-header" class="py-2 bg-light text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1 class="text-dark">
            <i class="fas fa-cog text-dark"></i> Analiza</h1>
        </div>
      </div>
    </div>
  </header>
<section id="razredi">
  <div class="container">
  <h1 class="display-4">SLUČAJ 1</h1>
  <p class="lead"><strong>Izračunata potrošnja energije OLED monitora u razdoblju jedne godine </strong><strong>P=</strong><?php echo $potrosnjaEnergijeUGodiniDanaS1;  ?> <strong>J</strong></p>
  <p class="lead"><strong>Izračunata potrošnja energije OLED monitora u razdoblju jedne godine uz upotrebu tamnijih boja </strong><strong>P=</strong><?php echo $potrosnjaEnergijeUGodiniDanaS3;  ?> <strong>J</strong></p>
  <p class="lead"><strong>Postignuta ušteda energije (J) </strong><strong class="font-weight-bold">UŠTEDA ENERGIJE=</strong><?php echo $potrosnjaEnergijeUGodiniDanaS1-$potrosnjaEnergijeUGodiniDanaS3;  ?> <strong>(J)</strong></p>
  <p class="lead"><strong>Postignuta ušteda energije (%) </strong><strong class="font-weight-bold">UŠTEDA ENERGIJE=</strong><?php echo ($potrosnjaEnergijeUGodiniDanaS1-$potrosnjaEnergijeUGodiniDanaS3)/$potrosnjaEnergijeUGodiniDanaS1*100;  ?> <strong>(%)</strong></p>
  <h1 class="display-4">SLUČAJ 2</h1>
  <p class="lead"><strong>Izračunata potrošnja energije OLED monitora u razdoblju jedne godine </strong><strong>P=</strong><?php echo $potrosnjaEnergijeUGodiniDanaS2;  ?> <strong>J</strong></p>
  <p><strong>Izračunata potrošnja energije OLED monitora u razdoblju jedne godine uz upotrebu tamnijih boja </strong><strong>P=</strong><?php echo $potrosnjaEnergijeUGodiniDanaS4;  ?> <strong>J</strong></p>
    <p class="lead"><strong>Postignuta ušteda energije (J) </strong><strong class="font-weight-bold">UŠTEDA ENERGIJE=</strong><?php echo $potrosnjaEnergijeUGodiniDanaS2-$potrosnjaEnergijeUGodiniDanaS4;  ?> <strong>(J)</strong></p>
   <p class="lead"><strong>Postignuta ušteda energije (%) </strong><strong class="font-weight-bold">UŠTEDA ENERGIJE=</strong><?php echo ($potrosnjaEnergijeUGodiniDanaS2-$potrosnjaEnergijeUGodiniDanaS4)/$potrosnjaEnergijeUGodiniDanaS2*100;  ?> <strong>(%)</strong></p>
  <h1 class="display-4">info</h1>
  <p class="lead">Ukupni postotak promjene boje na zaslonu <?php echo $postotakPromjeneBoje ;?><strong>(%)</strong></p>
  <p class="lead">Postotak smanjenja boje najveći potrosač <?php echo $postotakSmanjenjaBojeNajveciPotrosac ;?><strong>(%)</strong></p>
  <p class="lead">Postotak smanjenja boje drugi najveći potrosač <?php echo $postotakSmanjenjaBojeDrugiNajveciPotrosac ;?><strong>(%)</strong></p>
  <br>
  <h2 class="display-4">Grafički prikaz potrošnje energije u razdoblju jedne godine po klasama potrošnje</h2>
  <div class="container" style="max-width: 100%;width:1000px;height: 1000px; margin-left:auto;margin-right:auto"><div id="histogram1" style="width: 100%;height: 1000px;"></div></div>
  <div class="container" style="max-width: 100%;width:1000px;height: 1000px; margin-left:auto;margin-right:auto"><div id="histogram2" style="width: 100%;height: 1000px;"></div></div>
 
  <script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawChart2);

function drawChart() {
      var data = new google.visualization.DataTable();
  var data = google.visualization.arrayToDataTable([
      <?php 
	  $colors="";
	  echo "['Boja', 'Potrosnja', { role: 'style' }],";
	  for ($i = 0; $i < count($prikaz); $i++) {
		if($i==(count($prikaz)-1)){
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getCalculatedPowerConsumptionPerYearCase1().",'".$prikaz[$i]->getHexValue()."'"."]";
		}else{
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getCalculatedPowerConsumptionPerYearCase1().",'".$prikaz[$i]->getHexValue()."'"."],";
		}
		if($i==(count($prikaz)-1)){
			$colors.="'".$prikaz[$i]->getHexValue()."'";
		}else{
			$colors.="'".$prikaz[$i]->getHexValue()."',";
		}
	}
	  ?>
]);
  var options = {title:'Potrošnja energije 1 godini',height:800,bar:{gap: 0 }};
  var chart = new google.visualization.ColumnChart(document.getElementById('histogram1'));
  chart.draw(data, options);
}

function drawChart2() {
      var data2 = new google.visualization.DataTable();
  var data2 = google.visualization.arrayToDataTable([
      <?php 
	  echo "['Boja', 'Potrosnja', { role: 'style' }],";
	  for ($i = 0; $i < 15; $i++) {
		if($i==(count($prikaz)-1)){
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getCalculatedPowerConsumptionPerYearCase1().",'".$prikaz[$i]->getHexValue()."'"."]";
		}else{
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getCalculatedPowerConsumptionPerYearCase1().",'".$prikaz[$i]->getHexValue()."'"."],";
		}
	}
	  ?>
]);
  var options2 = {title:'Prikaz prvih 15 klasa potrošnje energije u 1 godini',height:800,bar:{gap: 0 },
  hAxis: {title: "Boje" , slantedText:true, slantedTextAngle:90 },
  vAxis: {title: "Potrošnja (J)"}
  };
  var chart2 = new google.visualization.ColumnChart(document.getElementById('histogram2'));
  chart2.draw(data2, options2);
}
</script>
  <br>
    <h2 class="display-4">potrošnja energije u 1 godini po klasama</h2>
        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
					<th>#</th>
                    <th>OZNAKA RAZREDA (R,G,B)</th>
                    <th>Potrošena energija u 1 godini (J) SLUČAJ 1</th>
					<th>Potrošena energija u 1 godini (J) SLUČAJ 2</th>
					<th>Prosječni postotak zaslona</th>
                    <th>"Boja"</th>
                </tr>
            </thead>
            <tbody>
	</div>		
  <?php 
  /*ISPIS POTROSNJE ENERGIJE PO KLASAMA U 1G*/
  /*DODATNO HISTOGRAM UPITNIK??*/
  for ($i = 0; $i < count($prikaz); $i++) {
						echo "<tr>";	
						echo "<td>";	
						echo ($i+1);	
						echo "</td>";
						echo "<td>";	
						echo "(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")";	
						echo "</td>";	
						echo "<td>";	
						echo $prikaz[$i]->getCalculatedPowerConsumptionPerYearCase1();	
						echo "</td>";
						echo "<td>";	
						echo $prikaz[$i]->getCalculatedPowerConsumptionPerYearCase2();
						echo "</td>";
						echo "<td>";	
						echo $prikaz[$i]->getAverageScreenPercentage();
						echo "</td>";
						echo "<td class='gardijent' style='background: linear-gradient(to right, rgb"."(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")"." 0%, rgb( ";
						echo $prikaz[$i]->getREnd() . ",";
						echo $prikaz[$i]->getGEnd(). ",";
						echo $prikaz[$i]->getBEnd();
						echo ") 100%)'";	
						echo ">";	
						echo "</td>";

						echo "</tr>";			
				  
	}
  ?>
 </tbody>
</table><br>
  
   <h2 class="display-4">potrošnja energije u 1 godini po klasama UZ TAMNIJU TEMU</h2>
        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
					<th>#</th>
                    <th>OZNAKA RAZREDA (R,G,B)</th>
                    <th>Potrošena energija u 1 godini (J) SLUČAJ 1</th>
					<th>Potrošena energija u 1 godini (J) SLUČAJ 2</th>
					<th>Prosječni postotak zaslona</th>
                    <th>"Boja"</th>
                </tr>
            </thead>
            <tbody>
  <?php 
 
  
  
  /*ISPIS POTROSNJE ENERGIJE PO KLASAMA U 1G*/
  /*DODATNO HISTOGRAM UPITNIK??*/
  for ($i = 0; $i < count($modificiraneKlase); $i++) {
						echo "<tr>";	
						echo "<td>";	
						echo ($i+1);	
						echo "</td>";
						echo "<td>";	
						echo "(".$modificiraneKlase[$i]->getR().",".$modificiraneKlase[$i]->getG().",".$modificiraneKlase[$i]->getB().")";	
						echo "</td>";	
						echo "<td>";	
						echo $modificiraneKlase[$i]->getCalculatedPowerConsumptionPerYearCase1();	
						echo "</td>";
						echo "<td>";	
						echo $modificiraneKlase[$i]->getCalculatedPowerConsumptionPerYearCase2();
						echo "</td>";
						echo "<td>";	
						echo $modificiraneKlase[$i]->getAverageScreenPercentage();
						echo "</td>";
						echo "<td class='gardijent' style='background: linear-gradient(to right, rgb"."(".$modificiraneKlase[$i]->getR().",".$modificiraneKlase[$i]->getG().",".$modificiraneKlase[$i]->getB().")"." 0%, rgb( ";
						echo $modificiraneKlase[$i]->getREnd() . ",";
						echo $modificiraneKlase[$i]->getGEnd(). ",";
						echo $modificiraneKlase[$i]->getBEnd();
						echo ") 100%)'";	
						echo ">";	
						echo "</td>";

						echo "</tr>";			
				  
	}
  ?>
 </tbody>
</table><br>

  </div>

   
  </section>

  <!-- FOOTER -->
  <footer id="main-footer" class="bg-dark text-white mt-5 p-5">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="lead text-center">
            Copyright &copy;
            <span id="year"></span>
            Jelena Čuka
          </p>
        </div>
      </div>
    </div>
  </footer>

  <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
    crossorigin="anonymous"></script>
  <script src="https://cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>

  <script>
    $('#year').text(new Date().getFullYear());
    CKEDITOR.replace('editor1');
  </script>
</body>

</html>