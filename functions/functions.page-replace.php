<?php
    /** Add replace function here */
    function replace_page_content($content) {
        if (!is_admin()) {
            $defaultLanguage = getDefaultLanguage();
            $currentLanguage = getCurrentLanguage();
            if ($defaultLanguage !== $currentLanguage) {
                $post_id = get_the_ID();
                $savedPostTexts = getSavedPostTexts($post_id, true);

                $removals = [];
                $find = array_column($savedPostTexts, convertLanguageCodesforDB($defaultLanguage)); // Original texts in default language

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

                // WE CAN PREVENT those cases by using apostrophe (’) instead of single quotes (') in texts.

                $replace = array_column($savedPostTexts, convertLanguageCodesforDB($currentLanguage)); // Text replacements in current language
                $content = str_replace($find, $replace, $content); // $finds strings in the $content to $replace
                return $content; // The filtered content
            }
        }
        return $content; // The default content
    }
?>