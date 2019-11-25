<?php
  include('../traitements/log_db.php');
  include('../traitements/resultat.php');
?>

<!DOCTYPE html>
<html>
<head>
  <title>Thyple</title>
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <link rel="icon" type="image/png" href="assets/img/favicon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="assets/js/jquery.min.js"></script>
</head>
<body>

  <div id="smallbar">

    <a href="../index.php">
      <div id="logo_res">
        <span style="color : #4782F3">T</span>
        <span style="color : #DF3032">h</span>
        <span style="color : #F4C419">y</span>
        <span style="color : #4782F3">p</span>
        <span style="color : #55BB50">l</span>
        <span style="color : #DF3032">e</span>
      </div>
    </a>

    <div id="formulaire_res">
      <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
        <span>
          <input id="searchbar_res" type="text" placeholder="<?php echo $_POST['inputText'] ?>" name="inputText"/>
        </span>
      </form>       
    </div>  

    <a href="indexation.php"><div class="pulsating-circle pcp2" title="Indexer un fichier"><i class="fa fa-cog fa-2x"></i></div></a>

  </div>

  <div id="resultat">
    
    <?php

      $affichage = new resultat(); 
      $affichage->get_data($servername, $dbname, $username, $password, $directory, $listchar, $motsvides)

    ?>

  </div>

  <script src="assets/js/tags.js"></script>

</body>
</html>