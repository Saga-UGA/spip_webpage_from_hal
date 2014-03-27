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
    spip_log(sprintf("[hal_parse] init_http(%s): Done"), _LOG_DEBUG);

    $dom = new DomDocument();
    $dom->loadHTML($content);
    
    $tags = $dom->getElementsByTagname("body");

    if ($tags->length != 1) {
        spip_log("No or Many tag body founded ...", _LOG_ERREUR);

        return;
    }

    foreach ($tags as $tag) {
        $halPublis = _DOMinnerHTML($tag);
    }

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