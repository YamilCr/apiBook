<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
   function can_get_all_books()
   {

    $books =  Book::factory(4)->create();

    $response = $this->getJson(route('books.index'));

    $response->assertJsonFragment([
        'title' => $books[0]->title
    ])->assertJsonFragment([
        'title' => $books[2]->title
    ]);
   }
   
   /** @test */
   function can_get_one_book()
   {

    $book =  Book::factory()->create();

    // dd(route('books.show', $book));
    $response = $this->getJson(route('books.show', $book));

    $response->assertJsonFragment([
        'title' => $book->title
    ]);
   }
    /** @test */
    function can_create_book()
    {
        $this->postJson(route('books.store'),[])
        ->assertJsonValidationErrorFor('title');

       $this->postJson(route('books.store'), [
            'title' => 'Mi nuevo libro'
        ])->assertJsonFragment([
            'title' => 'Mi nuevo libro'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Mi nuevo libro'
        ]);
    }

        /** @test */
        function can_update_book()
        {
            $book = Book::factory()->create();

            $this->patchJson(route('books.update', $book),[])
             ->assertJsonValidationErrorFor('title');


    
           $this->patchJson(route('books.update', $book), [
                'title' => 'Libro editado'
            ])->assertJsonFragment([
                'title' => 'Libro editado'
            ]);
    
            $this->assertDatabaseHas('books', [
                'title' => 'Libro editado'
            ]);

            
        }

        /** @test */
        function can_delete_book()
        {
            $book = Book::factory()->create();
    
           $this->deleteJson(route('books.destroy', $book))
           ->assertNoContent();
    
            $this->assertDatabaseCount('books', 0);

            
        }
}

