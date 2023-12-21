<!DOCTYPE html>
<html>
<head>
<title>Información para el valuador</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?=$url?>/assets/css/w3.css">
<link rel="stylesheet" href="<?=$url?>/assets/js/w3.js">
<link rel="stylesheet" href="<?=$url?>/assets/css/raleway.css">
<link rel="stylesheet" href="<?=$url?>/assets/css/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="<?=$url?>/assets/js/sweetalert.js"></script>
<script src="<?=$url?>/assets/js/qrcode/qrcode.min.js"></script>
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
  <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey noprint" onclick="w3_open();"><i class="fa fa-bars"></i>  Menu</button>
  <span class="w3-bar-item w3-right"><span class="noprint">Hola</span> <?=$GLOBALS['usuario_nombre']?></span>
</div>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

