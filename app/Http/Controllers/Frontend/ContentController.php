<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;

class ContentController extends Controller
{
    public function show(string $seoUrl)
    {
        $content = ContentItem::with(['folder', 'type.colorScheme'])
            ->where('seo_url', $seoUrl)
            ->firstOrFail();

        return view('frontend.content', compact('content'));
    }
}
