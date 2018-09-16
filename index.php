<?php
include_once 'baza.class.php';
$sqlBrojSudionikaUkupno="SELECT COUNT(user_id) FROM users;";
$brojSudionikaUkupno=0;
$brojSudionikaUkupno=dohvatiPodatak($sqlBrojSudionikaUkupno);
//2-dohvati iz baze

$brojPodatakaPoSudionikuProsjecno=0;
$brojPodataka=0;
$sqlBrojPodataka="SELECT count(page_data_id) FROM page_data;";
$brojPodataka=dohvatiPodatak($sqlBrojPodataka);
$brojPodatakaPoSudionikuProsjecno=$brojPodataka/$brojSudionikaUkupno;

$sqlBrojEnergetskihKlasaPoZaslonuProsjecno="select avg(brojKlasa) from (SELECT pd.page_data_id, COUNT(pc.energy_id) as brojKlasa FROM page_data pd left join page_has_energy_classes pc on pd.page_data_id=pc.page_id GROUP by 1) poScreenu;";
$brojEnergetskihKlasaPoZaslonuProsjecno=0;
$brojEnergetskihKlasaPoZaslonuProsjecno=dohvatiPodatak($sqlBrojEnergetskihKlasaPoZaslonuProsjecno);
//4-dohvati iz baze

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
      <a href="index.php" class="nav-link text-secondary active font-weight-bold">Početna </a>
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
  
  <section id="showcase">
    <div class="d-overlay bg-dark text-white">
      <div class="container bg-dark text-light">
        <div class="row">
                    <div class="col-lg-6 text-center">
            <h1 class="display-3 mt-5 pt-5">
              Energetska efikasnost vizualnog sučelja društvenih mreža
            </h1>
            <p class="lead">Stranica izrađena za praćenje podataka koji se prikupljaju prilikom provedbe istraživanja energetske efikasnosti vizualnog sučelja društvenih mreža!</p>
          </div>
          <div class="col-lg-6">
            <img src="img/efficiency.png" alt="" class="img-fluid d-none d-lg-block">
          </div>
          <div class="col-lg-6">
            <img src="img/book.png" alt="" class="img-fluid d-none d-lg-block">
          </div>
        </div>
		<div class="row">
		<div class="container d-flex flex-column row-hl py-5">
        <div class="row">
		<p>
	<div class="card border-info mb-3 my-5 p-2 item-hl">
            <div class="card-body">
                <h4 class="card-title text-dark">Ukupno sudionika</h4>
                <p class="card-text text-dark"><?php echo $brojSudionikaUkupno; ?></p>
            </div>
     </div>	
	 </p>
	 <p>
	 	<div class="card border-info mb-3 my-5 p-2 item-hl">
            <div class="card-body">
                <h4 class="card-title text-dark">Prosječan broj podataka po sudioniku:</h4>
                <p class="card-text text-dark"><?php echo $brojPodatakaPoSudionikuProsjecno; ?></p>
            </div>
     </div>	
	 </p>
	 <p>
	 	<div class="card border-info mb-3 my-5 p-2 item-hl">
            <div class="card-body">
                <h4 class="card-title text-dark">Prosječan broj energetskih klasa po zaslonu:</h4>
                <p class="card-text text-dark"><?php echo $brojEnergetskihKlasaPoZaslonuProsjecno; ?></p>
            </div>
     </div>	
	 </p>
	 	 <p>
	 	<div class="card border-info mb-3 my-5 p-2 item-hl">
            <div class="card-body">
                <h4 class="card-title text-dark">Ukupan broj prikupljenih podataka:</h4>
                <p class="card-text text-dark"><?php echo $brojPodataka; ?></p>
            </div>
     </div>	
	 </p>
	 </div> </div>	

        </div>
      </div>
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