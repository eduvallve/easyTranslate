<?php
    function updatePostTranslatedValues() {

        $table = $GLOBALS['cfg']['table'];
        $defaultLanguage = convertLanguageCodesForDB(getDefaultLanguage());

        $translationLanguages = array_map(function($lang) {
            return convertLanguageCodesForDB($lang);
        }, getTranslationLanguages());

        foreach ($_POST['id'] as $key => $id) {
            $defaultText = $_POST[$defaultLanguage][$key];
            $applyTranslations = "";
            foreach ($translationLanguages as $i => $language) {
                $value = 'NULL';
                if ( $_POST[$language][$key] != '' ) {
                    $value = "\"{$_POST[$language][$key]}\"";
                }
                $applyTranslations .= " $language = $value ";
                if ( $i < count($translationLanguages) - 1 ) {
                    $applyTranslations .= ",";
                }
            }
            $updateQuery_translatePost = " UPDATE $table SET $applyTranslations WHERE id = $id ; ";
            $updateTranslatedValues = $GLOBALS['wpdb']->query($GLOBALS['wpdb']-> prepare($updateQuery_translatePost));
        }
    }

    updatePostTranslatedValues();
    successMessage("<span class='dashicons-before dashicons-saved'> All changes saved successfully</span>");
?>