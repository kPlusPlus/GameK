  <?php
    $zip = new ZipArchive;
    
    if ($zip->open('test.zip') === TRUE) {
    	$zip->extractTo('/my/');
      $zip->close();
      echo 'ok';
    } 
    else {
      echo 'failed';
    }
  ?>