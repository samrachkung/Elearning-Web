<?php

   // Database Connection Setup
   $db_name = 'mysql:host=localhost;dbname=course_db'; // Database connection string, specifying host and database name
   $user_name = 'root'; // Username for the database connection
   $user_password = ''; // Password for the database connection (empty string means no password)

   // Create a new PDO database connection object
   $conn = new PDO($db_name, $user_name, $user_password);

   // Unique ID Generation Function
   function unique_id() {
      $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'; // String containing all possible characters for the ID
      $rand = array(); // Initialize an array to store randomly selected characters
      $length = strlen($str) - 1; // Calculate the length of the string
      for ($i = 0; $i < 20; $i++) { // Loop to generate a 20-character long ID
          $n = mt_rand(0, $length); // Generate a random index within the range of the string length
          $rand[] = $str[$n]; // Append the randomly selected character to the array
      }
      return implode($rand); // Return the generated array of characters as a string
   }

?>
