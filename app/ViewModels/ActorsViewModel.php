<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class ActorsViewModel extends ViewModel
{
    public $popularActors;
    public $page;

    public function __construct($popularActors, $page)
    {
        $this->popularActors = $popularActors;
        $this->page = $page;
    }

    public function popularActors()
    {
        return collect($this->popularActors)->map(function($actor) {
            return collect($actor)->merge([
                'profile_path' => $actor['profile_path']
                ? 'https://image.tmdb.org/t/p/w235_and_h235_face/'.$actor['profile_path']
                : 'https://ui-avatars.com/api/?size=235&name='.$actor['name'],
                'known_for' => collect($actor['known_for'])->where('media_type', 'movie')->pluck('title')->union(
                    collect($actor['known_for'])->where('media_type', 'tv')->pluck('name'),
                )->implode(', '), // Aizstās esošo 'known_for' elementu ar paša izveidoto kolekciju. Šajā gadījumā, atkarībā no tā, vai media_type ir movie vai tv, paņems tikai title vai name. Šādas darbības veic ar union() kas atkarībā no tā, vai iekšā ievadītais eksistē paņem vai nu oriģinālās kolekcijas vērtības (tas, kas norādīts pirms union()) vai iekšā union() paņēmtās vērtības.
            ])->only([
                'name', 'id', 'profile_path', 'known_for'
            ]);
        })->dump();

        return collect($this->popularActors)->dump();
    }

    public function previous() // Atgriezīs mainīgo metodes nosaukmā $previous, kuram varēs piekļūt view failā, kad kontrolieris to nosūtīs tam.
    {
        return $this->page > 1 ? $this->page - 1 : null; // Pārbauda vai lietotājs ir tālāk par pirmo lapu. Ja ir, tad $previous vērtība būs iepriekšējā lapa. Ja nav, tad vērtība būs null, kuru pārbaudot @if būs false
    }

    public function next() // Atgriezīs mainīgo metodes nosaukmā $next, kuram varēs piekļūt view failā, kad kontrolieris to nosūtīs tam.
    {
        return $this->page < 500 ? $this->page + 1 : null;
    }
}
