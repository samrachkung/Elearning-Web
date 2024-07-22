<?php

// Include the file responsible for database connection
include '../components/connect.php';

// Check if the form is submitted
if(isset($_POST['submit'])){

   // Generate a unique ID for the tutor
   $id = unique_id();

   // Sanitize and retrieve name from the form data
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   // Sanitize and retrieve profession from the form data
   $profession = $_POST['profession'];
   $profession = filter_var($profession, FILTER_SANITIZE_STRING);

   // Sanitize and retrieve email from the form data
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   // Hash the password for security
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Hash the confirm password for security
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Retrieve image file name from the form data
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);

   // Get the file extension
   $ext = pathinfo($image, PATHINFO_EXTENSION);

   // Rename the image file with a unique ID
   $rename = unique_id().'.'.$ext;

   // Retrieve image file size from the form data
   $image_size = $_FILES['image']['size'];

   // Retrieve temporary location of the image file
   $image_tmp_name = $_FILES['image']['tmp_name'];

   // Define the folder path to store uploaded images
   $image_folder = '../uploaded_files/'.$rename;

   // Prepare and execute query to check if email already exists
   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
   $select_tutor->execute([$email]);
   
   // Check if email already exists in the database
   if($select_tutor->rowCount() > 0){
      $message[] = 'email already taken!';
   }else{
      // If email is unique, proceed with registration
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         // Prepare and execute query to insert new tutor data into database
         $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
         $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);

         // Move uploaded image file to the designated folder
         move_uploaded_file($image_tmp_name, $image_folder);

         $message[] = 'new tutor registered! please login now';
      }
   }
}

?>

<!-- HTML starts here -->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
// Display messages if any
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- register section starts  -->

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>register new</h3>
      <div class="flex">
         <div class="col">
            <p>your name <span>*</span></p>
            <input type="text" name="name" placeholder="enter your name" maxlength="50" required class="box">
            <p>your profession <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- select your profession</option>
               <!-- Add more profession options here if needed -->
            </select>
            <p>your email <span>*</span></p>
            <input type="email" name="email" placeholder="enter your email" maxlength="20" required class="box">
         </div>
         <div class="col">
            <p>your password <span>*</span></p>
            <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
            <p>confirm password <span>*</span></p>
            <input type="password" name="cpass" placeholder="confirm your password" maxlength="20" required class="box">
            <p>select pic <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p class="link">already have an account? <a href="login.php">login now</a></p>
      <input type="submit" name="submit" value="register now" class="btn">
   </form>

</section>

<!-- register section ends -->

<!-- JavaScript to toggle dark mode -->
<script>

let darkMode = localStorage.getItem('dark-mode');
let body = document.body;

const enableDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enableDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>
