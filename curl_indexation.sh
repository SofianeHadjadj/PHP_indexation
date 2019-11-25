#!/bin/bash

if [[ "$#" -ne 1 ]]; then
    echo "Usage : ./curl_indexation.sh <url of website>"
else
	curl -d "url=$1" -X POST http://localhost/indexation_PHP/traitements/download.php
	curl http://localhost/indexation_PHP/vues/indexation.php	
fi

# numéroter resultats
# Afficher mots clés en nuage
# Supprimer l'affichage des mots clés -> mettre des statistiques (nb new word, nb word existants, temps de la requete)
# 