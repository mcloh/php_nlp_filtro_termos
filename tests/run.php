<?php
require dirname(__DIR__) . '/src/PhpNlpFiltroTermos.php';
$filter = new PhpNlpFiltroTermos();
$blocked = array('m4c4qu1nh0', 'putana', 'baitola', 'estrume', 'raça inferior', 'voltem para seu país', 'eliminar os gays', 'negro imundo', 'caralio');
$allowed = array('Racismo e xenofobia devem ser combatidos.', 'A democracia precisa de participação pública.', 'A Constituição protege a dignidade de todas as pessoas.');
foreach ($blocked as $text) if (!$filter->contains($text)) { fwrite(STDERR, "Falha de bloqueio\n"); exit(1); }
foreach ($allowed as $text) if ($filter->contains($text)) { fwrite(STDERR, "Falso positivo\n"); exit(2); }
echo "Testes aprovados: 12 casos.\n";
