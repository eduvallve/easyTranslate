<?php
    function handleSingleQuotesCases($find, $content) {
        $removals = [];
        // Sometimes there will be cases that text include single quotes (').
        // This can be problemattic as str_replace() can't find them in the content to be replaced
        // In those cases, we divide the string using ' with explode().
        // The longest division is the most reliable piece of text thst we have to do the replacement there.
        foreach ($find as $i => $f) {
            if (str_contains($f,"'")) {
                $subtext = explode("'", $f);
                $largest = array_map(function($cell) {
                    return strlen($cell);
                }, $subtext);
                $longestIndex = implode(array_values(array_keys($largest, max($largest))));

                foreach ($subtext as $j => $sub) {
                    if (intVal($j) !== intVal($longestIndex)) {
                        array_push($removals, $sub);
                    }
                }
                $find[$i] = $subtext[$longestIndex];
            }
        }

        // After that, we remove from the string all subdivisions, except for the longest one.
        foreach ($removals as $removal) {
            $content = str_replace($removal, "", $content);
        }
        return [$find, $content];
        // WE CAN PREVENT those cases by using apostrophe (’) instead of single quotes (') in texts.
    }

    function sortTranslationLinesByLength($find, $replace) {
        $find_new = $find;
        usort($find_new, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        // print_r($find_new); echo '<hr>';
        $replace_new = [];
        foreach ($find_new as $fn) {
            foreach ($find as $indexFind => $f) {
                if (trim($fn) === trim($f)) {
                    array_push($replace_new, $replace[$indexFind]);
                }
            }
        }
        // print_r($replace_new); echo '<hr>';
        return [$find_new, $replace_new];
    }

    /** Add replace function here */
    function replace_page_content($content) {
        if (!is_admin()) {
            $defaultLanguage = getDefaultLanguage();
            $currentLanguage = getCurrentLanguage();
            if ($defaultLanguage !== $currentLanguage) {
                $post_id = get_the_ID();
                $savedPostTexts = getSavedPostTexts($post_id, true);

                // We get the saved post texts in the DB in default language.
                $find = array_column($savedPostTexts, convertLanguageCodesforDB($defaultLanguage)); // Original texts in default language

                // First processing phase --> what if there are single quotes?
                $arrays = handleSingleQuotesCases($find, $content);
                $find = $arrays[0];
                $content = $arrays[1];

                // We get the saved post texts in current language
                $replace = array_column($savedPostTexts, convertLanguageCodesforDB($currentLanguage)); // Text replacements in current language

                // Second processing phase --> Sort translatyion lines by length, starting from the longest one, to avoid translation overlapping.
                $contentArrays = sortTranslationLinesByLength($find, $replace);
                $find_new = $contentArrays[0];
                $replace_new = $contentArrays[1];

                $content = str_replace($find_new, $replace_new, $content); // $finds strings in the $content to $replace
                return $content; // The filtered content
            }
        }
        return $content; // The default content
    }
?>