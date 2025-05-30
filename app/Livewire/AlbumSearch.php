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

    public $search = '';
    public $genreFilter = '';
    public $perPage = 12;
    
    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'genreFilter' => ['except' => '', 'as' => 'genre'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        Log::debug('Mounting component', [
            'search' => $this->search,
            'genreFilter' => $this->genreFilter
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGenreFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        Log::debug('Rendering component', [
            'search' => $this->search,
            'genreFilter' => $this->genreFilter
        ]);

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

        $albums = $query->paginate($this->perPage);
        $genres = Genre::all();

        return view('livewire.album-search', [
            'albums' => $albums,
            'genres' => $genres,
        ]);
    }

}