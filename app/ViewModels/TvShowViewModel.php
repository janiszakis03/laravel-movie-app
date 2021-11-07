<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;

class TvShowViewModel extends ViewModel
{
    public $tvshow;

    public function __construct($tvshow)
    {
        $this->tvshow = $tvshow;
    }

    public function tvshow()
    {
        return collect($this->tvshow)->merge([
            'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$this->tvshow['poster_path'],
            'vote_average' => $this->tvshow['vote_average'] * 10 .'%',
            'first_air_date' => Carbon::parse($this->tvshow['first_air_date'])->format('M d, Y'),
            'genres' => collect($this->tvshow['genres'])->pluck('name')->flatten()->implode(', '), // Izveido kolekciju, kurā ar pluck() paņem un kolekciju izveido tikai ar 'name' vērtībām, un ar flatten() pārveido no divdimensiju kolekcijas/masīva uz viendimensiju
            'crew' => collect($this->tvshow['credits']['crew'])->take(2), // Izveidojam jaunu key, kura vērtība ir kolekcija, kurā, izmantojot take(), paņemam tikai pirmos divus elementus
            'cast' => collect($this->tvshow['credits']['cast'])->take(5),
            'images' => collect($this->tvshow['images']['backdrops'])->take(9),
        ])->only([
            'poster_path', 'id', 'genres', 'name', 'vote_average', 'overview', 'first_air_date', 'credits', 'videos', 'images', 'crew', 'cast', 'images', 'created_by'
        ]);
    }
}
