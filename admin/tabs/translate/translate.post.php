<?php
// $_GET['translate_id']

function getSinglePostData($post_id) {
    if ( isset($GLOBALS['cfg']['getSinglePostData']) ) {
        return $GLOBALS['cfg']['getSinglePostData'];
    } else {
        showFunctionFired('getSinglePostData()');
        $table = $GLOBALS['cfg']['table'];
        $defaultLanguage = convertLanguageCodesForDB(getDefaultLanguage());
        $translationLanguages = getTranslationLanguages();
        $addSelectLanguage = "";
        $addWhereLanguage = "";
        foreach ($translationLanguages as $key => $translationLanguage) {
            $lang = convertLanguageCodesForDB($translationLanguage);
            $addSelectLanguage .= ", count($lang) AS $lang ";
            $addWhereLanguage .= " OR $lang IS NOT NULL ";
        }

        $query_getSinglePostData = "SELECT $table.post_id, wp_posts.post_title AS post_title, wp_posts.guid AS post_guid, post_type AS post_type, count($defaultLanguage) AS $defaultLanguage $addSelectLanguage FROM $table, wp_posts WHERE ($defaultLanguage IS NOT NULL $addWhereLanguage) AND $table.post_id = wp_posts.ID AND $table.track_language = '$defaultLanguage' AND $table.post_id = $post_id GROUP BY $table.post_id";
        // echo $query_getSinglePostData.'<hr>';
        $singlePostData = $GLOBALS['wpdb']->get_results($query_getSinglePostData)[0];
        $GLOBALS['cfg']['getSinglePostData'] = $singlePostData;
        return $singlePostData;
    }
}

function showTranslationLine($savedPostText) {
    $defaultLanguage = convertLanguageCodesForDB(getDefaultLanguage());
    $rows = round( strlen($savedPostText->$defaultLanguage) / 50 );
    $rows = $rows > 2 ? $rows : 2 ;
    ?>
        <div class="md-translate__page--item">
            <span class="md-translate__page--item-original"><?php echo $savedPostText->$defaultLanguage; ?></span>
            <div class="md-translate__page--item-translations">
                <?php
                    foreach ($savedPostText as $lang => $languageVariant) {
                        if ( $lang !== $defaultLanguage ) {
                            ?>
                                <label for=""><span class="md-translate__page--item-translations-icon-mobile">⤷</span><?php echo $lang; ?> <textarea rows="<?php echo $rows; ?>"><?php echo $languageVariant; ?></textarea></label>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    <?php
}

function showTranslationLines() {
    $savedPostTexts = getSavedPostTexts($_GET['translate_id'], true);
    foreach ( $savedPostTexts as $savedPostText ) {
        showTranslationLine($savedPostText);
    }
}


?>
<a href="?page=my-dictionary&tab=translate" class="md-translate__page--back">← Back to Global process</a>

<table class="md-translate__page--header">
    <tr>
        <td class="md-translate__page--header-title">
            <h2><?php echo getSinglePostData($_GET['translate_id'])->post_title; ?></h2>
        </td>
        <td class="md-translate__page--header-progress">
            <div class="md-translate__page--header-progress-bar">
                Translated <?php showSingleProgressBar(getSinglePostData($_GET['translate_id'])); ?>
            </div>
        </div>
    </tr>
</table>

<div class="md-translate__page--type">TITLE</div>

<div class="md-translate__page--type">CONTENT</div>

<?php showTranslationLines(); ?>
