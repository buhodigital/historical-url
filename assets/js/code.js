function hideAlert($this){
    $this.style.display='none';
}

function showConfirmation(){
    document.getElementById('confirmation').style.display='block';
}


function copyToClipboard(text) {
    const type = 'text/plain';
    const blob = new Blob([text], {type});
    let data = [new ClipboardItem({[type]: blob})];
  
    navigator.clipboard.write(data).then(function() {
        Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Url copiado',
                showConfirmButton: false,
                timer: 1500
            })
    }, function() {
      console.log('Ups! No se copio');
    });
  }
  
  //
  document.getElementById('copy').addEventListener('click', function() {
    copyToClipboard(document.getElementById('short-url').innerHTML);
  });


