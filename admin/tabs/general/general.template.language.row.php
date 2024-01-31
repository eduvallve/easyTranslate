<?php

if (!function_exists('languageRowOutput')) {
    function languageRowOutput($languageCode, $languageName, $isDefaultLanguage) {
        ?>
            <tr class="md-language__item<?php echo isset($isDefaultLanguage) && $isDefaultLanguage === true ? ' md-language__item--default' : '' ; ?>">
                <td>
                    <input type="radio" name="defaultLanguage" value="<?php echo $languageCode; ?>" <?php echo isset($isDefaultLanguage) && $isDefaultLanguage === true ? 'checked' : '' ; ?>>
                </td>
                <td class="md-language__name">
                    <?php echo $languageName; ?>
                </td>
                <td class="md-language__code">
                    <?php echo $languageCode; ?>
                    <input type="hidden" name="languages[]" value="<?php echo $languageCode; ?>">
                </td>
                <td class="md-language__action">
                    <span class="md-language__action-remove dashicons-before dashicons-trash" data-code="<?php echo $languageCode; ?>">Remove</span>
                </td>
            </tr>
        <?php
    }
}

if (!function_exists('isAjaxCall')) {
    function isAjaxCall() {
        return !isset($_GET['page']) && isset($_GET['md_code']);
    }
}

if ( isAjaxCall() ) {
    $languageCode = $_GET['md_code'];
    require_once "../../admin.functions.php";
}

$languageName = getAllLanguages()[$languageCode];

if ( isset($languageName) ) {
    if ( isAjaxCall() ) {
        echo '<table>';
        languageRowOutput($languageCode, $languageName, $isDefaultLanguage);
        echo '</table>';
    } else {
        languageRowOutput($languageCode, $languageName, $isDefaultLanguage);
    }
}

?>