<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Change the application language
     */
    public function changeLanguage(Request $request, $locale)
    {
        // Validate locale
        $supportedLocales = ['vi', 'en', 'hi'];
        
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'vi'; // Default to Vietnamese
        }
        
        // Store locale in session
        Session::put('locale', $locale);
        
        // Set application locale
        App::setLocale($locale);
        
        // Redirect back to the previous page
        return redirect()->back()->with('success', 'Language changed successfully!');
    }
    
    /**
     * Get current locale
     */
    public function getCurrentLocale()
    {
        return Session::get('locale', 'vi');
    }
}
