<?php 

require_once "../app/inc/db.php";

$db = new ChefDB();

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=login_BDB4".date('Y-m-d').".xls");
header("Pragma: no-cache");
header("Expires: 0");
$list_user = $db->loginReport();

echo "<table border=1> ";
echo "<tr> ";
echo     "<th>idmask</th> ";
echo     "<th>ip</th> ";
echo     "<th>fecha</th> ";
echo "</tr> ";
while($row = mysqli_fetch_array($list_user)){
    echo "<tr> ";
    echo 	"<td>".$row['idmask']."</td> ";
    echo 	"<td>".$row['ip']."</td> ";
    echo 	"<td>".$row['fecha']."</td> ";
    echo "</tr> ";

}
echo "</table> ";


