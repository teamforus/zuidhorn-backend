<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Media;

class CategoriesTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoris = collect(['Books', 'Bikes', /*'Piano lessons',*/ 
            'Sport toys', 'Swimming', 'Computer', 'Clothing', 'Toys']);

        $names = [
            'Books'         => "Boeken", 
            'Bikes'         => "Fiets", 
            // 'Piano lessons' => "lorem", 
            'Sport toys'    => "Sport accessoires", 
            'Swimming'      => "Zwemmen", 
            'Computer'      => "Computer", 
            'Clothing'      => "Kleding", 
            'Toys'          => "Speelgoed"
        ];

        $names_flip = array_flip($names);

        $categoris->map(function($key, $category_id) use ($names) {
            return Category::create([
                'id'        => $category_id + 1,
                'name'      => $names[$key],
                ]);
        })->each(function($edit) use ($names_flip) {
            // media details
            $original_type  = 'original';
            $preview_type   = 'preview';
            $mediable_type  = Category::class;
            $mediable_id    = $edit->id;

            $image = storage_path("/app/seed/categories/{$names_flip[$edit->name]}.jpg");

            // upload photo
            if (file_exists($image)) {
                $media_info = Media::uploadSingleFromFile(
                    $image, 
                    $original_type, 
                    $mediable_type);

                // confirm uploaded photo
                $media = Media::confirmSingle(
                    $original_type, 
                    $mediable_type, 
                    $mediable_id, 
                    $media_info['mediaId']);

                $media_info = Media::uploadSingleFromFile(
                    $image, 
                    $preview_type, 
                    $mediable_type);

                // confirm uploaded photo
                $media = Media::confirmSingle(
                    $preview_type, 
                    $mediable_type, 
                    $mediable_id, 
                    $media_info['mediaId']);
            }
        });
    }
}
