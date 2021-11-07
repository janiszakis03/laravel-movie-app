<div class="relative mt-3 md:mt-0" x-data="{ isOpen: true }" @click.away="isOpen = false"> <!-- Alpine.js - x-data direktīvu liek pie parent elementa, ar x-data izveido jaunu component un norāda tās datus priekš HTML bloka/elementa, ar @click.away pārmaina isOpen = false, kas paslēpj div, kurā norādīts x-show="isOpen"
    @click.away liek pie visa div, lai kad ieklikšķina searchbox inputā, div ar sarakstu nepazustu, bet ja klikšķina ārpus šī div, tad aizverās -->
    <input 
        wire:model.debounce.500ms="search" 
        type="text" 
        class="bg-gray-800 text-sm rounded-full w-64 px-4 pl-8 py-1" 
        placeholder="Search (Press '/' to focus)"
        x-ref="search"
        @keydown.window="
            if(event.keyCode == 191) {
                event.preventDefault()
                $refs.search.focus()
            }
        "
        @focus="isOpen = true"
        @keydown="isOpen = true"
        @keydown.escape.window="isOpen = false"
        @keydown.shift.tab="isOpen = false"
    > <!-- focus pārmaina isOpen uz true/parāda elementu, ja ieklikšķina ieksā input laukā 
    keydown.escape.window pārmaina isOpen = false, kad nospiež 'esc' un tādējādi paslēpj šo div, x-show="isOpen" un parāda tikai ja ir true 
    Nav atšķirība kur, iekšā div liek keydown.escape.window, jo tas darbojas uz visu window kā tas ir norādīts ar '.window' 
    Ar keydown var arī uztvert pogu kombinācijas, piem., keydown.shift.tab
    keydown attiecas uz visām pogām, ja nenorāda konkrētas 
    x-ref izveido atsauci, kurai var piekļūt ar $refs.atsauce, lai, piemēram, šajā gadīijumā, kad nospiež '/' pogu, input lauks fokusējas 
    event.preventDefault() izvairās no noklusējuma notikuma, šajā gadījumā, input laukā neievadīs '/', kad to nospiedīs, lai fokusētu -->
    <div class="absolute top-0">
        <svg class="fill-current w-4 text-gray-500 mt-0.5 ml-2" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
        width="24" height="24"
        viewBox="0 0 30 30"
        style=" fill:#eee;"><path d="M 13 3 C 7.4889971 3 3 7.4889971 3 13 C 3 18.511003 7.4889971 23 13 23 C 15.396508 23 17.597385 22.148986 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148986 17.597385 23 15.396508 23 13 C 23 7.4889971 18.511003 3 13 3 z M 13 5 C 17.430123 5 21 8.5698774 21 13 C 21 17.430123 17.430123 21 13 21 C 8.5698774 21 5 17.430123 5 13 C 5 8.5698774 8.5698774 5 13 5 z"></path></svg>
    </div>

    <div wire:loading class="spinner top-0 right-0 mr-4 mt-3"></div>

    @if(strlen($search) >= 2)
        <div 
            class="z-50 absolute bg-gray-800 text-sm rounded w-64 mt-4" 
            x-show="isOpen"
            x-transition.duration.500ms.opacity
        > <!-- x-show togglo elementa redzamību, x-transition.duration.500ms.opacity pievieno 500ms ilgu animāciju - 'fade-in, fade-out' -->
            @if($searchResults->count() > 0)
                <ul>
                    @foreach($searchResults as $result)
                        <li class="border-b border-gray-700">
                            <a 
                                href="{{ route('movies.show', $result['id']) }}" 
                                class="block hover:bg-gray-700 px-3 py-3 transition flex items-center"
                                @if($loop->last) @keydown.tab="isOpen = false" @endif
                            ><!-- Pēdējam search rezultātu elementam, kad ar tab ir uziets uz tā un nospiež tab, search rezultātu div aizverās -->
                            @if($result['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w92/{{ $result['poster_path'] }}" alt="poster" class="w-8">
                            @else
                                <img src="https://via.placeholder.com/50x75" alt="poster" class="w-8">
                            @endif
                            <span class="ml-4">{{ $result['title'] }}</span>
                        </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-3 py-3">No results for "{{ $search }}"</div>
            @endif
        </div>
    @endif
</div>
