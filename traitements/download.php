<?php

	header( "refresh:3;url=../vues/indexation.php" );

	$url = $_POST['url'];

	check_url($url);

	function check_url($url) {

		if (isset($url)) {
			if (gettype($url) == string) {

				$url = preg_replace('/\s+/', '', $url);

				$file_headers = @get_headers($url);
				$OK200 = substr($file_headers[0], -6);

				if ($OK200 == '200 OK') {
					$html = file_get_contents($url);

					$filename = get_docname($url);
					$extension = substr($filename, -5);
					if ($extension == '.html') {
						$filename = $filename;
						$extLess = str_replace('.html', '', $filename);
					} else { 
						$extLess = $filename;
						$filename = $filename.".html";
					}
					$layoutFilename = preg_replace('/_|\.|\-/', ' ', $extLess);

					$titre = get_title($url);

					$dossier = "../vues/assets/files/";
					$path = $dossier.$filename;		

					download_html_file($html, $path, $filename, $layoutFilename, $titre);

					//echo "url : ".$url."<br>";
				} else echo "URL Inaccessible !<br>";
			} else echo "URL Invalide<br>";
		} else echo "URL vide<br>";
	}

    function get_title($url) {
    	if (isset($url)) {
	        $fp = file_get_contents($url);
	        if (!$fp) 
	            return null;

		    $modtitle = '#<title>(.+)</title>#isU';

		    if (preg_match($modtitle, $fp, $title))
	      		return $title[1];
	      	else return null;
    	} else {
    		echo "Format incorrect ! <br><br>";
    	}
    }

    function get_docname($url) {
    	if (isset($url)) {
			$urlArray = explode('/',$url);
			$docname = end($urlArray);
			return $docname;
    	} else {
    		echo "Format incorrect ! <br><br>";
    	}
    }

    function download_html_file($html, $path, $filename, $layoutFilename, $titre) {

    	if (isset($html)) {
			if (gettype($titre) != string) {

				$editfile = fopen($path, "w");

				$dom = new DOMDocument();
				$dom->loadHTML($html);

				$head = $dom->getElementsByTagName('head');

				$NEWtitle = $dom->createElement('title');
				$NEWtitle = $head[0]->appendChild($NEWtitle);

				$NEWtext = $dom->createTextNode(ucfirst($layoutFilename));
				$NEWtext = $NEWtitle->appendChild($NEWtext);

				fwrite($editfile, utf8_decode($dom->saveHTML()));

				echo "<br><br>Fichier : ".$filename." créé avec succès !<br>";	
				echo "Titre ajouté : " . ucfirst($layoutFilename);

				fclose($editfile);

			} else {
				$newfile = fopen($path, "w");

				fwrite($newfile, $html);

				fclose($newfile);	

				echo "Fichier : ".$filename." créé avec succès !";	
			}
    	}
	}


?>

<a href="../vues/indexation.php"></a>