<?php
  //
  // Always setup the error handler, the timezone and read the config-file
  //

  set_error_handler('exceptions_error_handler');
  date_default_timezone_set('UTC');
  $INIFILE='../private/config.ini';

  try { 
    $config=parse_ini_file($INIFILE, true);
  } catch (Exception $e) {
    die('Can\'t read the $INIFILE');
  }



  function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
      return;
    }
    if (error_reporting() & $severity) {
      throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
  }

?>
