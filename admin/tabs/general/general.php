<?php
/**
 * General tab here
 */

function createLanguageSelect($defaultLanguage = false) {
    $listOutput = "";
    foreach( getAllLanguages() as $abbr => $language ) {
        $listOutput .= "<option title='{$abbr}' value='{$abbr}'";
        if ($defaultLanguage && $defaultLanguage === $abbr ) {
            $listOutput .= ' selected';
        }
        $listOutput .= ">".$language."</option>";
    }
    return $listOutput;
}

function fillLanguageRow($languageCode, $isDefaultLanguage) {
    include "general.template.language.row.php";
}

function showAvailablePostTypes() {
    $postTable = $GLOBALS['cfg']['postTable'];
    showFunctionFired('showAvailablePostTypes()');
    $query_getAvailablePostTypes = "SELECT DISTINCT(post_type) FROM $postTable WHERE post_type != 'revision'";
    $getAvailablePostTypes = $GLOBALS['wpdb']->get_results($query_getAvailablePostTypes);
    $availablePostTypes = array_column($getAvailablePostTypes, 'post_type');
    $supportedPostTypes = getSupportedPostTypes();
    foreach ($availablePostTypes as $availableType) {
        echo "<label class='md-post-type__label' for='post-type-$availableType'><input type='checkbox' name='postTypes[]' id='post-type-$availableType' value='$availableType'";
        foreach ($supportedPostTypes as $supportedType) {
            echo $availableType === $supportedType ? 'checked' : '' ;
        }
        echo ">$availableType</label>";
    }
}

if ( isset($_POST) && count($_POST) > 0 ) {
    include "general.update.php";
}
?>

<form id="md-general" method="post" data-plugin-url="<?php echo plugin_dir_url( __FILE__ ); ?>">
    <table class="form-table">
        <tbody>
            <tr>
                <th>Supported languages</th>
                <td>
                    <div class="md-language__area">
                        <table class="md-language__table">
                            <thead>
                                <th class="md-language__default-title">Default</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                $defaultLanguage = getDefaultLanguage();
                                if ( count(getSupportedLanguages()) > 0 ) {
                                    foreach (getSupportedLanguages() as $language) {
                                        $checked = $language === $defaultLanguage ? true : false ;
                                        fillLanguageRow($language,$checked);
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="md-new-language">
                            <p>Select the languages you wish to make your website available in <span class="inline-icon">⤵</span></p>
                            <select id="select_language" tabindex="-1" aria-hidden="true">
                                <option value="">Choose...</option>
                                <?php echo createLanguageSelect(); ?>
                            </select>
                            <button type="button" class="md-add-language button-secondary">Add</button>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Supported post types</th>
                <td class="md-post-type__area">
                    <div class="md-language__area">
                        <?php echo showAvailablePostTypes(); ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" class="button-primary" value="Save Changes">
    </p>
</form>