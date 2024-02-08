<?php
// $_GET['translate_id']

function getSinglePostData($post_id) {
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
    return $GLOBALS['wpdb']->get_results($query_getSinglePostData)[0];
}


?>
<a href="?page=my-dictionary&tab=translate" class="md-translate__page--back">← Back to Global process</a>

<table class="md-translate__page--header">
    <tr>
        <td class="md-translate__page--header-title">
            <h2>Post name here</h2>
        </td>
        <td class="md-translate__page--header-progress">
            <div class="md-translate__page--header-progress-bar">
                Translated <?php showSingleProgressBar(getSinglePostData($_GET['translate_id'])); ?>
            </div>
        </div>
    </tr>
</table>

<div class="md-translate__page--type">TITLE</div>

<div class="md-translate__page--item">
    <span class="md-translate__page--item-original">Post name here</span>
    <div class="md-translate__page--item-translations">
        <label for="">Català <textarea rows="2"></textarea></label>
        <label for="">Español (España) <textarea rows="2"></textarea></label>
    </div>
</div>

<div class="md-translate__page--type">CONTENT</div>

