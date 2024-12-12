<?php
session_unset();
session_start();
date_default_timezone_set('America/Panama');
?>

<script>
    console.log('<?php echo date_default_timezone_get();?>')
</script>

<?php

if (!isset($_SESSION['user'])) {    
    header('Location: index.php?error=No has iniciado sesión.');
    exit;
}
?>