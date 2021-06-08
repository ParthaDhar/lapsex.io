<?php

namespace App\Http\Controllers;

use App\Models\Core\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeController extends Controller
{

    public function __invoke(): View
    {
        $homePage = Page::whereNotNull('published_at')->where('is_home_page', ACTIVE)->first();

        if($homePage){
            try {
                $fallbackLang = Storage::disk('public')->get('page_language/'.$homePage->id.'/'. config('app.fallback_locale') .'.json');
                $data['fallbackLang'] = json_decode($fallbackLang, true);
            }
            catch (\Exception $e){
                abort('404','You do not have default fallback language data');
            }
            try {
                $currentLang = Storage::disk('public')->get('page_language/'.$homePage->id.'/'. App::getLocale() .'.json');
                $data['currentLang'] = json_decode($currentLang, true);
            }
            catch (\Exception $e){
                $data['currentLang'] = [];
            }
            $data['visualPage'] = $homePage;
            return view('core.pages.show', $data);
        }

        return view('regular_pages.home');
    }
}
