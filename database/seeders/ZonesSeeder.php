<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonesSeeder extends Seeder
{
    public function run(): void
    {
        $zones = $this->getZonesData();
        DB::table('zones')->insert($zones);
    }

    public function getZonesData(): array
    {
        return [
            ['id' => 1, 'name' => 'Riyadh', 'name_ar' => 'الرياض', 'price' => 50],
            ['id' => 2, 'name' => 'Makkah', 'name_ar' => 'مكة', 'price' => 0],
            ['id' => 3, 'name' => 'Medina', 'name_ar' => 'المدينة', 'price' => 50],
            ['id' => 4, 'name' => 'Eastern Province', 'name_ar' => 'المنطقة الشرقية', 'price' => 50],
            ['id' => 5, 'name' => 'Asir', 'name_ar' => 'عسير', 'price' => 50],
            ['id' => 6, 'name' => 'Tabuk', 'name_ar' => 'تبوك', 'price' => 50],
            ['id' => 7, 'name' => 'Hail', 'name_ar' => 'حائل', 'price' => 50],
            ['id' => 8, 'name' => 'Northern Borders', 'name_ar' => 'الحدود الشمالية', 'price' => 50],
            ['id' => 9, 'name' => 'Jazan', 'name_ar' => 'جازان', 'price' => 50],
            ['id' => 10, 'name' => 'Najran', 'name_ar' => 'نجران', 'price' => 50],
            ['id' => 11, 'name' => 'Al Bahah', 'name_ar' => 'الباحة', 'price' => 50],
            ['id' => 12, 'name' => 'Al Jawf', 'name_ar' => 'الجوف', 'price' => 50],
            ['id' => 13, 'name' => 'Qassim', 'name_ar' => 'القصيم', 'price' => 50],
            ['id' => 14, 'name' => 'Al-Kharj', 'name_ar' => 'الخرج', 'price' => 50],
            ['id' => 15, 'name' => 'Al-Majmaah', 'name_ar' => 'المجمعة', 'price' => 50],
            ['id' => 16, 'name' => 'Al-Dawadmi', 'name_ar' => 'الدوادمي', 'price' => 50],
            ['id' => 17, 'name' => 'Wadi ad-Dawasir', 'name_ar' => 'وادي الدواسر', 'price' => 50],
            ['id' => 18, 'name' => 'Jeddah', 'name_ar' => 'جدة', 'price' => 0],
            ['id' => 19, 'name' => 'Taif', 'name_ar' => 'الطائف', 'price' => 0],
            ['id' => 20, 'name' => 'Rabigh', 'name_ar' => 'رابغ', 'price' => 50],
            ['id' => 21, 'name' => 'Al-Qunfudhah', 'name_ar' => 'القنفذة', 'price' => 50],
            ['id' => 22, 'name' => 'Yanbu', 'name_ar' => 'ينبع', 'price' => 50],
            ['id' => 23, 'name' => 'Badr', 'name_ar' => 'بدر', 'price' => 50],
            ['id' => 24, 'name' => 'Al-Ula', 'name_ar' => 'العلا', 'price' => 50],
            ['id' => 25, 'name' => 'Dammam', 'name_ar' => 'الدمام', 'price' => 50],
            ['id' => 26, 'name' => 'Khobar', 'name_ar' => 'الخبر', 'price' => 50],
            ['id' => 27, 'name' => 'Jubail', 'name_ar' => 'الجبيل', 'price' => 50],
            ['id' => 28, 'name' => 'Hofuf', 'name_ar' => 'الهفوف', 'price' => 50],
            ['id' => 29, 'name' => 'Qatif', 'name_ar' => 'القطيف', 'price' => 50],
            ['id' => 30, 'name' => 'Abha', 'name_ar' => 'أبها', 'price' => 50],
            ['id' => 31, 'name' => 'Khamis Mushait', 'name_ar' => 'خميس مشيط', 'price' => 50],
            ['id' => 32, 'name' => 'Bisha', 'name_ar' => 'بيشة', 'price' => 50],
            ['id' => 33, 'name' => 'Tabuk', 'name_ar' => 'تبوك', 'price' => 50],
            ['id' => 34, 'name' => 'Duba', 'name_ar' => 'ضباء', 'price' => 50],
            ['id' => 35, 'name' => 'Al-Wajh', 'name_ar' => 'الوجه', 'price' => 50],
            ['id' => 36, 'name' => 'Hail', 'name_ar' => 'حائل', 'price' => 50],
            ['id' => 37, 'name' => 'Baqaa', 'name_ar' => 'بقعاء', 'price' => 50],
            ['id' => 38, 'name' => 'Arar', 'name_ar' => 'عرعر', 'price' => 50],
            ['id' => 39, 'name' => 'Rafha', 'name_ar' => 'رفحاء', 'price' => 50],
            ['id' => 40, 'name' => 'Turaif', 'name_ar' => 'طريف', 'price' => 50],
            ['id' => 41, 'name' => 'Jazan', 'name_ar' => 'جازان', 'price' => 50],
            ['id' => 42, 'name' => 'Sabia', 'name_ar' => 'صبيا', 'price' => 50],
            ['id' => 43, 'name' => 'Abu Arish', 'name_ar' => 'أبو عريش', 'price' => 50],
            ['id' => 44, 'name' => 'Najran', 'name_ar' => 'نجران', 'price' => 50],
            ['id' => 45, 'name' => 'Sharurah', 'name_ar' => 'شرورة', 'price' => 50],
            ['id' => 46, 'name' => 'Al Bahah', 'name_ar' => 'الباحة', 'price' => 50],
            ['id' => 47, 'name' => 'Baljurashi', 'name_ar' => 'بلجرشي', 'price' => 50],
            ['id' => 48, 'name' => 'Sakakah', 'name_ar' => 'سكاكا', 'price' => 50],
            ['id' => 49, 'name' => 'Al Qurayyat', 'name_ar' => 'القريات', 'price' => 50],
            ['id' => 50, 'name' => 'Buraidah', 'name_ar' => 'بريدة', 'price' => 50],
            ['id' => 51, 'name' => 'Unaizah', 'name_ar' => 'عنيزة', 'price' => 50],
            ['id' => 52, 'name' => 'Al-Rass', 'name_ar' => 'الرس', 'price' => 50],
        ];
    }
}
