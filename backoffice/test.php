<?php
echo "✅ PHP está funcionando!<br>";
echo "Versão do PHP: " . phpversion() . "<br>";
echo "Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Documento raiz: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Caminho atual: " . __DIR__ . "<br>";
?>
