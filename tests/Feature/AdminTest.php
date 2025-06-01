<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Album;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $album;
    protected $order;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed genres
        $this->seed(\Database\Seeders\GenreSeeder::class);

        // Create admin user
        $this->admin = User::factory()->create([
            'user_type' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123')
        ]);

        // Create regular user
        $this->user = User::factory()->create([
            'user_type' => 'user'
        ]);

        // Create album
        $this->album = Album::factory()->create([
            'creator_by' => $this->admin->id
        ]);

        // Create order
        $this->order = Order::factory()->create([
            'customer_id' => $this->user->id,
            'status' => 'pending'
        ]);

        // Fake storage
        Storage::fake('public');
    }

    #[Test]
    public function admin_can_login()
    {
        // First visit the login page to establish session
        $this->get('/admin/login');

        // Then submit login form with CSRF token
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
            '_token' => session()->token()
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }
    
    #[Test]
    public function non_admin_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_view_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewHas(['albumCount', 'userCount', 'orderCount']);
    }

    #[Test]
    public function admin_can_view_albums_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/albums');
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_create_album()
    {
        // Skip if GD isn't available
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not available');
        }

        $file = UploadedFile::fake()->image('album.jpg');

        $file = UploadedFile::fake()->image('album.jpg');

        $response = $this->actingAs($this->admin)->postJson('/api/albums', [
            'title' => 'New Album',
            'artist' => 'Test Artist',
            'release_year' => 2023,
            'price' => 19.99,
            'stock' => 100,
            'cover_image' => $file,
            'genres' => [1, 2]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Album created successfully'
            ]);

        $this->assertDatabaseHas('albums', ['title' => 'New Album']);
        Storage::disk('public')->assertExists('album_covers/' . $file->hashName());
    }

    #[Test]
    public function admin_can_view_album()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/albums/' . $this->album->id);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $this->album->id
                ]
            ]);
    }

    #[Test]
    public function admin_can_update_album()
    {
        $this->withoutMiddleware();
        
        // Get the first genre ID that exists
        $genre = \App\Models\Genre::first();
        
        $response = $this->actingAs($this->admin)->putJson('/api/albums/' . $this->album->id, [
            'title' => 'Updated Album',
            'artist' => $this->album->artist,
            'release_year' => $this->album->release_year,
            'price' => $this->album->price,
            'stock' => $this->album->stock,
            'genres' => [$genre->id] // Use the existing genre ID
        ]);

        if ($response->status() !== 200) {
            dump($response->getContent());
        }

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Album updated successfully'
            ]);
    }

    #[Test]
    public function admin_can_delete_album()
    {
        $response = $this->actingAs($this->admin)->deleteJson('/api/albums/' . $this->album->id);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Album deleted successfully'
            ]);

        $this->assertDatabaseMissing('albums', ['id' => $this->album->id]);
    }

    #[Test]
    public function admin_can_view_users_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_users_list()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/users');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'user_type']
                ]
            ]);
    }

    #[Test]
    public function admin_can_delete_user()
    {
        $response = $this->actingAs($this->admin)->deleteJson('/api/users/' . $this->user->id);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User deleted successfully'
            ]);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    }

    #[Test]
    public function admin_can_view_orders_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/orders');
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_order_detail_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/orders/' . $this->order->id);
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_view_orders_list()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/orders');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'customer_id', 'status', 'total_amount']
                ]
            ]);
    }

   #[Test]
    public function admin_can_view_single_order()
    {
        $response = $this->actingAs($this->admin)->getJson('/api/orders/' . $this->order->id);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->order->id
                ]
            ]);
    }

    #[Test]
    public function admin_can_update_order_status()
    {
        $response = $this->actingAs($this->admin)->putJson('/api/orders/' . $this->order->id, [
            'status' => 'accepted'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'accepted'
        ]);
    }
}