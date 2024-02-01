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

function getLogsByLanguage() {
    if ( isset($GLOBALS['cfg']['logsByLanguage']) ) {
        return $GLOBALS['cfg']['logsByLanguage'];
    } else {
        showFunctionFired('getLogsByLanguage()');
        $table = $GLOBALS['cfg']['table'];
        $defaultLanguage = str_replace("-", "_", getDefaultLanguage());
        $supportedLanguages = getSupportedLanguages();
        $languageList = "";
        foreach ($supportedLanguages as $key => $supportedLanguage) {
            $lang = str_replace("-", "_", $supportedLanguage);
            $languageList .= " COUNT($lang) AS $lang ";
            $languageList .= $key !== count($supportedLanguages) - 1 ? ', ' : '' ;
        }
        $query_logsByLanguage = "SELECT $languageList FROM $table WHERE track_language = '$defaultLanguage'";
        $logsByLanguage = $GLOBALS['wpdb']->get_results($query_logsByLanguage);
        $logsByLanguage = (array) $logsByLanguage[0];

        $GLOBALS['cfg']['logsByLanguage'] = $logsByLanguage;
        return $logsByLanguage;
    }
}

function showGlobalLanguagesProgress() {
    $maxSavedLogs = getLogsByLanguage()[str_replace("-", "_", getDefaultLanguage())];
    // $maxSavedLogs = max(getLogsByLanguage());

    $translationLanguages = getTranslationLanguages();
    $output = "";
        foreach ($translationLanguages as $language) {
            $output .= "<span class='md-translate__language-global'><label>$language</label>";
            if ($maxSavedLogs > 0) {
                $output .= createProgressBar(getLogsByLanguage()[str_replace("-", "_", $language)],$maxSavedLogs);
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

function headingCells() {
    echo '<tr>
        <th scope="col">Title</th>
        <th scope="col">Progress</th>
    </tr>';
}

function createPostListByType($postType, $allPostListData) {
    $actualUrl = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $isEmpty = true;
    foreach ($allPostListData as $post) {
        if ($post->post_type === $postType) {
            $isEmpty = false;
            ?>
                <tr id="post-<?php echo $post->post_id; ?>" class="md-translate__item" data-colname="Title">
                    <td class="md-translate__item-title">
                        <strong>
                            <a class="row-title" href="<?php echo $actualUrl."&translate_id=".$post->post_id; ?>" aria-label="“<?php echo $post->post_title; ?>” (Translate)"><?php echo $post->post_title; ?></a>
                        </strong>
                        <div class="row-actions">
                            <span class="Translate">
                                <a href="<?php echo $actualUrl."&translate_id=".$post->post_id; ?>" aria-label="Translate “<?php echo $post->post_title; ?>”">Translate</a> | </span>
                            <span class="view">
                                <a href="<?php echo $post->post_guid; ?>" target="_blank" rel="bookmark" aria-label="View “<?php echo $post->post_title; ?>”">View</a></span>
                        </div>
                    </td>
                    <td class="md-translate__item-progress">
                        <?php showSingleProgressBar($post); ?>
                    </td>
                </tr>
            <?php
        }
    }

    if ( $isEmpty ) {
        ?> <tr><td colspan="2">No texts found under this post type</td></tr> <?
    }
}

function getAllPostListData() {
    showFunctionFired('getAllPostListData()');
    $table = $GLOBALS['cfg']['table'];
    $defaultLanguage = str_replace("-", "_", getDefaultLanguage());
    $translationLanguages = getTranslationLanguages();
    $addSelectLanguage = "";
    $addWhereLanguage = "";
    foreach ($translationLanguages as $translationLanguage) {
        $lang = str_replace("-", "_", $translationLanguage);
        $addSelectLanguage .= ", count($lang) AS $lang ";
        $addWhereLanguage .= " OR $lang IS NOT NULL ";
    }
    $query_getAllPostListData = "SELECT $table.post_id, wp_posts.post_title AS post_title, wp_posts.guid AS post_guid, post_type AS post_type, count($defaultLanguage) AS $defaultLanguage $addSelectLanguage FROM $table, wp_posts WHERE ($defaultLanguage IS NOT NULL $addWhereLanguage) AND $table.post_id = wp_posts.ID AND $table.track_language = '$defaultLanguage' GROUP BY $table.post_id ORDER BY post_type ASC, post_id DESC";
    // echo $query_getAllPostListData;
    return $GLOBALS['wpdb']->get_results($query_getAllPostListData);
}

function createPostListTable() {
    $allPostListData = getAllPostListData();
    $supportedPostTypes = getSupportedPostTypes();
    foreach ($supportedPostTypes as $postType) {
        ?>
            <div class="col-container md-container-<?php echo $postType; ?>">
                <div class="col-left"><?php echo ucfirst($postType); ?></div>
                <div class="col-right">
                    <div class="col-wrap">
                        <table class="striped widefat">
                            <thead>
                                <?php headingCells(); ?>
                            </thead>
                            <tbody>
                                <?php createPostListByType($postType, $allPostListData); ?>
                            </tbody>
                            <tfoot>
                                <?php headingCells(); ?>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php
    }
}

?>

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
<?php if ( areTranslationLanguages() ) {
    createPostListTable();
    } ?>