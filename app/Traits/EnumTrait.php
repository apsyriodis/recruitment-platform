<?php

namespace App\Traits;

trait EnumTrait
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        $categories = [];

        foreach (self::cases() as $case) {
            $categories[] = [
                'id' => $case->value,
                'title' => $case->label(),
            ];
        }

        return $categories;
    }

    /**
     * Ελληνική ετικέτα από την τιμή που είναι αποθηκευμένη στη βάση.
     * Αν η τιμή δεν αντιστοιχεί σε case, επιστρέφεται αυτούσια.
     */
    public static function labelFor(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return self::tryFrom($value)?->label() ?? $value;
    }
}
