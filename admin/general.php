<?php
/**
 * General tab here
 */

require_once "general.functions.php";

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

?>

<form id="md-general" method="post" action="<?php echo plugin_dir_url( __FILE__ ); ?>general.update.php" data-plugin-url="<?php echo plugin_dir_url( __FILE__ ); ?>">
    <table class="form-table">
        <tbody>
            <tr>
                <th>Supported languages</th>
                <td>
                    <table class="md-language__table">
                        <thead>
                            <th>Default</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            <?php
                            fillLanguageRow(getDefaultLanguage(),true);
                            ?>
                        </tbody>
                    </table>
                    <div class="md-new-language">
                        <select class="" tabindex="-1" aria-hidden="true">
                            <option value="">Choose...</option>
                            <?php echo createLanguageSelect(); ?>
                        </select>
                        <button type="button" class="md-add-language button-secondary">Add</button>
                        <p>Select the languages you wish to make your website available in.</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" class="button-primary" value="Save Changes">
    </p>
</form>