<?php
session_start();
session_unset();
session_destroy();

echo "<script>
        window.alert('Fechando sistema! Você deverá logar novamente para ter acesso.');
      </script>";

echo "<meta http-equiv='refresh' content='0;url=/SITELENDAS/index.php'>";
