<?php

namespace WebBestPractice\Cookies\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use WebBestPractice\Cookies\Services\ConsentService;

class ConsentController extends Controller
{
    public function accept(ConsentService $consent): JsonResponse
    {
        return $consent->accept();
    }

    public function reject(ConsentService $consent): JsonResponse
    {
        return $consent->reject();
    }
}
