<?php

/**
 * Methos to return next letter nivel
 * @param Int $poscioNivel
 * @return String letter
 */
function lettersNivel($poscionLetter)
{
    $number = $poscionLetter / 26;
    $totalVeces = intval($number);
    $number = $number > 1 && is_float($number) ? 1 : 0;
    $number = $number + $totalVeces;
    if ($number > 1) {
        $poscionLetter -= 26 * ($number - 1);
        $poscionLetter += 64;
        $letter = chr($poscionLetter);
        $letter = chr(63 + $number) . $letter;
    } else {
        $asciiLetter = 64 + $poscionLetter;
        $letter = chr($asciiLetter);
    }
    return $letter;
}
