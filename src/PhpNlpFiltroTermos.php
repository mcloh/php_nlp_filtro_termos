<?php

/** Filtro determinístico de linguagem ofensiva e discurso de ódio, compatível com PHP 5.6+. */
class PhpNlpFiltroTermos
{
    public function contains($text)
    {
        $text = $this->normalize($text);
        if ($text === '') return false;
        foreach ($this->patterns() as $pattern) if (preg_match('/(?:^| )' . $pattern . '(?: |$)/', $text)) return true;
        foreach (preg_split('/ +/', $text) as $token) if ($this->fuzzy($token)) return true;
        return false;
    }

    public function normalize($text)
    {
        if (class_exists('Normalizer')) {
            $text = Normalizer::normalize($text, Normalizer::FORM_D);
            $text = preg_replace('/\p{Mn}+/u', '', $text);
        }
        $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
        $map = array('á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c','а'=>'a','е'=>'e','о'=>'o','р'=>'p','с'=>'c','х'=>'x','і'=>'i','@'=>'a','$'=>'s','€'=>'e','0'=>'o','1'=>'i','!'=>'i','2'=>'z','3'=>'e','4'=>'a','5'=>'s','6'=>'g','7'=>'t','8'=>'b','9'=>'g','+'=>'t','|'=>'i','%'=>'x');
        $text = strtr($text, $map);
        $text = preg_replace('/(.)\1+/u', '$1', $text);
        return trim(preg_replace('/[^a-z0-9]+/', ' ', $text));
    }

    public function highlightHtml($text)
    {
        $matches = array(); $html = ''; $offset = 0; $found = false;
        preg_match_all('/\S+/u', $text, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[0] as $match) {
            $word = $match[0]; $position = $match[1];
            $html .= htmlspecialchars(substr($text, $offset, $position - $offset), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $safe = htmlspecialchars($word, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            if ($this->contains($word)) { $html .= '<mark class="risk-highlight">' . $safe . '</mark>'; $found = true; }
            else $html .= $safe;
            $offset = $position + strlen($word);
        }
        $html .= htmlspecialchars(substr($text, $offset), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        return $found ? $html : '';
    }

    private function patterns()
    {
        return array(
            'merd(?:a|as|inha|inhas|ao|oes)?','bost(?:a|as|inha|inhas|ao|oes)?','por(?:a|as|inha|inhas|ao|oes)','caralh(?:o|os|a|as|inho|inha|inhos|inhas|ozinho|ozinha|ao|oes)?','cacete(?:s|inho|inha)?','fod(?:a|as|ao|oes|er|eu|e|em|endo|ido|ida|idos|idas|ase|asse|am)?','fud(?:e|er|eu|ido|ida|idos|idas|ase|am)?','pqp','fdp','vsf','vtmnc','tmnc','put(?:a|as|o|os|inha|inhas|inho|inhos|aria|arias|ao|oes)?','putan(?:a|as|ao|oes|inha|inhas)?','bucet(?:a|as|inha|inhas|ao|oes)?','pic(?:a|as|inha|inhas|ao|oes)?','piroc(?:a|as|inha|inhas|ao|oes)?','pau(?:s|zinho|zinhos|zao|zoes)?','cu(?:s|zinho|zinhos|zao|zoes)?',
            'arombad(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','escrot(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','babac(?:a|as|ona|onas|inha|inhas|ao|oes)?','idiot(?:a|as|o|os|inha|inhas|inho|inhos|ice|oes)?','imbecil(?:is|zinho|zinha|zinhos|zinhas)?','otari(?:o|a|os|as|inho|inha|inhos|inhas|ozinho|ozinha|ao|oes)?','troux(?:a|as|inha|inhas|ao|oes)?','canalh(?:a|as|inha|inhas|ao|oes)?','safad(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','vagabund(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','nojent(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','lix(?:o|os|inho|inha|inhos|inhas|ao|oes)?','desgracad(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','maldit(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','corn(?:o|a|os|as|ona|onas|inho|inha|inhos|inhas|ao|oes)?','asn(?:o|a|os|as|inho|inha|inhos|inhas)?','jument(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','retardad(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?',
            'viad(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','bich(?:a|as|inha|inhas|ao|oes)?','travec(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','sapat(?:ao|o)(?:s|zinho|zinha|zinhos|zinhas)?','baitol(?:a|as|inha|inhas|ao|oes)?','macaqu(?:inho|inha|inhos|inhas|ao|oes)?','macac(?:o|a|os|as|ona|onas|inho|inha|inhos|inhas|ao|oes)?','crioul(?:o|a|os|as|inho|inha|inhos|inhas|ao|oes)?','estrum(?:e|es|inho|inha|inhos|inhas)?','macumbeir(?:o|a|os|as|inho|inha|inhos|inhas)?','feminazi(?:s)?','mulherzinha(?:s)?','nigg(?:er|ers|a|as)?',
            'fuck(?:er|ers|ing|ed)?','shit(?:y|head|heads)?','ashole(?:s)?','bitch(?:es|y)?','bastard(?:s)?','cunt(?:s)?','dick(?:s|head|heads)?','whore(?:s)?','slut(?:s|ty)?','motherfucker(?:s)?','dumbass(?:es)?','moron(?:s)?','wanker(?:s)?','prick(?:s)?','scumbag(?:s)?',
            '(?:negro|negra|preto|preta|judeu|judia|arabe|muculmano|imigrante|estrangeiro|gringo|indigena|indio|asiatico|oriental|gay|lgbt|lgbtqia) (?:imund(?:o|a|os|as)|inferior(?:es)?|nojent(?:o|a|os|as)|lix(?:o|os)|verme(?:s)?)','(?:raca|sangue) (?:inferior|puro|pura|puros|puras)','(?:volte|voltem) para (?:seu|o) pais','(?:fora|expulsem) (?:os|as)? ?(?:imigrante|estrangeiro|gringo|arabe|muculmano|indigena|indio)s?','(?:matar|eliminar|exterminar|limpar) (?:os|as)? ?(?:gay|lgbt|lgbtqia|judeu|judia|negro|negra|preto|preta|muculmano|imigrante|indigena|indio)s?'
        );
    }

    private function fuzzy($token)
    {
        if (!preg_match('/^[a-z0-9]{5,30}$/', $token)) return false;
        $refs = array('merda','bosta','porra','caralho','foder','fudido','puta','putana','buceta','piroca','arrombado','escroto','imbecil','retardado','viado','traveco','baitola','macaquinho','estrume','piranha','vagabundo','motherfucker','asshole');
        foreach ($refs as $ref) {
            $ref = $this->normalize($ref); $distance = levenshtein($token, $ref); $max = strlen($ref) >= 9 ? 2 : 1;
            if ($distance <= $max && $this->jaccard($token, $ref) >= .25 && $this->cosine($token, $ref) >= .40) return true;
        }
        return false;
    }
    private function grams($s) { $g=array(); for($i=0;$i<=strlen($s)-3;$i++){ $x=substr($s,$i,3); $g[$x]=isset($g[$x])?$g[$x]+1:1; } return $g; }
    private function jaccard($a,$b) { $a=array_keys($this->grams($a));$b=array_keys($this->grams($b));$u=array_unique(array_merge($a,$b));return $u?count(array_intersect($a,$b))/count($u):0; }
    private function cosine($a,$b) { $a=$this->grams($a);$b=$this->grams($b);$d=0;$aa=0;$bb=0;foreach($a as $k=>$v){$aa+=$v*$v;$d+=$v*(isset($b[$k])?$b[$k]:0);}foreach($b as $v)$bb+=$v*$v;return $aa&&$bb?$d/sqrt($aa*$bb):0; }
}
