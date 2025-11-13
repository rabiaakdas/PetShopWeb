
<?php
/*
  $host="localhost";
  $kullanici="root";
  $parola="";
  $vt="petshop";

  $baglanti=mysqli_connect($host, $kullanici, $parola, $vt);
  mysqli_set_charset($baglanti, "UTF8");

  */

  
  $host = "localhost";
  $kullanici = "root";
  $parola = "";
  $vt = "petshop";

  // Error logging file
  $log_file = 'error_log.txt';

  // Enable error reporting
  error_reporting(E_ALL);
  ini_set('display_errors', 0); // Hide errors from being shown in the browser
  ini_set('log_errors', 1); // Enable error logging
  ini_set('error_log', $log_file); // Set the log file

  try {
      // Create connection
      $baglanti = mysqli_connect($host, $kullanici, $parola, $vt);
      
      // Check connection
      if (!$baglanti) {
          throw new Exception('Database connection failed: ' . mysqli_connect_error());
      }

      // Set character set to UTF-8
      mysqli_set_charset($baglanti, "UTF8");

  } catch (Exception $e) {
      // Log the error to the log file
      error_log($e->getMessage(), 3, $log_file);

      // Optionally, you can display a user-friendly message to the user
      echo 'An error occurred. Please try again later.';
  }
?>

