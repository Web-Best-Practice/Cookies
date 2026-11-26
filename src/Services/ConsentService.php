<?php

namespace WebBestPractice\Cookies\Services;

use Illuminate\Http\JsonResponse;
use WebBestPractice\Cookies\Support\Consent;

class ConsentService
{
    public function accept(): JsonResponse
    {
        return $this->respondWithConsentCookie(
            config('cookies.cookies.all'),
            Consent::grantedState()
        );
    }

    public function reject(): JsonResponse
    {
        return $this->respondWithConsentCookie(
            config('cookies.cookies.essential'),
            Consent::deniedState()
        );
    }

    /**
     * @param  array<string, string>  $consentUpdate
     */
    protected function respondWithConsentCookie(string $name, array $consentUpdate): JsonResponse
    {
        $minutes = (int) config('cookies.cookies.minutes', 60 * 24 * 365);

        $cookie = cookie(
            $name,
            '1',
            $minutes,
            '/',
            null,
            null,
            false, // readable by JS if needed
            false,
            'Lax'
        );

        return response()
            ->json([
                'consent' => $consentUpdate,
            ])
            ->withCookie($cookie);
    }
}
