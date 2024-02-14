<?php

function exists($data) {
    return isset($data) && $data !== '';
}

/**
 * Update data in the DataBase
 */

function updateDefaultLanguage($language) {
    showFunctionFired('<-> updateDefaultLanguage($language)');
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $query_updateDefaultLanguage = "UPDATE $tableMeta SET meta_key='defaultLanguage', meta_value='$language' WHERE meta_key='defaultLanguage'";
    $updateDefaultLanguage = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_updateDefaultLanguage));
}

function updateSupportedLanguages($supportedLanguages) {
    showFunctionFired('<-> updateSupportedLanguages($supportedLanguages)');
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $supportedLanguages = implode(",",$supportedLanguages);
    $query_updateSupportedLanguages = "UPDATE $tableMeta SET meta_key='supportedLanguages', meta_value='$supportedLanguages' WHERE meta_key='supportedLanguages'";
    $updateSupportedLanguages = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_updateSupportedLanguages));
    // Add column for each new language in wp_my_dictionary
    addNewDictionaryColumns();
}

function updateSupportedPostTypes($supportedPostTypes) {
    showFunctionFired('<-> updateSupportedPostTypes($supportedPostTypes)');
    $tableMeta = $GLOBALS['cfg']['tableMeta'];
    $supportedPostTypes = implode(",",$supportedPostTypes);
    $query_updateSupportedPostTypes = "UPDATE $tableMeta SET meta_key='supportedPostTypes', meta_value='$supportedPostTypes' WHERE meta_key='supportedPostTypes'";
    $updateSupportedPostTypes = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($query_updateSupportedPostTypes));
}

/**
 * Behaviour
 */

if ( exists($_POST['defaultLanguage']) ) {
    $successMsg = true;
    if ( isset($_POST['defaultLanguage']) ) {
        updateDefaultLanguage($_POST['defaultLanguage']);
    }
    if ( isset($_POST['languages']) ) {
        updateSupportedLanguages($_POST['languages']);
    }
    if ( isset($_POST['postTypes']) ) {
        updateSupportedPostTypes($_POST['postTypes']);
    } else {
        warningMessage("<span class='dashicons-before dashicons-info-outline'> At least one Post Type must be selected</span>");
        $successMsg = false;
    }

    if ($successMsg) {
        successMessage("<span class='dashicons-before dashicons-saved'> All changes saved successfully</span>");
    } else {
        successMessage("<span class='dashicons-before dashicons-saved'> <em>Language changes</em> have been saved successfully</span>");
    }
}

?>