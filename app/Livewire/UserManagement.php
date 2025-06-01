<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

class UserManagement extends Component
{
    public $users = [];
    public $loading = true;

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->loading = true;
        
        try {
            $userController = new UserController();
            $response = $userController->index();
            $this->users = $response->toArray(request())['data'] ?? $response->toArray(request());
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Error loading users: ' . $e->getMessage());
        }

        $this->loading = false;
    }

    public function deleteUser($userId)
    {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }

        try {
            $userController = new UserController();
            $request = new Request();
            $response = $userController->destroy($userId);
            
            $this->dispatch('alert', type: 'success', message: 'User deleted successfully');
            $this->loadUsers();
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user-management');
    }
}