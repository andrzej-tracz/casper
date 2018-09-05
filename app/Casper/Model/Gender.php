<?php

namespace App\Casper\Model;

final class Gender
{
    const MALE = 'male';
    const FEMALE = 'female';

    /**
     * Returns list of available values for male
     *
     * @return array
     */
    public static function getValidValues()
    {
        return [
            self::MALE,
            self::FEMALE,
        ];
    }
}
