<?php

namespace App\Enums\Traits;

trait Helper
{
    public static function getValues(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public static function getNames(): array
    {
        return array_map(fn ($case) => $case->name, self::cases());
    }

    public static function fromValue(string $value)
    {
        foreach (self::cases() as $case) {
            if ($case->value == $value) {
                return $case->name;
            }
        }
        return false;
    }

    public static function fromName($name)
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) return $case->value;
        }

        return false;
    }

    public static function toOptions()
    {
        return \Arr::pluck(self::cases(), 'name', 'name');
    }
}
