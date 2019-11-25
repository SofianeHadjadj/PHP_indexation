<?php

  class Interaction {

    public function checkFiles($servername, $dbname, $username, $password, $directory, $listchar, $motsvides) {

      $action = new Traitement(); 
      $html_files = glob($directory."*.html");
      $var_db = array('servername' => $servername, 'dbname' => $dbname, 'username' => $username, 'password' => $password );

      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($html_files as $key => $value) {

          $file = str_replace($directory, '', $value);

          $doc = $conn->prepare("SELECT * FROM documents WHERE document = '".$file."'");
          $doc->execute();      
          $result = $doc->fetch();
          if (($result['document'] == $file)) {
            echo "Le document : <span style='font-weight:bold;'>" . $file . "</span> a déjà été indéxé. <br>";
          } else {
            echo "Document non indéxé : <span style='font-weight:bold;'>".$file."</span> (indexation en cours) : <br><br>";
            $url = $directory.$file;
            $html = file_get_contents($url);   
            $action->transfert(strtolower($html), $listchar, $motsvides, $var_db, $file);
          }
        } 
      }
      catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }

      $conn = null;
     
    }

    public function to_db($text, $servername, $dbname, $username, $password, $url, $titre, $description) {
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //$query = "INSERT INTO dictionnaire (url, mot, poids) VALUES (?, ?, ?)";

        $query = "INSERT INTO mots (mot) VALUES (?)";
        $query2 = "INSERT INTO documents (document, titre, description) VALUES (?, ?, ?)";
        $query3 = "INSERT INTO indexs (id_mot, id_doc, poids) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt2 = $conn->prepare($query2);
        $stmt3 = $conn->prepare($query3);
        foreach($text as $word => $occurence) {

          $encoding = mb_detect_encoding($word, mb_detect_order(), false);

          if($encoding == "UTF-8") {
              $word = mb_convert_encoding($word, 'UTF-8', 'UTF-8');    
          }

          $word = iconv(mb_detect_encoding($word, mb_detect_order(), false), "UTF-8//IGNORE", $word);

          if(strlen($word)> 2) {
            $app = $conn->prepare("SELECT * FROM mots WHERE mot = '".$word."'");
            $app->execute();

            $doc = $conn->prepare("SELECT * FROM documents WHERE document = '".$url."'");
            $doc->execute();         

            $result = $app->fetch();
            $result2 = $doc->fetch();
            $allword = $app->fetchAll();

            $idx = $conn->prepare("SELECT * FROM indexs WHERE id_mot = '".$result['id']."'");
            $idx->execute(); 
            $residx = $idx->fetch();


            if ($result2['document'] != $url) {
              $stmt2->execute([htmlspecialchars($url), htmlspecialchars($titre), htmlspecialchars($description)]);
              echo "New document created successfully : ".$url." <br>";
              $last_id_do = $conn->lastInsertId();
            } else {
              //echo "The document : \"".$url."\" already exist! <br>";
              $docid = $conn->prepare("SELECT * FROM documents WHERE document = '".$url."'");
              $docid->execute();
              $resdoc = $docid->fetch();
              $last_id_do = $resdoc['id'];
            }

            $idx2 = $conn->prepare("SELECT * FROM indexs WHERE id_mot = '".$result['id']."' AND id_doc = '".$last_id_do."'");
            $idx2->execute(); 
            $residx2 = $idx2->fetch();

            if ((!empty($word)) && ($word != " ") && ($word != "&nbsp;") && ($word != "&#160;")) {
              if (($result['mot'] != $word)) {
                $stmt->execute([htmlspecialchars(strtolower($word))]);
                //echo "New record created successfully : ".$word." <br>";
                $last_id_mo = $conn->lastInsertId();
              } 
              elseif (($result['mot'] === $word) && ($residx2 == false)) {
                $last_id_mo = $result['id'];
              } 
              else {
                echo "The word : \"".$result['mot']."\" id : \"".$result['id']."\" already exist! <br>";
              }

              if (!empty($last_id_mo) && !empty($last_id_do)) {
                $stmt3->execute([(int)$last_id_mo, (int)$last_id_do, htmlspecialchars($occurence)]);
                echo "New record created successfully : ".$word." <br>";
              }
            }

          }
        
        }
      }
      catch(PDOException $e) {
        echo " Request failed : " . $e->getMessage() ."<br>";
      }

      $conn = null;
    }    

  }

  class Traitement {

    var $body;
    var $head;
    var $new_title;
    var $new_keywords;
    var $new_description;    

    public function transfert($html, $listchar, $motvide, $var_db, $file) {
      $this->purifying($html, $listchar, $motvide);
      $this->get_head($html, $listchar, $motvide);
      $this->merge_array($var_db, $file);
    }

    public function purifying($html, $listchar, $motvide) {
      $html_core = preg_replace('#<title>(.+)</title>#isU', '', $html);
      $html_core = preg_replace('#<style[^>]*>(.*)</style>#isU', '', $html_core);
      $html_core = preg_replace('#<script[^>]*>(.*)</script>#isU', '', $html_core);
      $html_core = preg_replace('#<link[^>]*/>#isU', '', $html_core);
      $pure_html = strip_tags($html_core);
      $pure_html = preg_replace('/[0-9]+/', '', $pure_html);
      $pure_html = strtolower($pure_html);
      $pure_html = html_entity_decode($pure_html);
      $tab_body = $this->explodebis($pure_html, $listchar, $motvide);
      $tab_body_pure = $this->rm_bad_words($tab_body, $motvide);
      $this->body = $tab_body_pure;
    }    

    public function explodebis($texte, $listchar, $motvide) {
        $token = strtok($texte,$listchar);
         if(strlen($token)> 2) $tableau[] = $token;

        while( $token = strtok($listchar) )
        {
            if(strlen($token)> 2) {
              $tableau[] = $token;
            }
        }
        return $tableau;
    }

    public function rm_bad_words($tab, $motvide) {
      $found = false;

      foreach ($tab as $key_a => $val_a) {
        $found = false;
        foreach ($motvide as $key_b => $val_b) {
            if ($val_a == $val_b) {
                unset($tab[$key_a]);    
                $found = true;
            }     
        }
      }     
      $valtabs = array_count_values($tab);
      return $valtabs;

    }   

    public function get_head($html, $listchar, $motvide) {
      $head = "";
      $modtitle = '#<title>(.+)</title>#isU';
      $modkey = '#<meta[^>]*name="keywords"[^>]*content="(.+)"#isU';
      $moddesc = '#<meta[^>]*name="description"[^>]*content="(.+)"#isU';

      if (preg_match($modtitle, $html, $title)) {
        $head = $head.$title[1].",";
        $this->new_title = $title[1];
      }
      if (preg_match($modkey, $html, $keywords)) {
        $head = $head.$keywords[1].",";
        $this->new_keywords = $keywords[1];
      }
      if (preg_match($moddesc, $html, $description)) {
        $head = $head.$description[1].",";
        $this->new_description = $description[1]; 
      } 
      else {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $firstChildOfDiv = $dom->getElementsByTagName('p')->item(0)->childNodes->item(0);
        $firstParagraph = $firstChildOfDiv->nodeValue;
        $this->new_description = mb_substr(html_entity_decode($firstParagraph), 0, 230)."..."; // ajouter le parametre "utf-8" après 200 si problème
      }
      $tab_head = $this->explodebis($head, $listchar, $motvide);
      $tab_head_pure = $this->rm_bad_words($tab_head, $motvide);
      $this->head = $tab_head_pure;
    }
      
    public function print_tab($tags) {
      foreach($tags as $key => $value) {
        echo "$key : $value <br>";
      }
    }    

    public function merge_array($var_db, $file) {
      foreach ($this->head as $key => $value) {
        if (isset($this->body[$key])) {
          $this->body[$key] = $this->body[$key] + 2;
        } else {
          $this->body[$key] = 2;
        }
      }
      $demarrage = new Interaction();     
      $demarrage->to_db($this->body, $var_db['servername'], $var_db['dbname'], $var_db['username'], $var_db['password'], $file, $this->new_title, $this->new_description); 
    } 
  } 

?>