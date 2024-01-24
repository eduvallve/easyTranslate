<?php
function isAjaxCall() {
    return !isset($_GET['page']) && isset($_GET['md_code']);
}

if ( isAjaxCall() ) {
    $languageCode = $_GET['md_code'];
    include "general.functions.php";
}

$languageName = getAllLanguages()[$languageCode];

if ( isset($languageName) ) {

    if ( isAjaxCall() ) { ?>
    <table>
    <?php } ?>

        <tr class="md-language__item<?php echo isset($isDefaultLanguage) ? '--default' : '' ; ?>">
            <td>
                <input type="radio" name="defaultLanguage" value="<?php echo $languageCode; ?>" <?php echo isset($isDefaultLanguage) ? 'checked' : '' ; ?>>
            </td>
            <td class="md-language__name">
                <?php echo $languageName; ?>
            </td>
            <td class="md-language__code">
                <?php echo $languageCode; ?>
                <input type="hidden" name="languages[]" value="<?php echo $languageCode; ?>">
            </td>
            <td class="md-language__action">
                <span class="md-language__action-remove" code=<?php echo $languageCode; ?>>Remove</span>
            </td>
        </tr>

    <?php
    if ( isAjaxCall() ) { ?>
    </table> <?php
    }
} ?>