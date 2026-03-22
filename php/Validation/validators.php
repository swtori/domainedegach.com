<?php

/**
 * Validation côté serveur (formats, longueurs, cohérence des dates).
 */

if (!function_exists('mb_strlen')) {
    /**
     * @param string $s
     */
    function mb_strlen($s)
    {
        return strlen($s);
    }
}

/**
 * @return bool
 */
function validation_isNonEmptyString($s, $maxLen)
{
    if (!is_string($s)) {
        return false;
    }
    $t = trim($s);
    return $t !== '' && mb_strlen($t) <= $maxLen;
}

/**
 * @return bool
 */
function validation_email($email)
{
    if (!is_string($email)) {
        return false;
    }
    $t = trim($email);
    if (mb_strlen($t) > 70) {
        return false;
    }
    return filter_var($t, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Téléphone : chiffres, espaces, +, ., - — longueur raisonnable.
 * @return bool
 */
function validation_tel($tel)
{
    if (!is_string($tel)) {
        return false;
    }
    $t = trim($tel);
    if ($t === '' || mb_strlen($t) > 20) {
        return false;
    }
    return (bool) preg_match('/^[0-9+\s.\-()]{8,20}$/u', $t);
}

/**
 * Date au format Y-m-d.
 * @return bool
 */
function validation_dateYmd($s)
{
    if (!is_string($s)) {
        return false;
    }
    $d = DateTime::createFromFormat('Y-m-d', trim($s));
    return $d !== false && $d->format('Y-m-d') === trim($s);
}

/**
 * Sortie strictement après entrée (séjour d'au moins une nuit).
 * @return bool
 */
function validation_reservationDateRange($dateIn, $dateOut)
{
    if (!validation_dateYmd($dateIn) || !validation_dateYmd($dateOut)) {
        return false;
    }
    $in = new DateTime(trim($dateIn));
    $out = new DateTime(trim($dateOut));
    return $out > $in;
}

/**
 * Valide les champs client pour création / mise à jour.
 * @return array<string> liste vide si OK, sinon codes d'erreur ('username','lastname','tel','email')
 */
function validation_clientPayload($username, $lastname, $tel, $email)
{
    $err = array();
    if (!validation_isNonEmptyString($username, 255)) {
        $err[] = 'username';
    }
    if (!validation_isNonEmptyString($lastname, 255)) {
        $err[] = 'lastname';
    }
    if (!validation_tel($tel)) {
        $err[] = 'tel';
    }
    if (!validation_email($email)) {
        $err[] = 'email';
    }
    return $err;
}

/**
 * id entier strictement positif.
 * @return bool
 */
function validation_positiveInt($v)
{
    if (is_int($v)) {
        return $v > 0;
    }
    if (is_string($v) && ctype_digit($v)) {
        return (int) $v > 0;
    }
    return false;
}
