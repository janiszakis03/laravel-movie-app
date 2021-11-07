<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Livewire\Livewire;

// Visus testus var palaist/pārbaudīt ar komandu: php artisan test

class ViewMoviesTest extends TestCase
{
   /** @test */
    public function the_main_page_shows_correct_info()
    {
        Http::fake([
            'https://api.themoviedb.org/3/movie/popular' => $this->fakePopularMovies(),
            'https://api.themoviedb.org/3/movie/now_playing' => $this->fakeNowPlayingMovies(),
            'https://api.themoviedb.org/3/movie/list' => $this->fakeGenres(),
        ]);

        $response = $this->get(route('movies.index'));

        $response->assertSuccessful();
        $response->assertSee('Popular Movies');
        $response->assertSee('Fake Movie');
        $response->assertSee('Adventure, Drama, Mystery, Science Fiction, Thriller');
        $response->assertSee('Now Playing');
        $response->assertSee('Now Playing Fake Movie');
    }

    /** @test */
    public function the_movie_page_shows_correct_info()
    {
        Http::fake([
            'https://api.themoviedb.org/3/movie/*' => $this->fakeSingleMovie(),
        ]);

        $response = $this->get(route('movies.show', '12345'));
        $response->assertSee('Fake Single Movie');
    }

    /** @test */
    public function the_search_dropdown_works_correctly()
    {
        Http::fake([
            'https://api.themoviedb.org/3/search/movie?query=jumanji' => $this->fakeSearchMovies(),
        ]);

        Livewire::test('search-dropdown')
            ->assertDontSee('jumanji')
            ->set('search', 'jumanji')
            ->assertSee('Jumanji');
    }

    private function fakeSearchMovies()
    {
        return Http::response([
            'results' => [
                [
                    "adult" => false,
                    "backdrop_path" => "/zTxHf9iIOCqRbxvl8W5QYKrsMLq.jpg",
                    "genre_ids" => [
                        12,
                        35,
                        14
                    ],
                    "id" => 512200,
                    "original_language" => "en",
                    "original_title" => "Jumanji: The Next Level",
                    "overview" => "As the gang return to Jumanji to rescue one of their own, they discover that nothing is as they expect. The players will have to brave parts unknown and unexplored in order to escape the world’s most dangerous game.",
                    "popularity" => 89.77,
                    "poster_path" => "/jyw8VKYEiM1UDzPB7NsisUgBeJ8.jpg",
                    "release_date" => "2019-12-04",
                    "title" => "Jumanji: The Next Level",
                    "video" => false,
                    "vote_average" => 7,
                    "vote_count" => 6302
                ]
            ]
        ], 200);
    }

    private function fakePopularMovies()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 5783.658,
                    "vote_count" => 1283,
                    "video" => false,
                    "poster_path" => "/rjkmN1dniUHVYAtwuV3Tji7FsDO.jpg",
                    "id" => 580489,
                    "adult" => false,
                    "backdrop_path" => "/lNyLSOKMMeUPr1RsL4KcRuIXwHt.jpg",
                    "original_language" => "en",
                    "original_title" => "Venom: Let There Be Carnage",
                    "genre_ids" => [
                        878,
                        28,
                    ],
                    "title" => "Fake Movie",
                    "vote_average" => 6.9,
                    "overview" => "After finding a host body in investigative reporter Eddie Brock, the alien symbiote must face a new enemy, Carnage, the alter ego of serial killer Cletus Kasady.",
                    "release_date" => "2021-09-30",
                ]
            ]
        ], 200);
    }

    private function fakeNowPlayingMovies()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 5783.658,
                    "vote_count" => 1283,
                    "video" => false,
                    "poster_path" => "/rjkmN1dniUHVYAtwuV3Tji7FsDO.jpg",
                    "id" => 580489,
                    "adult" => false,
                    "backdrop_path" => "/lNyLSOKMMeUPr1RsL4KcRuIXwHt.jpg",
                    "original_language" => "en",
                    "original_title" => "Now Playing Fake Movie",
                    "genre_ids" => [
                        12,
                        18,
                        9648,
                        878,
                        53,
                    ],
                    "title" => "Now Playing Fake Movie",
                    "vote_average" => 6,
                    "overview" => "After finding a host body in investigative reporter Eddie Brock, the alien symbiote must face a new enemy, Carnage, the alter ego of serial killer Cletus Kasady.",
                    "release_date" => "2021-09-30",
                ]
            ]
        ], 200);
    }

    private function fakeGenres()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 5783.658,
                    "vote_count" => 1283,
                    "video" => false,
                    "poster_path" => "/rjkmN1dniUHVYAtwuV3Tji7FsDO.jpg",
                    "id" => 580489,
                    "adult" => false,
                    "backdrop_path" => "/lNyLSOKMMeUPr1RsL4KcRuIXwHt.jpg",
                    "original_language" => "en",
                    "original_title" => "Venom: Let There Be Carnage",
                    "genre_ids" => [
                        12,
                        18,
                        9648,
                        878,
                        53,
                    ],
                    "title" => "Fake Now Playing Movie",
                    "vote_average" => 6,
                    "overview" => "After finding a host body in investigative reporter Eddie Brock, the alien symbiote must face a new enemy, Carnage, the alter ego of serial killer Cletus Kasady.",
                    "release_date" => "2021-09-30",
                ]
            ]
        ], 200);
    }
    
    private function fakeSingleMovie()
    {
        return Http::response([
            'results' => [
                [
                    "popularity" => 5783.658,
                    "vote_count" => 1283,
                    "video" => false,
                    "poster_path" => "/rjkmN1dniUHVYAtwuV3Tji7FsDO.jpg",
                    "id" => 12345,
                    "adult" => false,
                    "backdrop_path" => "/lNyLSOKMMeUPr1RsL4KcRuIXwHt.jpg",
                    "original_language" => "en",
                    "original_title" => "Fake Single Movie",
                    "genre_ids" => [
                        12,
                        18,
                        9648,
                        878,
                        53,
                    ],
                    "title" => "Fake Single Movie",
                    "vote_average" => 6,
                    "overview" => "After finding a host body in investigative reporter Eddie Brock, the alien symbiote must face a new enemy, Carnage, the alter ego of serial killer Cletus Kasady.",
                    "credits" => [
                        "cast" => [
                            [
                                "name" => "Tom Hardy",
                                "original_name" => "Tom Hardy",
                                "character" => "Eddie Brock / Venom",
                            ]
                        ],
                        "crew" => [
                            [
                                "name" => "Ryan",
                                "original_name" => "Ryan",
                                "job" => "Crew Member",
                            ]
                        ]
                    ],
                    "videos" => [
                        "results" => [
                            [
                                "iso_639_1" => "en",
                                "iso_3166_1" => "US",
                                "name" => "Wishes",
                                "key" => "A47y6VJNols",
                                "site" => "YouTube",
                            ]
                        ]
                    ],
                    "images" => [
                        "backdrops" => [
                            [
                                "aspect_ratio" => 1.778,
                                "height" => 1080,
                                "iso_639_1" => null,
                                "file_path" => "/lNyLSOKMMeUPr1RsL4KcRuIXwHt.jpg",
                                "vote_average" => 5.342,
                                "vote_count" => 15,
                                "width" => 1920,
                            ]
                        ]
                    ]
                ]
            ]
        ], 200);
    }
}