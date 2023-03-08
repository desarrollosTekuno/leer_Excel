<?php
require_once 'php/ips.php';
if (isset($_GET['no_proyecto']) && $_GET['no_proyecto'] != '') {
    $no_proyecto = $_GET['no_proyecto'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/solid.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row mt-2">
            <div class="col">
                <div class="card" style="max-width:100%;width: 20rem;margin:0 auto;">
                    <img style="width: 100px;margin:1px auto;" src="img/logo-excel.jpg" class="card-img-top img-fluid" alt="...">
                    <div class="card-body">
                        <form id="frmFiles" method="post" enctype="multipart/form-data">
                            <input class="form-control" type="hidden" name="no_proyecto" id="no_proyecto" value="<?php echo $no_proyecto; ?>">
                            <input id="adjunto" accept=".xlsx, .xlsm, .xls" name="adjunto" type="file" class="form-control" required>
                            <button id="subirArchivo" class="btn btn-block btn-primary w-100 mt-2" type="submit">Subir archivo</button>
                        </form>
                        <div id="progressArea" class="mt-2"></div>
                        <div id="uploadedArea"></div>
                        <div id="resultado"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/app.js"></script>

</body>

</html>