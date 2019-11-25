# Moteur de recherche THYPLE

![Visuel application](https://raw.githubusercontent.com/SofianeHadjadj/indexation_PHP/master/vues/assets/img/thyple.png?token=AFRCAYYKGPL3ZU4PXS52NA253EUPU)

## Architecture projet

```
indexation_PHP/
├── index.php
├── README.md
├── traitements/
│   ├── indexation.php
│   ├── log_db.php
│   └── resultat.php
└── vues/
    ├── assets/
    │   ├── css/
    │   │   └── style.css
    │   ├── files/
    │   │   ├── document1.html
    │   │   ├── ...
    │   │   ├── [tous les documents indéxés ou à indexer au démarrage]
    │   │   ├── mots_vides.txt
    │   │   ├── not_indexed/
    │   │   │   ├── document2.html
    │   │   │   ├── ...
    │   │   │   └── [réserve de documents non-indéxés (ignorés)]
    │   │   └── test.html
    │   ├── fonts/
    │   │   ├── product-sans
    │   │   │   └── product-sans-regular.ttf
    │   │   └── roboto
    │   │       └── roboto-regular.ttf
    │   ├── img/
    │   │   ├── favicon.png
    │   │   ├── loupe_bleue.png
    │   │   ├── loupe.png
    │   │   └── thyple.png
    │   └── js/
    │       ├── jquery.min.js
    │       └── tags.js
    ├── indexation.php
    └── resultat.php
```

## Indexation
* Répare les mots cassés
* Verifie si null ou vide
* Vérifie si plus de 2 caracteres
* Vérifie si mot pure (sans chiffre, sans caractère spéciaux, sans espace)
* Indexe tous les fichier html du dossier source si pas encore indéxé


## Recherche 
* Supprime les caractères spéciaux
* Supprime les majuscules
* Supprime les espaces
* Propose des resultats similaires aucun resultat
* Recupére les mots-clé des resultats de la recherche
* Propose une recherche secondaire via les mots-clés