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
            <a href="razredi.php" class="nav-link">Razredi</a>
          </li>
            <li class="nav-item px-2">
            <a href="efikasnost.php" class="nav-link active font-weight-bold">Efikasnost</a>
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
   <br>
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
								return $first->averageScreenPercentage < $second->averageScreenPercentage;
							});
	}
	  
	  echo "['Boja', 'Postotak', { role: 'style' }],";
	  for ($i = 0; $i <10; $i++) {
		if($i==(count($prikaz)-1)){
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getAverageScreenPercentage().",'".$prikaz[$i]->getHexValue()."'"."]";
		}else{
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getAverageScreenPercentage().",'".$prikaz[$i]->getHexValue()."'"."],";
		}
	}
	  ?>
]);
  var options = {title:'Prikaz 10 najčešćih energetskih klasa boja',height:800,bar:{gap: 0 },
  hAxis: {title: "Boje" , slantedText:true, slantedTextAngle:90 },
  vAxis: {title: "Prosječni postotak sučelja"}};
  var chart = new google.visualization.ColumnChart(document.getElementById('histogram1'));
  chart.draw(data, options);
}

function drawChart2() {
      var data2 = new google.visualization.DataTable();
  var data2 = google.visualization.arrayToDataTable([
      <?php 
	  	  if(count($prikaz)>0){
							usort($prikaz,function($first,$second){
								return $first->averageScreenPercentage > $second->averageScreenPercentage;
							});
	}
	  
	  
	  echo "['Boja', 'Postotak', { role: 'style' }],";
	  for ($i = 0; $i < 10; $i++) {
		if($i==(count($prikaz)-1)){
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getAverageScreenPercentage().",'".$prikaz[$i]->getHexValue()."'"."]";
		}else{
			echo "['".$prikaz[$i]->getRGBString()."',".$prikaz[$i]->getAverageScreenPercentage().",'".$prikaz[$i]->getHexValue()."'"."],";
		}
	}
	  ?>
]);
  var options2 = {title:'Prikaz 10 najrijeđih energetskih klasa boja',height:800,bar:{gap: 0 },
  hAxis: {title: "Boje" , slantedText:true, slantedTextAngle:90 },
  vAxis: {title: "Prosječni postotak sučelja"}
  };
  var chart2 = new google.visualization.ColumnChart(document.getElementById('histogram2'));
  chart2.draw(data2, options2);
}
</script>
  <br>
  <div class="container">
  <h2 class="display-4">20 najčešćih razreda po učestalosti</h2>
        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
					<th>#</th>
                    <th>OZNAKA RAZREDA (R,G,B)</th>
                    <th>potrošnja energije po pikselu (J)</th>
                    <th>Prikaz raspona boja</th>
					<th>Prosječni prostotak zaslona (%)</th>
                </tr>
            </thead>
            <tbody>
			<?php
						if(count($prikaz)>0){
							usort($prikaz,function($first,$second){
								return $first->averageScreenPercentage < $second->averageScreenPercentage;
							});
						}			
			
			
				//ispis iz polja
				$limit=20;
				if($limit>count($prikaz)) $limit=count($prikaz);
				for ($i = 0; $i < $limit ; $i++) {
						echo "<tr>";	
						echo "<td>";	
						echo ($i+1);	
						echo "</td>";
						echo "<td>";	
						echo "(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")";	
						echo "</td>";	
						echo "<td>";	
						echo $prikaz[$i]->getPower();	
						echo "</td>";
						echo "<td class='gardijent' style='background: linear-gradient(to right, rgb"."(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")"." 0%, rgb( ";
						echo $prikaz[$i]->getREnd() . ",";
						echo $prikaz[$i]->getGEnd(). ",";
						echo $prikaz[$i]->getBEnd();
						echo ") 100%)'";	
						echo ">";	
						echo "</td>";
						echo "<td>";	
						echo $prikaz[$i]->getAverageScreenPercentage();
						echo "</td>";
						echo "</tr>";			
				  
				}
			?>
            </tbody>
        </table>
        <br>
  </div>
    <div class="container">
  <h2 class="display-4">20 najrijeđih razreda po učestalosti</h2>
        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
					<th>#</th>
                    <th>OZNAKA RAZREDA (R,G,B)</th>
                    <th>potrošnja energije po pikselu (J)</th>
                    <th>Prikaz raspona boja</th>
					<th>Prosječni prostotak zaslona (%)</th>
                </tr>
            </thead>
            <tbody>
			<?php
						if(count($prikaz)>0){
							usort($prikaz,function($first,$second){
								return $first->averageScreenPercentage > $second->averageScreenPercentage;
							});
						}			
			
			
				//ispis iz polja
				$limit=20;
				if($limit>count($prikaz)) $limit=count($prikaz);
				for ($i = 0; $i < $limit ; $i++) {
						echo "<tr>";	
						echo "<td>";	
						echo ($i+1);	
						echo "</td>";
						echo "<td>";	
						echo "(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")";	
						echo "</td>";	
						echo "<td>";	
						echo $prikaz[$i]->getPower();	
						echo "</td>";
						echo "<td class='gardijent' style='background: linear-gradient(to right, rgb"."(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")"." 0%, rgb( ";
						echo $prikaz[$i]->getREnd() . ",";
						echo $prikaz[$i]->getGEnd(). ",";
						echo $prikaz[$i]->getBEnd();
						echo ") 100%)'";	
						echo ">";	
						echo "</td>";
						echo "<td>";	
						echo $prikaz[$i]->getAverageScreenPercentage();
						echo "</td>";
						echo "</tr>";			
				  
				}
			?>
            </tbody>
        </table>
        <br>
  </div>
  
  
  
  
  <div class="container">
  <h2 class="display-4">Učestalost pojavljivanja energetskih razreda</h2>
        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
					<th>#</th>
                    <th>OZNAKA RAZREDA (R,G,B)</th>
                    <th>potrošnja energije po pikselu (J)</th>
                    <th>Prikaz raspona boja</th>
					<th>Prosječni prostotak zaslona (%)</th>
                </tr>
            </thead>
            <tbody>
			<?php
						if(count($prikaz)>0){
							usort($prikaz,function($first,$second){
								return $first->averageScreenPercentage < $second->averageScreenPercentage;
							});
						}			
			
			
				//ispis iz polja
				for ($i = 0; $i < count($prikaz); $i++) {
						echo "<tr>";	
						echo "<td>";	
						echo ($i+1);	
						echo "</td>";
						echo "<td>";	
						echo "(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")";	
						echo "</td>";	
						echo "<td>";	
						echo $prikaz[$i]->getPower();	
						echo "</td>";
						echo "<td class='gardijent' style='background: linear-gradient(to right, rgb"."(".$prikaz[$i]->getR().",".$prikaz[$i]->getG().",".$prikaz[$i]->getB().")"." 0%, rgb( ";
						echo $prikaz[$i]->getREnd() . ",";
						echo $prikaz[$i]->getGEnd(). ",";
						echo $prikaz[$i]->getBEnd();
						echo ") 100%)'";	
						echo ">";	
						echo "</td>";
						echo "<td>";	
						echo $prikaz[$i]->getAverageScreenPercentage();
						echo "</td>";
						echo "</tr>";			
				  
				}
			?>
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