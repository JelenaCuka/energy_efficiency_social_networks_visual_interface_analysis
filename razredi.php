<?php
include_once 'klase.php';
$prikaz=dohvatiEnergetskeKlase();
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
      <a href="index.php" class="nav-link text-secondary">Početna </a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav">
          <li class="nav-item px-2">
            <a href="razredi.php" class="nav-link active font-weight-bold">Razredi</a>
          </li>
            <li class="nav-item px-2">
            <a href="efikasnost.php" class="nav-link">Efikasnost</a>
          </li>
		    <li class="nav-item px-2">
            <a href="izracun.php" class="nav-link">Izračuni</a>
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
  <h2 class="display-4">Energetski razredi boja po potrošnji energije</h2>
        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
					<th>#</th>
                    <th>OZNAKA RAZREDA (R,G,B)</th>
                    <th>potrošnja energije po pikselu (µWh)</th>
                    <th>Prikaz raspona boja</th>
                </tr>
            </thead>
            <tbody>
			<?php
				$redniBroj=0;
				$sql="SELECT r,g,b,power FROM energy_color_class ORDER BY power DESC;";
				$veza = new Baza();
				$veza->spojiDB();
				$rez = $veza->selectDB($sql);
				if ($rez != null) {
					while (list($r,$g,$b,$p) = $rez->fetch_array()) {
						 /*ispis retka*/	
						$redniBroj++; 
						echo "<tr>";	
						echo "<td>";	
						echo $redniBroj;	
						echo "</td>";
						echo "<td>";	
						echo "($r,$g,$b)";	
						echo "</td>";	
						echo "<td>";	
						echo $p*pow(10,15)." *10^3";	
						echo "</td>";
						echo "<td class='gardijent' style='background: linear-gradient(to right, rgb($r,$g,$b) 0%, rgb( ";
						if(($r+50)>255) echo 255 . ",";
						else echo ($r+50) . ",";
						if(($g+50)>255) echo 255 . ",";
						else echo ($g+50) . ",";
						if(($b+50)>255) echo 255;
						else echo ($b+50);
						"$r+50,$g+50,$b+50";
						echo ") 100%)'";	
						echo ">";	
						echo "</td>";
						echo "</tr>";	
					}
				}
				$veza->zatvoriDB();
			?>
            </tbody>
        </table>
        <br>
  </div>
    <div class="container"><h2 class="display-4">Grafički prikaz energetskih razreda potrošnje</h2></div>
    
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
	  if(count($prikaz)>0){
							usort($prikaz,function($first,$second){
								return $first->power < $second->power;
							});
	}
	  
	  echo "['Boja', 'Energija (µWh*10^3)', { role: 'style' }],";
	  for ($i = 0; $i <10; $i++) {
		if($i==(count($prikaz)-1)){
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getPower()*pow(10,15).",'".$prikaz[$i]->getHexValue()."'"."]";
		}else{
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getPower()*pow(10,15).",'".$prikaz[$i]->getHexValue()."'"."],";
		}
	}
	  ?>
]);
  var options = {title:'Prikaz 10 najefikasnijih energetskih razreda',height:800,bar:{gap: 0 },
  hAxis: {title: "Razred" , slantedText:true, slantedTextAngle:90 },
  vAxis: {title: "Jedinična potrošnja energije (µWh*10^3)"}};
  var chart = new google.visualization.ColumnChart(document.getElementById('histogram1'));
  chart.draw(data, options);
}

function drawChart2() {
      var data2 = new google.visualization.DataTable();
  var data2 = google.visualization.arrayToDataTable([
      <?php 
	  	  if(count($prikaz)>0){
							usort($prikaz,function($first,$second){
								return $first->power > $second->power;
							});
	}
	  
	  
	  echo "['Boja', 'Energija(µWh*10^3)', { role: 'style' }],";
	  for ($i = 0; $i < 10; $i++) {
		if($i==(count($prikaz)-1)){
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getPower()*pow(10,15).",'".$prikaz[$i]->getHexValue()."'"."]";
		}else{
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getPower()*pow(10,15).",'".$prikaz[$i]->getHexValue()."'"."],";
		}
	}
	  ?>
]);
  var options2 = {title:'Prikaz 10 najmanjeefikasnih energetskih razreda',height:800,bar:{gap: 0 },
  hAxis: {title: "Razred" , slantedText:true, slantedTextAngle:90 },
  vAxis: {title: "Jedinična potrošnja energije (µWh*10^3) "}
  };
  var chart2 = new google.visualization.ColumnChart(document.getElementById('histogram2'));
  chart2.draw(data2, options2);
}
</script>
  <br>
    <div class="container">
  <h2 class="display-4">Facebook boje</h2>
        <!-- TABLE -->
		<?php 
		$veza = new Baza();
        $veza->spojiDB();
		$sqlPostotciFBBoja="SELECT AVG(`fbblue1`),AVG(`fbblue2`),AVG(`fbgrey1`),AVG(`fbblue3`),AVG(`fbgrey2`),AVG(`fbwhite`) FROM page_data";
		$PostotciFBBoja=$veza->selectDB($sqlPostotciFBBoja);
		list($b1fb,$b2fb,$g1fb,$b3fb,$g2fb,$wfb)=$PostotciFBBoja->fetch_array();
		$veza->zatvoriDB();		
	
		?>
        <table class="table">
            <thead>
                <tr>
                    <th>OZNAKA Facebook boje (R,G,B)</th>
					<th>BOJA</th>
                    <th>Oznaka razreda kojem pripada (r,g,b)</th>
					<th>Prosječno na zaslonu(%)</th>
					<th>RAZRED</th>
                    <th>Redni broj po razreda po potrošnji</th>
					
                </tr>
            </thead>
            <tbody>
			<tr>
                    <th>RGB(59,89,152)</th>
                    <td class="gardijent" style="background: rgb( 59,89,152);"></td>
					<th>razred(51,51,102)</th>
					<td><?php echo $b1fb; ?></td>
                    <td class="gardijent" style="background: linear-gradient(to right, rgb(51,51,102) 0%, rgb(101,101,152) 100%);"></td>
                    <td>200</td>
                </tr>
				<tr>
                    <th>RGB(66,103,178)</th>
                    <td class="gardijent" style="background: rgb(66,103,178);"></td>
					<th>razred(51,102,153)</th>
					<td><?php echo $b2fb; ?></td>
                    <td class="gardijent" style="background: linear-gradient(to right, rgb(51,102,153) 0%, rgb( 101,152,203) 100%);"></td>
                    <td>174</td>
                </tr>
				<tr>
                    <th>RGB(233,235,238)</th>
                    <td class="gardijent" style="background: rgb(233,235,238);"></td>
					<th>razred(204,204,204)</th>
					<td><?php echo $g1fb ;?></td>
                    <td class="gardijent" style="background: linear-gradient(to right, rgb(204,204,204) 0%, rgb( 254,254,254) 100%);"></td>
                    <td>27</td>
                </tr>
				<tr>
                    <th>RGB(54,88,153)</th>
                    <td class="gardijent" style="background: rgb(54,88,153);"></td>
					<th>razred(51,51,153)</th>
					<td><?php echo $b3fb ;?></td>
                    <td class="gardijent" style="background: linear-gradient(to right, rgb(51,51,153) 0%, rgb( 101,101,203) 100%);"></td>
                    <td>184</td>
                </tr>
				<tr>
                    <th>RGB(246,247,249)</th>
                    <td class="gardijent" style="background: rgb(246,247,249);"></td>
					<th>razred(204,204,204)</th>
					<td><?php echo $g2fb ;?></td>
                    <td class="gardijent" style="background: linear-gradient(to right, rgb(204,204,204) 0%, rgb( 254,254,254) 100%);"></td>
                    <td>27</td>
                </tr>
				<tr>
                    <th>RGB(255,255,255)</th>
                    <td class="gardijent" style="background: rgb(255,255,255);"></td>
					<th>razred(255,255,255)</th>
					<td><?php echo $wfb ;?></td>
                    <td class="gardijent" style="background: linear-gradient(to right, rgb(255,255,255) 0%, rgb(255,255,255) 100%);"></td>
                    <td>1</td>
                </tr>
            </tbody>
        </table>
        <br>
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