  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey">

  </footer>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Close the sidebar with the close button
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}
//Mostar notificacioness
<?php if(isset($_GET['status']) && $_GET['status']=='1'){ 
          echo "Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: 'Datos actualizados',
                  showConfirmButton: false,
                  timer: 1500
                })";
}elseif(isset($_GET['status']) && $_GET['status']=='2'){
          echo "Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: 'Registro exitoso',
                  showConfirmButton: false,
                  timer: 1500
                })";
}elseif(isset($_GET['status']) && $_GET['status']=='e'){
                echo "Swal.fire({
                  position: 'top-end',
                  icon: 'error',
                  title: 'Error, intente nuevamente',
                  showConfirmButton: false,
                  timer: 1500
                })";
}elseif(isset($_GET['status']) && $_GET['status']=='b'){
                echo "Swal.fire({
                  position: 'top-end',
                  icon: 'info',
                  title: 'Registro eliminado',
                  showConfirmButton: false,
                  timer: 1500
                })";
} ?>
</script>
</body>
<link rel="stylesheet" href="<?=$url?>/assets/css/style.css">
<script src="<?=$url?>/assets/js/code.js"></script>
</html>