<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return PageResource::collection(Page::all());
    }

    public function show(Request $request, string $language, Page $page)
    {
        return PageResource::make($page);
    }
}
