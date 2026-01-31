<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingFormTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function preview_route_shows_borrowing_form_without_auth(): void
    {
        $response = $this->get('/preview-borrowing-form');

        $response->assertStatus(200);
        $response->assertSee('Formulir Peminjaman Buku');
        $response->assertSee('Durasi Peminjaman');
        $response->assertSee('7 Hari');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_open_borrowing_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get('/borrowings/create');

        $response->assertStatus(200);
        $response->assertSee('Formulir Peminjaman Buku');
        $response->assertSee('Tanggal Pinjam');
        $response->assertSee('30 Hari');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function book_borrow_page_prefills_requested_book(): void
    {
        $user = User::factory()->create();
        $category = \App\Models\Category::create(['name' => 'Umum', 'slug' => 'umum']);
        $book = \App\Models\Book::create([
            'title' => 'Buku Test Prefill',
            'author' => 'Pengarang Contoh',
            'isbn' => 'TEST-ISBN-001',
            'category_id' => $category->id,
            'total_copies' => 2,
            'available_copies' => 2,
        ]);

        $this->actingAs($user);
        $response = $this->get('/books/' . $book->id . '/borrow');

        $response->assertStatus(200);
        $response->assertSee('Buku Test Prefill');
        // assert the option is present and has the selected attribute
        $this->assertMatchesRegularExpression('/<option[^>]*value="' . $book->id . '"[^>]*selected[^>]*>/s', (string) $response->getContent());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function modal_submission_with_validation_errors_reopens_modal(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // submit from dashboard modal with missing required fields
        $response = $this->from('/dashboard')->post(route('borrowings.store'), [
            'from' => 'modal',
            // intentionally omit required fields like book_id
        ]);

        $response->assertSessionHasErrors('book_id');

        // follow the redirect and assert the dashboard contains the modal marker + preserved old input
        $follow = $this->followingRedirects()->get('/dashboard');
        $follow->assertSee('openBorrowModalBtn');
        $follow->assertSee('name="from" value="modal"', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function legacy_member_paths_redirect_to_canonical_borrowing_routes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // legacy index path
        $resp1 = $this->get('/member/borrowings');
        $resp1->assertStatus(302);
        $resp1->assertRedirect(route('borrowings.index'));

        // legacy create path should redirect to canonical create
        $resp2 = $this->get('/member/borrowings/create');
        $resp2->assertStatus(302);
        $resp2->assertRedirect(route('borrowings.create'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_to_books_borrow_legacy_url_is_supported(): void
    {
        $user = User::factory()->create();
        $category = \App\Models\Category::create(['name' => 'Umum', 'slug' => 'umum']);
        $book = \App\Models\Book::create([
            'title' => 'Buku Test POST Legacy',
            'author' => 'Pengarang Contoh',
            'isbn' => 'TEST-ISBN-POST-LEGACY',
            'category_id' => $category->id,
            'total_copies' => 2,
            'available_copies' => 2,
        ]);

        $this->actingAs($user);

        $payload = [
            'book_id' => $book->id,
            'duration_days' => 14,
            'due_date' => now()->addDays(14)->format('Y-m-d'),
            'agree_terms' => 'on',
            'agree_condition' => 'on',
            'agree_loss' => 'on',
        ];

        $response = $this->post('/books/' . $book->id . '/borrow', $payload);

        $response->assertRedirect(route('borrowings.index'));
        $this->assertDatabaseHas('borrowings', ['book_id' => $book->id, 'user_id' => $user->id]);
    }
}
