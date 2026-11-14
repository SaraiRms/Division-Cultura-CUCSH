<?php

namespace App\Http\Controllers;

use App\Models\Podcast;

class PodcastController extends Controller
{
    public function index()
    {
        $podcasts = Podcast::published()->latest('published_at')->paginate(9);

        return view('podcast.index', compact('podcasts'));
    }
}
