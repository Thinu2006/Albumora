<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Album;
use App\Models\Order;
use App\Models\Genre;
use PHPUnit\Framework\Attributes\Test;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $customer;
    protected $albums;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF protection for all tests
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Create a customer user
        $this->customer = User::factory()->create([
            'user_type' => 'user',
            'password' => bcrypt('password')
        ]);

        // Create some genres first
        $genres = Genre::factory()->count(3)->create();
        
        // Create albums and attach genres
        $this->albums = Album::factory()->count(5)->create();
        
        foreach ($this->albums as $album) {
            $album->genres()->attach($genres->random(rand(1, 3)));
        }
    }

    #[Test]
    public function customer_can_view_albums()
    {
        $this->actingAs($this->customer);

        // Test API endpoint
        $apiResponse = $this->getJson('/api/albums');
        $apiResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'artist',
                        'price',
                        'cover_image',
                        'genres'
                    ]
                ]
            ]);

        // Test web route
        $webResponse = $this->get('/user/albums');
        $webResponse->assertStatus(200)
            ->assertViewIs('user.album-search');
    }

    #[Test]
    public function customer_can_view_album_details()
    {
        $this->actingAs($this->customer);
        $album = $this->albums->first();

        $response = $this->getJson("/api/albums/{$album->id}");
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $album->id,
                    'title' => $album->title,
                    'artist' => $album->artist,
                    'genres' => $album->genres->map(fn($g) => [
                        'id' => $g->id,
                        'name' => $g->name
                    ])->toArray()
                ]
            ]);
    }

    #[Test]
    public function customer_can_view_cart_page()
    {
        $this->actingAs($this->customer);

        $response = $this->get('/user/cart');
        $response->assertStatus(200)
            ->assertViewIs('user.cart')
            ->assertViewHas('allAlbums');
    }

    #[Test]
    public function customer_can_view_checkout_page()
    {
        $this->actingAs($this->customer);

        $response = $this->get('/user/checkout');
        $response->assertStatus(200)
            ->assertViewIs('user.checkout')
            ->assertViewHas('allAlbums');
    }

    #[Test]
    public function customer_can_create_order()
    {
        $this->actingAs($this->customer);
        
        $selectedAlbums = $this->albums->take(2)->map(function($album) {
            return [
                'id' => $album->id,
                'quantity' => 1,
                'unit_price' => $album->price
            ];
        });

        $totalAmount = $selectedAlbums->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        $orderData = [
            'customer_id' => $this->customer->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'albums' => $selectedAlbums->toArray(),
            'shipment' => [
                'address_line1' => '123 Test St',
                'city' => 'Testville',
                'state' => 'TS',
                'country' => 'Testland'
            ],
            'payment' => [
                'cardholder_name' => 'Test User',
                'card_number' => '4111111111111111',
                'expiration_month' => '12',
                'expiration_year' => '2025',
                'card_type' => 'visa'
            ],
            'update_stock' => true
        ];

        $response = $this->postJson('/api/orders', $orderData);
        
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'customer_id',
                    'total_amount',
                    'status',
                    'items' => [
                        '*' => [
                            'id',
                            'quantity',
                            'unit_price'
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $this->customer->id,
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);
    }

    #[Test]
    public function customer_can_view_their_orders()
    {
        $this->actingAs($this->customer);
        
        // Create some orders for the customer
        $orders = Order::factory()->count(3)->create([
            'customer_id' => $this->customer->id
        ]);

        // Attach albums to orders
        foreach ($orders as $order) {
            $order->albums()->attach($this->albums->random(rand(1, 3)), [
                'quantity' => 1,
                'unit_price' => 10.99
            ]);
        }

        // Test API endpoint
        $apiResponse = $this->getJson('/api/orders?user_id=' . $this->customer->id);
        $apiResponse->assertStatus(200)
            ->assertJsonCount(3, 'data');

        // Test web route
        $webResponse = $this->get('/user/orders');
        $webResponse->assertStatus(200)
            ->assertViewIs('user.orders.list');
    }

    #[Test]
    public function customer_can_view_single_order_details()
    {
        $this->actingAs($this->customer);
        
        $order = Order::factory()->create([
            'customer_id' => $this->customer->id
        ]);

        // Attach albums to the order
        $order->albums()->attach($this->albums->random(2), [
            'quantity' => 1,
            'unit_price' => 10.99
        ]);

        // Test API endpoint
        $apiResponse = $this->getJson("/api/orders/{$order->id}");
        $apiResponse->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id,
                    'customer_id' => $this->customer->id
                ]
            ]);

        // Test web route
        $webResponse = $this->get("/user/orders/{$order->id}");
        $webResponse->assertStatus(200)
            ->assertViewIs('user.orders.show')
            ->assertViewHas('order', $order);
    }

    #[Test]
    public function customer_can_cancel_pending_order()
    {
        $this->actingAs($this->customer);
        
        $order = Order::factory()->create([
            'customer_id' => $this->customer->id,
            'status' => 'pending'
        ]);

        $response = $this->patchJson("/api/orders/{$order->id}", [
            'status' => 'cancelled'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => 'cancelled'
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled'
        ]);
    }

    #[Test]
    public function customer_cannot_cancel_non_pending_order()
    {
        $this->actingAs($this->customer);
        
        $order = Order::factory()->create([
            'customer_id' => $this->customer->id,
            'status' => 'completed'
        ]);

        $response = $this->patchJson("/api/orders/{$order->id}", [
            'status' => 'cancelled'
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed'
        ]);
    }

    #[Test]
    public function customer_can_view_review_page()
    {
        $this->actingAs($this->customer);

        $response = $this->get('/user/review');
        $response->assertStatus(200)
            ->assertViewIs('user.review');
    }
}