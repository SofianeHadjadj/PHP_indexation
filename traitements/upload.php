<?php

header( "refresh:3;url=../vues/indexation.php" );

$origin_dir = "../vues/assets/files/not_indexed/";
$origin_file = $origin_dir . basename($_FILES["fileToUpload"]["name"]);
$target_dir = "../vues/assets/files/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image

if(!empty($_POST["submit"])) {
    $check = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);
    if($check == "text/html") {
        $uploadOk = 1;
    } else {
        echo "File is not an HTML file. (".$check.")";
        $uploadOk = 0;
    }
} else echo "Empty";
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 2000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "html" ) {
    echo "Sorry, only HTML files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        unlink($origin_file);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}


// array(5) {
//   ["name"]=>
//   string(12) "cronjob.html"
//   ["type"]=>
//   string(9) "text/html"
//   ["tmp_name"]=>
//   string(14) "/tmp/php0DrPc0"
//   ["error"]=>
//   int(0)
//   ["size"]=>
//   int(946)
// }

?>

<a href="../vues/indexation.php"></a>