
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet_labels.css">
<link href="https://fonts.googleapis.com/css?family=Palanquin" rel="stylesheet">
<script src="https://use.fontawesome.com/d1c050bcc4.js"></script>
</head>
<body>

<?php

require_once('labelClasses.php');

$FishLabel = new FishLabel;

echo $FishLabel->createAllCards();

?>

</body>
</html>