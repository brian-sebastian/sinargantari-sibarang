<?php

function validateOnlyLettersAndSpaces($string)
{
    if (preg_match('/^[a-zA-Z\s]+$/', $string)) {
        // echo "String contains only letters and spaces.";
        return true;
    } else {
        // echo "String contains invalid characters.";
        return false;
    }
}

function validateUpperLowerCase($string)
{
    if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $string)) {
        // echo "String meets the criteria.";
        return true;
    } else {
        // echo "String does not meet the criteria.";
        return false;
    }
}

function validateAlphaNumeric($string)
{
    if (preg_match('/^[a-zA-Z0-9]+$/', $string)) {
        return true;
        // echo "String contains only alphanumeric characters.";
    } else {
        // echo "String contains invalid characters.";
        return false;
    }
}
