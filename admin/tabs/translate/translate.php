<?php

/**
 * Print progress bars.
 * Those functions need to be available in all variations of the Translate tab
 */

function isNullValue($value) {
    return $value === 'null';
}

function createProgressBar($progressValue, $totalValue) {
    if ( isNullValue($progressValue) && isNullValue($totalValue) ) {
        return 'Not scanned yet!';
    } else {
        $percentage = intval($progressValue * 100 / $totalValue);
        return "<progress value='$progressValue' max='$totalValue'> $percentage% </progress> <span>$percentage%</span>";
    }
}

function showSingleProgressBar($post) {
    $defaultLanguage = convertLanguageCodesForDB(getDefaultLanguage());
    $supportedLanguages = getTranslationLanguages();
    if ( isNullValue($post->$defaultLanguage) ) {
        $isFinished = '';
        $total = $post->$defaultLanguage;
        $partial = 'null';
    } else {
        $total = intval($post->$defaultLanguage) * count($supportedLanguages);
        $partial = 0;
        foreach ($supportedLanguages as $language) {
            $language = convertLanguageCodesForDB($language);
            $partial = $partial + intval($post->$language);
        }
        $isFinished = $partial === $total ? 'finished' : '' ;
    }
    echo "<div class='md-translate__item-progress-box $isFinished'>".createProgressBar($partial, $total)."</div>";
}

?>

<div id="md-translate">
    <?php
        $GLOBALS['cfg']['actual_link'] = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ( isset($_GET['translate_id']) && $_GET['translate_id'] !== '' ) {
            // Page to apply translations in a post / page / etc.
            require_once 'translate.post.php';
        } else if ( !isset($_GET['translate_id']) && isset($_POST['scan_id']) && $_POST['scan_id'] !== '' ) {
            // Page to scan a post / page / etc.
            fillDictionaryTableByPost($_POST['scan_id']);
            // Reload the page to see latest changes
            reloadPage();
        } else {
            require_once 'translate.list.page.php';
        }
    ?>
</div>