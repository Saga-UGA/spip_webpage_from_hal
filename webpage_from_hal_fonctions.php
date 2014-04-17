<?php

function hal_parse($url) {
    $url = trim(html_entity_decode($url), "\"' ");

    $infos = parse_url($url);
    $ip = gethostbyname($infos['host']);

    if ($ip != '193.48.96.10') {
        spip_log("Url invalid", _LOG_ERREUR);
        
        return;
    }

    spip_log(sprintf("[hal_parse] init_http(%s)", $url), _LOG_DEBUG);
    $content = recuperer_page($url);
    spip_log(sprintf("[hal_parse] init_http(%s): Done", $url), _LOG_DEBUG);

    $dom = new DomDocument('1.0', 'UTF-8');
    $str = mb_convert_encoding($content, "HTML-ENTITIES");
    @$dom->loadHTML($str);
    
    $tag = $dom->getElementById("res_script");

    if  (null === $tag) {
        spip_log("No tag found ...", _LOG_ERREUR);

        return;
    }

    $halPublis = _DOMinnerHTML($tag);

    return $halPublis;
}

function _DOMinnerHTML(DOMNode $element) {
    $innerHTML = ""; 
    $children  = $element->childNodes;
    
    foreach ($children as $child) {
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }
    
    return $innerHTML; 
}