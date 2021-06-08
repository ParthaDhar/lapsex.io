<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Core\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function __invoke(Page $page)
    {
        abort_if((empty($page->published_at) || $page->is_home_page), 404);
        try {
            $fallbackLang = Storage::disk('public')->get('page_language/'.$page->id.'/'. config('app.fallback_locale') .'.json');
            $data['fallbackLang'] = json_decode($fallbackLang, true);
        }
        catch (\Exception $e){
            abort('404','You do not have default fallback language data');
        }
        try {
            $currentLang = Storage::disk('public')->get('page_language/'.$page->id.'/'. App::getLocale() .'.json');
            $data['currentLang'] = json_decode($currentLang, true);
        }
        catch (\Exception $e){
            $data['currentLang'] = [];
        }
        $data['visualPage'] = $page;
        return view('core.pages.show', $data);
    }
}
