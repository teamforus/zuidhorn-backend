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
        $categoris = collect(['Books', 'Bikes', 'Piano lessons', 
            'Sport toys', 'Swimming']);

        $categoris->map(function($category_name, $category_id) {
            return Category::create([
                'id'        => $category_id + 1,
                'name'      => $category_name,
                ]);
        })->each(function($edit) {
            // media details
            $original_type  = 'original';
            $preview_type   = 'preview';
            $mediable_type  = Category::class;
            $mediable_id    = $edit->id;

            $image = storage_path("/app/seed/categories/{$edit->name}.jpg");

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
