# php_nlp_filtro_termos

Filtro determinístico de texto para PHP 5.6+, voltado a linguagem ofensiva, baixo calão, variações morfológicas, leetspeak e padrões direcionados de discurso de ódio.

Não usa LLM em produção. A decisão é auditável e combina normalização Unicode opcional, padrões lexicais, flexões frequentes, Levenshtein e similaridade de trigramas.

```php
require 'src/PhpNlpFiltroTermos.php';
$filter = new PhpNlpFiltroTermos();
if ($filter->contains($texto)) {
    // recuse a gravação
}
```

Execute a regressão com `php tests/run.php`.

O filtro é uma camada preventiva; mantenha escape de saída, CSRF, limites de tamanho e revisão periódica do léxico.
