<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Dewey Decimal Classification (DDC) Main Classes
            ['code' => '000', 'name' => 'Computer Science, Information & General Works', 'description' => 'General knowledge', 'parent_id' => null, 'order' => 1],
            ['code' => '100', 'name' => 'Philosophy & Psychology', 'description' => 'Philosophy and related disciplines', 'parent_id' => null, 'order' => 2],
            ['code' => '200', 'name' => 'Religion', 'description' => 'Religion and theology', 'parent_id' => null, 'order' => 3],
            ['code' => '300', 'name' => 'Social Sciences', 'description' => 'Social sciences, sociology & anthropology', 'parent_id' => null, 'order' => 4],
            ['code' => '400', 'name' => 'Language', 'description' => 'Languages and linguistics', 'parent_id' => null, 'order' => 5],
            ['code' => '500', 'name' => 'Science', 'description' => 'Natural sciences and mathematics', 'parent_id' => null, 'order' => 6],
            ['code' => '600', 'name' => 'Technology', 'description' => 'Applied sciences and technology', 'parent_id' => null, 'order' => 7],
            ['code' => '700', 'name' => 'Arts & Recreation', 'description' => 'Arts, music, sports', 'parent_id' => null, 'order' => 8],
            ['code' => '800', 'name' => 'Literature', 'description' => 'Literature and rhetoric', 'parent_id' => null, 'order' => 9],
            ['code' => '900', 'name' => 'History & Geography', 'description' => 'History and geography', 'parent_id' => null, 'order' => 10],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        // Add some subcategories
        $subcategories = [
            ['code' => '004', 'name' => 'Data Processing & Computer Science', 'description' => 'Computer programming', 'parent_id' => 1],
            ['code' => '150', 'name' => 'Psychology', 'description' => 'Psychology and related topics', 'parent_id' => 2],
            ['code' => '297', 'name' => 'Islam', 'description' => 'Islamic religion', 'parent_id' => 3],
            ['code' => '330', 'name' => 'Economics', 'description' => 'Economic theory and systems', 'parent_id' => 4],
            ['code' => '420', 'name' => 'English Language', 'description' => 'English and Old English', 'parent_id' => 5],
            ['code' => '510', 'name' => 'Mathematics', 'description' => 'Mathematical sciences', 'parent_id' => 6],
            ['code' => '610', 'name' => 'Medicine & Health', 'description' => 'Medical sciences', 'parent_id' => 7],
            ['code' => '745', 'name' => 'Decorative Arts & Crafts', 'description' => 'Arts and crafts', 'parent_id' => 8],
            ['code' => '823', 'name' => 'English Fiction', 'description' => 'English fiction literature', 'parent_id' => 9],
            ['code' => '959', 'name' => 'Southeast Asia', 'description' => 'History of Southeast Asia', 'parent_id' => 10],
        ];

        foreach ($subcategories as $subcategory) {
            \App\Models\Category::create($subcategory);
        }
    }
}
