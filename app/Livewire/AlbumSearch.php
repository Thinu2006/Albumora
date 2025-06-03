<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Album;
use App\Models\Genre;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class AlbumSearch extends Component
{
    use WithPagination;

    // Public properties bound to the search input, genre filter, and pagination
    public $search = '';
    public $genreFilter = '';
    public $perPage = 12;

    // Define how query parameters are managed in the URL
    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'genreFilter' => ['except' => '', 'as' => 'genre'],
        'page' => ['except' => 1],
    ];

    // Initialize the component
    public function mount()
    {
        Log::debug('Mounting component', [
            'search' => $this->search,
            'genreFilter' => $this->genreFilter
        ]);
    }

    // Reset pagination when the search input changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset pagination when the genre filter changes
    public function updatingGenreFilter()
    {
        $this->resetPage();
    }

    // Main method: builds the filtered query and returns the view
    public function render()
    {
        Log::debug('Rendering component', [
            'search' => $this->search,
            'genreFilter' => $this->genreFilter
        ]);

        // Build the query dynamically based on search and genre filter
        $query = Album::with(['genres', 'creator'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('artist', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->genreFilter, function ($query) {
                $query->whereHas('genres', function($q) {
                    $q->where('name', $this->genreFilter);
                });
            })
            ->latest();

        // Paginate the results
        $albums = $query->paginate($this->perPage);

        // Fetch all genres for the dropdown filter
        $genres = Genre::all();

        // Return the component view with data
        return view('livewire.album-search', [
            'albums' => $albums,
            'genres' => $genres,
        ]);
    }
}
