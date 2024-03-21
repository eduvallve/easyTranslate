<?php

function getLanguageFromURL() {
    if( isset($_GET['lang']) && $_GET['lang'] !== '' ) {
        setcookie("md_lang_cookie", $_GET['lang'], time() + (86400 * 30), "/");
    }
}

add_action('init', 'getLanguageFromURL');


function processLanguageName($languageName) {
    if (str_contains($languageName, '-')) {
        return explode('-', $languageName)[1];
    }
    return explode('-', $languageName)[0];
}


function getCurrentLanguage() {
    if( isset($_GET['lang']) && $_GET['lang'] !== '' ) {
        return $_GET['lang'];
    }
    $cookies = explode('; ', $_SERVER['HTTP_COOKIE']);
    foreach ($cookies as $cookie) {
        if (str_contains($cookie, 'md_lang_cookie')) {
            return explode('=', $cookie)[1];
        }
    }
    return getDefaultLanguage();
}

function menu_language_switcher(){
    $supportedLanguages = getSupportedLanguages();
    $currentLanguage = getCurrentLanguage();

    $output = "<select class='md-menu_language-switcher'>";

    foreach ($supportedLanguages as $languageCode) {
        $languageName = ucfirst(trim(processLanguageName(getLanguageName($languageCode))));
        $isCurrentLanguage = $languageCode === $currentLanguage ? 'selected' : '' ;
        $output .= "<option value='$languageCode' $isCurrentLanguage>$languageName</option>";
    }
    $output .= '</select>';

    return $output;
}

add_shortcode('my-dictionary-menu-language-switcher', 'menu_language_switcher');
?>