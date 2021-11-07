<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;

/*View modeļus lieto, lai mainīgo/datu apstrāde un sagatavošana lietošanai nav jāveic kontroliera failā un jāpiesārņo tas, un, lai nebūtu jāveic loģika un datu apstrāde view failos, jo view failos
šādas darbības labāk nevajag veikt, jo tie ir domāti, lai vienkārši parādītu jau apstrādātus mainīgos/datus vai veiktu minimālas loģikas darbības, piemēram, ar foreach iziet cauri padotajai kolekcijai/masīvam.*/

class MoviesViewModel extends ViewModel
{
    public $popularMovies; // Nodefinējam īpašības šajā klasē, lai tām varam piešķirt kontroliera padoto mainīgo vērtības
    public $nowPlayingMovies;
    public $genres;

    public function __construct($popularMovies, $nowPlayingMovies, $genres) // Nodefinējam parametrus, kurus pados kontrolieris
    {
        $this->popularMovies = $popularMovies;
        $this->nowPlayingMovies = $nowPlayingMovies;
        $this->genres = $genres;
    }

    public function popularMovies() // Katram padotajam mainīgajam vajag savu metodi, kas to apstrādās un atgriezīs. Par atgrieztā mainīgā nosaukumu kļūs metodes nosaukums
    {
        return $this->formatMovies($this->popularMovies); // Šeit vienkārši atgriež funkciju, norādot masīvu, kurā pārveidos tā vērtības
    }

    public function nowPlayingMovies()
    {
        return $this->formatMovies($this->nowPlayingMovies);
    }

    public function genres()
    {
        return collect($this->genres)->mapWithKeys(function($genres){ //Izveido kolekciju, kurā no $genresArray paņem vērtības 'id' un 'name' un no tām ar mapWithKeys() izveido masīvu kurā key ir 'id' un value ir 'name'
            return [$genres['id'] => $genres['name']];
        });
    }

    private function formatMovies($movies) // Šī privātā metode ir domāta, lai divreiz nav jāatkārto masīva vērtību pārveidošana, jo divos masīvos ir jāparveido tās pašas vērtības
    {
        return collect($movies)->map(function($movie) { // Ar map() var iziet cauri visai kolekcijai un izmainīt elementu vērtības, tādējādi atgriežot kolekciju ar savādākām vērtībām
            
            $genresFormatted = collect($movie['genre_ids'])->mapWithKeys(function($value) { // Izveido kolekciju, kurā key ir žanra ID un vērtība ir žanra nosaukums un, kurā, ja ir vairāki žanri, tos, izmantojot implode(), atdala ar komatu nevis priekš katra viedo jaunu elementu
                return [$value => $this->genres()->get($value)];
            })->implode(', ');

            return collect($movie)->merge([ // Ar merge() var sapludināt oriģinālo kolekciju ar pašu izveidoto masīvu vai kolekciju
                'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path'], // Šeit izdara mainīgo apstrādi un sagatavošanu, lai view failos tos var viegli izmantot, vienkārši norādot, piemēram, {{ $movie['poster_path'] }} nevis {{ 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path'] }}, jo view failos nav ieteicams veikt loģikas un mainīgo apstrādes/sagatavošanas uzdevumus
                'vote_average' => $movie['vote_average'] * 10 .'%',
                'release_date' => Carbon::parse($movie['release_date'])->format('M d, Y'),
                'genres' => $genresFormatted, // Izveidojam jaunu key, kuram kā vērtību padod $genresFormatted, kas ir kolekcija ar žanru ID un nosaukumiem, kuru viegli varēs izvadīt view failā bez foreach cikli, vienkārši norādot {{ $movie['genres'] }}
            ])->only([ // Ar only() norāda, kurus laukus/masīva elementus iekļaut kolekcijā, lai nebūtu jāņem elementi, kurus mēs neizmantojam
                'poster_path', 'id', 'genre_id', 'title', 'vote_average', 'overview', 'release_date', 'genres'
            ]);
        }); // Ja vajag, ar ->dump() var izvadīt/pārbaudīt
    }
}
