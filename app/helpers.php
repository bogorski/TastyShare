<?php

if (!function_exists('polskaOdmiana')) {
    function polskaOdmiana(string $slowo, int $liczba): string
    {
        if ($slowo === 'opinia') {
            if ($liczba === 1) return 'opinia';
            if ($liczba % 10 >= 2 && $liczba % 10 <= 4 && ($liczba % 100 < 10 || $liczba % 100 >= 20)) {
                return 'opinie';
            }
            return 'opinii';
        }

        return $slowo;
    }
}
