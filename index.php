<!DOCTYPE html>
<html>
<head>
  <title>Thyple</title>
  <link rel="stylesheet" type="text/css" href="vues/assets/css/style.css">
  <link rel="icon" type="image/png" href="vues/assets/img/favicon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="overflow: hidden;">
  <!-- <a href="vues/indexation.php"><div id="gear" title="Indexer un fichier"></div></a> -->
  <a href="vues/indexation.php"><div class="pulsating-circle pcp1" title="Indexer un fichier"><i class="fa fa-cog fa-2x"></i></div></a>
  <div id="recherche">
    <div id="logo">
      <span style="color : #4782F3">T</span>
      <span style="color : #DF3032">h</span>
      <span style="color : #F4C419">y</span>
      <span style="color : #4782F3">p</span>
      <!-- <span id="midPoint" style="color : #F4C419">·</span> -->
      <span id="letter5" style="color : #55BB50">l</span>
      <span id="letter6" style="color : #DF3032">e</span>
    </div>

    <div id="formulaire">
      <form action="vues/resultat.php" method="post">
        <span>
          <input id="searchbar" type="text" placeholder="Effectuez une recherche sur Thyple" name="inputText"/>
        </span>
      </form>       
    </div>    
  </div>

</body>
</html>