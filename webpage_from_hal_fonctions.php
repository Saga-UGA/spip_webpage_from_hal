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
    $dom->preserveWhiteSpace = false;
   
    $str = mb_convert_encoding($content, "HTML-ENTITIES");
    @$dom->loadHTML($str);
    
    $xpath = new DOMXpath($dom);
    $entries = $xpath->query('//div[@id="res_script"]');
    
    if ($entries->length == 0) {
        spip_log("No tag found ...", _LOG_ERREUR);
        return;
    }

    $res_script = $dom->saveXML($entries->item(0));  
    
    return $res_script;
}