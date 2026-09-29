<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Tenant\GlobalSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected GlobalSearchService $search)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = $request->query('q', '');

        return response()->json($this->search->search($query));
    }
}