<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// if ($_SERVER['REMOTE_ADDR'] == '181.129.44.26' || $_SERVER['REMOTE_ADDR'] == '181.57.146.242') {
include __DIR__ . "/../app/inc/security.php";
include __DIR__ . "/../app/inc/funtions.php";
// } else {
//   header('location: ../');
//   die();
// }
$type = 'product';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$QuantumTest = true;
if ($QuantumTest) {
  $QuantumRestUrl = 'wsrest'; //urltest
  $headers = [
    "user:chefc",
    "token:cc_9bvx20m*",
    "Content-Type:application/json"
  ];
} else {
  // $QuantumRestUrl = 'wsv3'; //urlproduccion
  // $headers = [
  //   "user:chef",
  //   "token:fKqm02uN",
  //   "Content-Type:application/json"
  // ];
}

if (count($_GET) == 0) {
  $vars = "";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/getbrands.json");
  curl_setopt($ch, CURLOPT_POST, 1);
  //curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $server_output = curl_exec($ch);
  curl_close($ch);
  $output = json_decode($server_output, true);
  echo ('<pre>');
  print_r($output["response"]["message"]);
  echo ('</pre>');
}
// verificar productos
elseif ($type === 'brand') {
  $vars = "";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/getproducts.json");
  curl_setopt($ch, CURLOPT_POST, 1);
  //curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  echo '<h1>brand ' . $brand . '</h1>';
  $postData = [
    'brand_id' => $brand
  ];
  // $p = "80";
  // $price = $p . "000";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
  $server_output = curl_exec($ch);
  $output = json_decode($server_output, true);
  echo ('<pre>');
  print_r($output["response"]["message"]);
  echo ('</pre>');
  curl_close($ch);
  // $array_uno = $output["response"]["message"];
  // foreach ($output["response"]["message"] as $item) {
  //   // if ($item["pvp"] == $price) {
  //     $idbono = $item["product_id"];
  //     // break;
  //   // }
  // }
  // echo $idbono;
  //



}
// verificar redencion
elseif ($type === 'redeem') {
  die('redimir');
  $product = isset($_GET['product']) ? $_GET['product'] : '';
  $id = isset($_GET['id']) ? $_GET['id'] : '';
  $price = isset($_GET['price']) ? $_GET['price'] : '';
  if ($product && $id && $price) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/redeem.json");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $postData = [
      'brand_id' => $brand,
      "product_id" => $product,
      "user_data" => array(
        'email' => 'chenao@chefcompany.co',
        "name" => "",
        'birthdate' => '',
        "id" => $id
      )
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    $server_output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($server_output, true);
    $idtxt = $output["response"]["trxid"];
    $json = $output["response"]["url"];
    if ($idtxt != "" and $json != "") {
      $db->postError($id, $brand, $product, "0");
      $db->postGanopremio($product, $id, $idtxt, $json, $price, 1);
    }
    echo '<pre>';
    print_r($output["response"]);
    echo '</pre>';

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL,"https://wsv3.activarpromo.com/api/redeem.json");
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //
    // $postData = [
    //     'brand_id' => $brand,
    //     "product_id" => $idbono,
    //     "user_data" => array(
    //       'email' => 'chenao@chefcompany.co',
    //       "name" => "",
    //       'birthdate' => '',
    //       "id" => "00001137FD693EBBCCD211DDA83D0000"
    //     )
    // ];
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    // $server_output = curl_exec ($ch);
    // curl_close ($ch);
    // $output = json_decode($server_output,true);
    // print_r($output); //Print empty
    //    echo('<pre>');
    // var_dump($output["response"]["message"]);
    // echo('</pre>');
    //
    // echo $output["response"]["trxid"];
    // echo $output["response"]["url"];
    //
    // echo "string";
  } else {
    die('parametros no válidos');
  }
} elseif ($type === 'pre-redeem') {
  $product = isset($_GET['product']) ? $_GET['product'] : '';
  $id = 'testChef';
  // $price = isset($_GET['price']) ? $_GET['price'] : '';
  // if ($product && $id && $price) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://" . $QuantumRestUrl . ".activarpromo.com/api/preredeem.json");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $postData = [
    'brand_id' => $brand,
    "product_id" => $product,
    "user_data" => array(
      'email' => 'chenao@chefcompany.co',
      "name" => "",
      'birthdate' => '',
      "id" => $id
    )
  ];
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
  $server_output = curl_exec($ch);
  curl_close($ch);
  $output = json_decode($server_output, true);
  echo '<pre>';
  print_r($output["response"]);
  echo '</pre>';
} elseif ($type === 'table') {
  require_once __DIR__ . "/../app/inc/functionsQuantum.php";
  $quantum = new consumeQuantum();
  $brands = $quantum->getBrands();
  $list = [];
  foreach ($brands as $brand) {
    $products = $quantum->getProductsByBrand($brand['brand_id']);
    if ($products) {
      foreach ($products as $product) {
        $list[] = array_merge(['logo' => $brand['logo'], 'location' => $brand['location']], $product);
      }
    }
  }
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="minimum-scale=1, initial-scale=1, width=device-width" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.3.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!-- Fonts to support Material Design -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" /> -->
    <!-- Icons to support Material Design -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" /> -->
    <title>Productos Quantum</title>
  </head>

  <body>
    <div class="container">

      <table id="MyTable" class="display" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>Nombre producto</th>
            <th>Producto id</th>
            <th>Descripcion</th>
            <th>Marca</th>
            <th>Marca id</th>
            <th>Locacion</th>
            <th>Valor</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>Nombre producto</th>
          </tr>
        </tfoot>
        <tbody>
          <?php foreach ($list as $item) { ?>
            <tr>
              <td><?php echo $item['name'] ?></td>
              <td><?php echo $item['product_id'] ?></td>
              <td><?php echo $item['description'] ?></td>
              <td><?php echo $item['brand_name'] ?></td>
              <td><?php echo $item['brand_id'] ?></td>
              <td><?php echo ($item['location'] == 1) ? 'Si' : 'No'; ?></td>
              <td>$<?php echo number_format($item['pvp'], 2, ',', '.') ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </div>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#MyTable').DataTable({
          initComplete: function() {
            this.api().columns().every(function() {
              var column = this;
              var select = $('<select><option value=""></option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function() {
                  var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                  );
                  //to select and search from grid  
                  column
                    .search(val ? '^' + val + '$' : '', true, false)
                    .draw();
                });

              column.data().unique().sort().each(function(d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
              });
            });
          },
          "pageLength": 50,
          "language": {
            "search": "Buscar:",
            "info": "Mostrando _START_ de _END_ de _TOTAL_ registros",
            "paginate": {
              "first": "Primero",
              "last": "Ultimo",
              "next": "Siguiente",
              "previous": "Anterior"
            },
            "lengthMenu": "Mostrando _MENU_ registros",
          },
            dom: 'Bfrtip',
            buttons: ['excel','pageLength']
        });
      });
    </script>

  </body>

  </html>
<?php
}
