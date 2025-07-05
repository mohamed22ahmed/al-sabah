<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();
        $categories = [
            [
                "id"=> 1,
                "name"=> "الرئيسية",
                "url"=> "/",
                "created_at"=> null,
                "updated_at"=> null
            ],
            [
                "id"=> 2,
                "name"=> "معدات المطاعم",
                "url"=> "/menu",
                "created_at"=> null,
                "updated_at"=> null
            ],
            [
                "id"=> 3,
                "name"=> "معدات القهوة",
                "url"=> "/coffee",
                "created_at"=> null,
                "updated_at"=> null
            ],
            [
                "id"=> 4,
                "name"=> "معدات المشروبات والعصائر",
                "url"=> "/drinks",
                "created_at"=> null,
                "updated_at"=> null
            ],
            [
                "id"=> 5,
                "name"=> "مستلزمات الايسكريم",
                "url"=> "/ice-cream",
                "created_at"=> null,
                "updated_at"=> null
            ],
            [
                "id"=> 6,
                "name"=> "الموازين",
                "url"=> "/stocks",
                "created_at"=> null,
                "updated_at"=> null
            ],
            [
                "id"=> 7,
                "name"=> "معدات الحلي",
                "url"=> "/honey",
                "created_at"=> null,
                "updated_at"=> null
            ],
            [
                "id"=> 8,
                "name"=> "قطع غيار spare parts",
                "url"=> "/spare-parts",
                "created_at"=> null,
                "updated_at"=> null
            ]
        ];
        foreach ($categories as $category){
            Category::create($category);
        }
    }
}
