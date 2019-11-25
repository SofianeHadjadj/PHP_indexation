<?php
  
  // # Mode debug activé
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);

  include('../traitements/log_db.php');
  include('../traitements/indexation.php');

  $directory = './assets/files/';
  $motsvides = file($directory."mots_vides.txt");
  $motsvides = array_filter(array_map('trim', $motsvides));
  $listchar = " .;,%£@_’:+=<#>\/-!([{\n\t}])\"'?“";

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

  <?php
    $demarrage = new Interaction(); 
    $demarrage->checkFiles($servername, $dbname, $username, $password, $directory, $listchar, $motsvides);
  ?>

  <h2>Enregistrer un nouveau document</h2>

  <h3>Via URL</h3>

  <form method="post" action="../traitements/download.php">
    Entrez l'url du document :
    <input type="text" name="url">  
    <br>
    <span style="color:red">Exemple = http://etude-volcans.e-monsite.com/pages/parties/parties/generalites-sur-les-volcans/generalites-sur-les-volcans.html</span>
    <br><br>
    <input type="submit" value="Valider">
  </form>

  <h3>Via Upload</h3>

  <form action="../traitements/upload.php" method="post" enctype="multipart/form-data">
      Selectionnez un document (html) :
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" name="submit">
  </form>

  <br><br>

  <a href="../index.php" style="font-size: 26px;font-weight: bold">Retour à la page de recherche</a>

</body>
</html>