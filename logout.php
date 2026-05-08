<?php 
// 3. Aakhir mein footer file include karein

?>
<?php
// 1. Session start krna zaroori hai taqy usy khatam kia ja saky
session_start();

// 2. Tamam session variables ko khali (unset) kr dain
session_unset();

// 3. Session ko mukammal tor pr destroy (khatam) kr dain
session_destroy();

// 4. User ko redirect kr dain (Login page ya Index page pr)
header("Location: login.php");
exit();
?>

