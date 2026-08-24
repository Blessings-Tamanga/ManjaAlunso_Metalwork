<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\Gallery;
use App\Models\SiteSetting;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function home()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        $projects = Project::where('is_featured', true)->take(2)->get();
        $testimonials = Testimonial::where('is_approved', true)->take(3)->get();
        $galleries = Gallery::orderBy('sort_order')->get();

        $settings = SiteSetting::all()->keyBy('key');
        $heroBackground = $settings['hero_background']->value ?? null;
        $aboutImage = $settings['about_image']->value ?? null;
        $whyChooseUsMedia = $settings['why_choose_us_media']->value ?? null;

        return view('home', compact('services', 'projects', 'testimonials', 'galleries', 'heroBackground', 'aboutImage', 'whyChooseUsMedia'));
    }

    public function about()
    {
        $settings = SiteSetting::all()->keyBy('key');
        $aboutImage = $settings['about_image']->value ?? null;

        return view('frontend.about', compact('aboutImage'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        return view('frontend.services', compact('services'));
    }

    public function projects()
    {
        $projects = Project::orderBy('created_at', 'desc')->paginate(9);
        return view('frontend.projects', compact('projects'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'service_interest' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Your message has been sent. We\'ll get back to you soon.');
    }
}
