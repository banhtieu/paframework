<?php


namespace Core\Validator;

/**
 * Created by PhpStorm.
 * User: banhtieu
 * Date: 3/25/2016
 * Time: 2:40 PM
 */
class Validator
{

    /**
     * @param $email string email to validate
     *
     * @throws \Exception exception if email is not valid
     */
    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email is not valid");
        }
    }

    /**
     * @param $value string value to validate
     * @param $length int length of the value
     * @param  $errorMessage string the error message to display
     *
     * @throws \Exception if validate fails
     */
    public function validateLength($value, $length, $errorMessage) {
        if (strlen($value) < $length) {
            throw new \Exception($errorMessage);
        }
    }

}