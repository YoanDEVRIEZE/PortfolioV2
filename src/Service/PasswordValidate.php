<?php

namespace App\Service;

class PasswordValidate {
    public static function check(string $password)
    {
        if ($password && !password_get_info($password)['algo']) {
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d$@#+-]{6,255}$/', $password)) {
                return false;
            }
        }

        return true;
    }
}
