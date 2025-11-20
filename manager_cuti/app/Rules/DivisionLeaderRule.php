<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class DivisionLeaderRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== null) {
            $user = User::find($value);
            if (!$user || $user->role !== 'division_leader') {
                $fail('The selected :attribute is not a division leader.');
            }
        }
    }
}
