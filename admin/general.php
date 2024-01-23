<?php
/**
 * General tab here
 */

function getAllLanguages() {
    $file = plugin_dir_path( __DIR__ ).'/languages.json';
    $json = file_get_contents($file);
    return json_decode($json,true);
}

function getDefaultLanguage() {
    $query_getDefaultLanguage = "SELECT meta_value FROM ".$GLOBALS['wp_my_dictionary_meta']." WHERE meta_key = 'defaultLanguage'";
    $getDefaultLanguage = $GLOBALS['wpdb']->get_results($query_getDefaultLanguage);
    if (count($getDefaultLanguage) === 0) {
        saveDefaultLanguage();
        return get_locale();
    } else {
        return implode(array_column($getDefaultLanguage, 'meta_value'));
    }
}

function createLanguageSelect($defaultLanguage = false) {
    $listOutput = "";
    foreach( getAllLanguages() as $abbr => $language ) {
        $abbr = str_replace("-", "_", $abbr);
        $listOutput .= "<option title='{$abbr}' value='{$abbr}'";
        if ($defaultLanguage && $defaultLanguage === $abbr ) {
            $listOutput .= ' selected';
        }
        $listOutput .= ">".$language."</option>";
    }
    return $listOutput;
}

?>
<form method="post" action="<?php echo plugin_dir_url( __FILE__ ); ?>update-general.php">
    <div class="md-main-general__wrap">
        <table id="md-general" class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Default Language </th>
                    <td>
                        <select name="md_languages[default]" class="md-default-language" tabindex="-1" aria-hidden="true">
                            <?php echo createLanguageSelect(getDefaultLanguage()); ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"> All Languages </th>
                    <td>
                        <table class="md-all-languages-table">
                            <thead>
                                <tr>
                                    <th>Language</th>
                                    <th>Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="md_settings[translation-languages][]" class="md-select2 md-translation-language select2-hidden-accessible" disabled="" tabindex="-1" aria-hidden="true">
                                            <?php echo createLanguageSelect(getDefaultLanguage()); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" disabled value="<?php echo getDefaultLanguage(); ?>">
                                    </td>
                                    <td></td>
                                </tr>
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
    </div>
    <p class="submit">
        <input type="submit" class="button-primary" value="Save Changes">
    </p>
</form>