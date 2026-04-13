<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchaService
{
    /**
     * Generate a captcha code and return it with a unique key.
     */
    public function generate(): array
    {
        $code = str_pad((string) random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
        $key = 'captcha_' . Str::random(32);

        Cache::put($key, $code, now()->addMinutes(5));

        return [
            'captcha_key' => $key,
            'captcha_code' => $code,
        ];
    }

    /**
     * Verify a captcha code against its key.
     */
    public function verify(string $key, string $inputCode): bool
    {
        $storedCode = Cache::get($key);

        if ($storedCode === null) {
            return false;
        }

        Cache::forget($key);

        return $storedCode === $inputCode;
    }
}
