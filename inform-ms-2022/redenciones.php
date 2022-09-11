<?php 

require_once "../app/inc/db.php";

$db = new ChefDB();

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=redencion_BDB4".date('Y-m-d').".xls");
header("Pragma: no-cache");
header("Expires: 0");
$list_awards = $db->redencionReport();

echo "<table border=1> ";
echo "<tr> ";
echo     "<th>idmask</th> ";
echo     "<th>nombre</th> ";
echo     "<th>precio</th> ";
echo     "<th>fecha</th> ";
echo "</tr> ";
while($row = mysqli_fetch_array($list_awards)){
    $price = number_format($row['value']);
    echo "<tr> ";
    echo 	"<td>".$row['idmask']."</td> "; 
    echo 	"<td>".$row['nombre']."</td> ";
    echo 	"<td>".$price."</td> ";
    echo 	"<td>".$row['fecha']."</td> ";  
    echo "</tr> ";

}
echo "</table> ";