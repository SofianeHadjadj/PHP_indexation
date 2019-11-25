<?php

  class Resultat {
    
    public function get_data($servername, $dbname, $username, $password) {

    $motcle = str_replace(' ', '', $_POST['inputText']);
    $motcle = preg_replace("/[^A-Za-zéèçàùêâôîûëäïöü]+/u", '', $motcle);

      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(isset($motcle)){

          $count = $conn->prepare("SELECT COUNT(*) as total FROM indexs as i INNER JOIN mots as m ON m.id = i.id_mot INNER JOIN documents as d ON i.id_doc = d.id WHERE m.mot = '".$motcle."'");

          $search = $conn->prepare("SELECT i.id as iid, m.id as mid, d.id as did, i.id_mot, i.id_doc, m.mot, i.poids, d.document, d.titre, d.description FROM indexs as i INNER JOIN mots as m ON m.id = i.id_mot INNER JOIN documents as d ON i.id_doc = d.id WHERE m.mot = '".$motcle."' ORDER BY poids DESC");

          $count->execute();
          $search->execute();

          $start = microtime(true);

          while ($numreq = $count->fetch()) {
            echo "<div class='thin'>About ".$numreq['total']." results";
          }

          $duration = microtime(true) - $start;
          $seconds = ($duration*60*60);
          $arrondi = round(($seconds*10), 2);    

          echo " in (".$arrondi.") seconds</div><br>";
          
          $forCorrect = $conn->prepare("SELECT * FROM  mots WHERE mots.mot = '".$motcle."'");
          $forCorrect->execute();
          $support = $forCorrect->fetch()['mot'];

          if (is_null($support) == false) { 

            echo '<div id="res_left">';
              echo "<div class='wrapper'>";
                $icloud=0;
                while ($research = $search->fetch()) {
                  $icloud++;
                  echo "<div id='sw".$icloud."' class='subWrap subWrapBig'>";
                  echo "<span class='resblue'><a href='assets/files/".$research['document']."'>".ucfirst($research['titre'])."</a></span>";
                  echo '<div id="cloud'.$icloud.'" class="cloud" title="Afficher le nuage de mots-clés"><div class="bottom_c"></div><div class="right_c"></div><div class="left_c"></div><div class="word">nuage<span id="scloud">+</span></div></div>';
                  echo "<div class='resgreen'>".$research['document']."</div>";
                  $utf_desc = html_entity_decode($research['description']);
                  $description = mb_substr($utf_desc, 0, 178, "utf-8")."...";
                  echo "<div class='resgrey'>".ucfirst($description)."</div>";
                  echo "<br><br>";
                  echo '</div>'; 
                }
              echo "</div>";
            echo "</div>";

            echo '<form id="secondForm" method="post" action="'.$_SERVER['PHP_SELF'].'">';
              echo '<div id="res_rigth">';
              $search->execute();
              $itags=0;
              while ($researchTwo = $search->fetch()) {
                $itags++;
                $gettags = $conn->prepare("SELECT i.id as iid, m.id as mid, d.id as did, i.id_mot, i.id_doc, m.mot, i.poids FROM indexs as i INNER JOIN mots as m ON m.id = i.id_mot INNER JOIN documents as d ON i.id_doc = d.id WHERE d.id = '".$researchTwo['did']."' ORDER BY poids DESC LIMIT 23");
                $gettags->execute();

                echo "<div class='fixTags'>";
                  echo "<div id='ct".$itags."' class='contTags hide'>";
                  while ($tag = $gettags->fetch()) {
                    echo '<input class="subsearch" type="submit" name="inputText" value="'.ucfirst($tag['mot']).'">';
                  }
                  echo "</div>";
                echo "</div>";
              }     
              echo '</div>';
            echo "</form>"; 
          } else {
            if (strlen($motcle) < 5) {
              $indice = 3;
            } else if (strlen($motcle) < 7) {
              $indice = 4;
            } else if (strlen($motcle) <= 10) {
              $indice = 5;
            } else {
              $indice = 6;
            }
            $similarSearch = $conn->prepare("SELECT mot FROM mots WHERE levenshtein('".$motcle."', mot) < ".(int)$indice."");
            $similarSearch->execute();          
            echo '<form id="secondForm" method="post" action="'.$_SERVER['PHP_SELF'].'">';
            while ($similar = $similarSearch->fetch()) {
              echo '<span class="simresult">Voir les resultats pour <input class="simsearch" type="submit" name="inputText" value="'.$similar['mot'].'"><span><br>';
            }
            echo '</form>';
          }
        }
      }
      catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
      $conn = null;
    }

    public function removeAccents($str) {
      $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
      $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
      return str_replace($a, $b, $str);
    }

  }

?>