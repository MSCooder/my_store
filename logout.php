<?php
// 1. Session start karein taake usay access kiya ja sake
session_start();

// 2. Tamam session variables ko khali kar dein
$_SESSION = array();

// 3. Agar session cookie mojood hai toh usay bhi expire kar dein (Security ke liye)
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// 4. Session ko mukammal taur par destroy kar dein
session_destroy();

// 5. User ko Login page ya Home page par redirect karein
// Aap Image 5 ke mutabiq login page par bhej sakte hain
header("Location: login.php?msg=logged_out");
exit();
?>