<?php

/**
 * Print progress bars.
 * Those functions need to be available in all variations of the Translate tab
 */

function createProgressBar($progressValue, $totalValue) {
    $percentage = intval($progressValue * 100 / $totalValue);
    return "<progress value='$progressValue' max='$totalValue'> $percentage% </progress> <span>$percentage%</span>";
}

function showSingleProgressBar($post) {
    $defaultLanguage = str_replace("-", "_", getDefaultLanguage());
    $supportedLanguages = getTranslationLanguages();
    $total = intval($post->$defaultLanguage) * count($supportedLanguages);
    $partial = 0;
    foreach ($supportedLanguages as $language) {
        $language = str_replace("-", "_", $language);
        $partial = $partial + intval($post->$language);
    }
    $isFinished = $partial === $total ? 'finished' : '' ;
    echo "<div class='md-translate__item-progress-box $isFinished'>".createProgressBar($partial, $total)."</div>";
}

?>

<div id="md-translate">
    <?php
        if ( isset($_GET['translate_id']) && $_GET['translate_id'] !== '' ) {
            // Page to apply translations in a post / page / etc.
            echo '<br>Apply translations to post/page #'.$_GET['translate_id'];
        } else {
            require_once 'translate.list.page.php';
        }
    ?>
</div>