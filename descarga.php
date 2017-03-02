<?php
//Si la variable archivo que pasamos por URL no esta 
//establecida acabamos la ejecucion del script.
if (!isset($_GET['pdf-file']) || empty($_GET['pdf-file'])) {
   exit();
}

//Utilizamos basename por seguridad, devuelve el 
//nombre del archivo eliminando cualquier ruta. 
$archivo = basename($_GET['pdf-file']);

if (is_file($archivo))
{
   header('Content-Type: application/pdf');
   header('Content-Disposition: attachment; filename='.$archivo);
   header('Content-Transfer-Encoding: binary');
   header('Content-Length: '.filesize($archivo));

   readfile($archivo);
}
else
   exit();

unlink( 'document.pdf');
exec( 'del C:\xampp\htdocs\TRAPdf\TRAPdf-translated.pdf /Q');
?>
