<?php

function areTranslationLanguages() {
    return count(getSupportedLanguages()) > 1 && count(getSupportedPostTypes()) > 0;
}

function welcomeMessage() {
    return '
        <span class="md-msg__body"><b>Welcome! 🎉</b><br>It&#x2019;s our first time working together! First, please <b>add</b> more languages in the <b>General</b> tab!</span>
        <a class="md-msg__action button-primary" href="?page=my-dictionary">Add languages</a>
    ';
}

/**
 * Start showGlobalLanguagesProgress area
 */

function getLogsByLanguage($language) {
    $table = $GLOBALS['wp_my_dictionary'];
    $language = str_replace("-", "_", $language);
    $query_getTranslations = "SELECT COUNT($language) AS $language FROM $table";
    $getTranslations = $GLOBALS['wpdb']->get_results($query_getTranslations);
    return intval(implode(array_column($getTranslations, $language)));
}

function createProgressBar($progressValue, $totalValue) {
    $percentage = intval($progressValue * 100 / $totalValue);
    return "<progress value='$progressValue' max='$totalValue'> $percentage% </progress> <span>$percentage%</span>";
}

function showGlobalLanguagesProgress() {
    $allSavedLogs = getLogsByLanguage(getDefaultLanguage());
    $translationLanguages = getTranslationLanguages();
    $output = "";
        foreach ($translationLanguages as $language) {
            $output .= "<span class='md-translate__language-global'><label>$language</label>";
            if ($allSavedLogs > 0) {
                $output .= createProgressBar(getLogsByLanguage($language),$allSavedLogs);
            } else {
                $output .= createProgressBar(0,100);
            }
            $output .= "</span>";
        }
    echo $output;
}

/**
 * Start showPostList area
 */

function showPostListByType($postType) {
    $query_getPostListByType = "SELECT DISTINCT(wp_posts.post_title) AS title, wp_posts.guid AS guid, post_date FROM wp_posts WHERE wp_posts.post_type = '$postType' ORDER BY wp_posts.post_date DESC";
    $getPostListByType = $GLOBALS['wpdb']->get_results($query_getPostListByType);
    $output = "";
    foreach ($getPostListByType as $postItem) {
        $output .= "<span class='md-translate__single-progress--item'>$postItem->title <a href='$postItem->guid' target='_blank' class='dashicons-before dashicons-external'></a></span>";
    }
    return $output;
}

function showPostList() {
    $supportedPostTypes = getSupportedPostTypes();
    foreach ($supportedPostTypes as $postType) {
        $postTypeName = ucfirst($postType);
        echo "<tr><td>$postTypeName</td><td colspan='2'>".showPostListByType($postType)."</td><td></td></tr>";
    }
}
?>

<div id="md-translate">
    <table class="form-table md-translate__global-progress">
        <tbody>
            <tr>
                <th colspan="2">Global progress</th>
            </tr>
            <tr>
                <td class="md-translate__global">
                    <?php
                        if ( areTranslationLanguages() ) {
                            showGlobalLanguagesProgress();
                        } else {
                            infoMessage(welcomeMessage());
                        }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="form-table md-translate__single-progress">
        <tbody>
            <tr>
                <th>Post type</th>
                <th>Post name</th>
                <th>Individual progress</th>
                <th>Scan</th>
            </tr>
            <?php if ( areTranslationLanguages() ) {
                    showPostList();
                } ?>
        </tbody>
    </table>
</div>