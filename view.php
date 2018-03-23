<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'configs.php';

$conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
mysqli_set_charset($conn,"utf8");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$result = $conn->query("SELECT `path` FROM document WHERE id = " . $_GET['file'] . "");
$row = mysqli_fetch_assoc($result);       


?>

<html>
<head>
<meta charset="utf-8">
<title>Display the pdf file using php</title>
</head>
<body>
<?php
$file = 'uploads/'. $row['path'];
$filename = 'huafdocument.pdf';
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file));
header('Accept-Ranges: bytes');
@readfile($file);
?>

</body>
</html>