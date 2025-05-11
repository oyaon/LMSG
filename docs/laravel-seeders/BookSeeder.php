<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Import existing books from the old database
        $oldBooks = DB::connection('mysql_old')
            ->table('all_books')
            ->get();

        foreach ($oldBooks as $oldBook) {
            // Skip if book already exists
            if (Book::where('name', $oldBook->name)
                ->where('author', $oldBook->author)
                ->exists()) {
                continue;
            }

            // Find or create author
            $author = Author::firstOrCreate(
                ['name' => $oldBook->author],
                ['biography' => null]
            );

            // Copy cover image if exists
            $coverImage = null;
            if (!empty($oldBook->cover_image) && File::exists(public_path('images/covers/' . $oldBook->cover_image))) {
                $coverImage = $oldBook->cover_image;
                File::copy(
                    public_path('images/covers/' . $oldBook->cover_image),
                    storage_path('app/public/covers/' . $oldBook->cover_image)
                );
            }

            // Copy PDF if exists
            $pdf = null;
            if (!empty($oldBook->pdf) && File::exists(public_path('pdfs/' . $oldBook->pdf))) {
                $pdf = $oldBook->pdf;
                File::copy(
                    public_path('pdfs/' . $oldBook->pdf),
                    storage_path('app/public/pdfs/' . $oldBook->pdf)
                );
            }

            // Create book
            Book::create([
                'name' => $oldBook->name,
                'author_id' => $author->id,
                'author' => $oldBook->author,
                'category' => $oldBook->category,
                'description' => $oldBook->description,
                'quantity' => $oldBook->quantity,
                'price' => $oldBook->price,
                'pdf' => $pdf,
                'cover_image' => $coverImage,
                'created_at' => $oldBook->created_at ?? now(),
                'updated_at' => $oldBook->updated_at ?? now(),
            ]);
        }
    }
}