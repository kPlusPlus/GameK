  <?php
    $zip = new ZipArchive;
    
    if ($zip->open('test.zip') === TRUE) {
    	$zip->extractTo('.');
      $zip->close();
      echo 'ok';
    } 
    else {
      echo 'failed';
    }
  ?>