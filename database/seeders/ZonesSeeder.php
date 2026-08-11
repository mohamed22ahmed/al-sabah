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
            ['id' => 1, 'name' => 'Riyadh', 'name_ar' => 'الرياض'],
            ['id' => 2, 'name' => 'Makkah', 'name_ar' => 'مكة'],
            ['id' => 3, 'name' => 'Medina', 'name_ar' => 'المدينة'],
            ['id' => 4, 'name' => 'Eastern Province', 'name_ar' => 'المنطقة الشرقية'],
            ['id' => 5, 'name' => 'Asir', 'name_ar' => 'عسير'],
            ['id' => 6, 'name' => 'Tabuk', 'name_ar' => 'تبوك'],
            ['id' => 7, 'name' => 'Hail', 'name_ar' => 'حائل'],
            ['id' => 8, 'name' => 'Northern Borders', 'name_ar' => 'الحدود الشمالية'],
            ['id' => 9, 'name' => 'Jazan', 'name_ar' => 'جازان'],
            ['id' => 10, 'name' => 'Najran', 'name_ar' => 'نجران'],
            ['id' => 11, 'name' => 'Al Bahah', 'name_ar' => 'الباحة'],
            ['id' => 12, 'name' => 'Al Jawf', 'name_ar' => 'الجوف'],
            ['id' => 13, 'name' => 'Qassim', 'name_ar' => 'القصيم'],
            ['id' => 14, 'name' => 'Al-Kharj', 'name_ar' => 'الخرج'],
            ['id' => 15, 'name' => 'Al-Majmaah', 'name_ar' => 'المجمعة'],
            ['id' => 16, 'name' => 'Al-Dawadmi', 'name_ar' => 'الدوادمي'],
            ['id' => 17, 'name' => 'Wadi ad-Dawasir', 'name_ar' => 'وادي الدواسر'],
            ['id' => 18, 'name' => 'Jeddah', 'name_ar' => 'جدة'],
            ['id' => 19, 'name' => 'Taif', 'name_ar' => 'الطائف'],
            ['id' => 20, 'name' => 'Rabigh', 'name_ar' => 'رابغ'],
            ['id' => 21, 'name' => 'Al-Qunfudhah', 'name_ar' => 'القنفذة'],
            ['id' => 22, 'name' => 'Yanbu', 'name_ar' => 'ينبع'],
            ['id' => 23, 'name' => 'Badr', 'name_ar' => 'بدر'],
            ['id' => 24, 'name' => 'Al-Ula', 'name_ar' => 'العلا'],
            ['id' => 25, 'name' => 'Dammam', 'name_ar' => 'الدمام'],
            ['id' => 26, 'name' => 'Khobar', 'name_ar' => 'الخبر'],
            ['id' => 27, 'name' => 'Jubail', 'name_ar' => 'الجبيل'],
            ['id' => 28, 'name' => 'Hofuf', 'name_ar' => 'الهفوف'],
            ['id' => 29, 'name' => 'Qatif', 'name_ar' => 'القطيف'],
            ['id' => 30, 'name' => 'Abha', 'name_ar' => 'أبها'],
            ['id' => 31, 'name' => 'Khamis Mushait', 'name_ar' => 'خميس مشيط'],
            ['id' => 32, 'name' => 'Bisha', 'name_ar' => 'بيشة'],
            ['id' => 33, 'name' => 'Tabuk', 'name_ar' => 'تبوك'],
            ['id' => 34, 'name' => 'Duba', 'name_ar' => 'ضباء'],
            ['id' => 35, 'name' => 'Al-Wajh', 'name_ar' => 'الوجه'],
            ['id' => 36, 'name' => 'Hail', 'name_ar' => 'حائل'],
            ['id' => 37, 'name' => 'Baqaa', 'name_ar' => 'بقعاء'],
            ['id' => 38, 'name' => 'Arar', 'name_ar' => 'عرعر'],
            ['id' => 39, 'name' => 'Rafha', 'name_ar' => 'رفحاء'],
            ['id' => 40, 'name' => 'Turaif', 'name_ar' => 'طريف'],
            ['id' => 41, 'name' => 'Jazan', 'name_ar' => 'جازان'],
            ['id' => 42, 'name' => 'Sabia', 'name_ar' => 'صبيا'],
            ['id' => 43, 'name' => 'Abu Arish', 'name_ar' => 'أبو عريش'],
            ['id' => 44, 'name' => 'Najran', 'name_ar' => 'نجران'],
            ['id' => 45, 'name' => 'Sharurah', 'name_ar' => 'شرورة'],
            ['id' => 46, 'name' => 'Al Bahah', 'name_ar' => 'الباحة'],
            ['id' => 47, 'name' => 'Baljurashi', 'name_ar' => 'بلجرشي'],
            ['id' => 48, 'name' => 'Sakakah', 'name_ar' => 'سكاكا'],
            ['id' => 49, 'name' => 'Al Qurayyat', 'name_ar' => 'القريات'],
            ['id' => 50, 'name' => 'Buraidah', 'name_ar' => 'بريدة'],
            ['id' => 51, 'name' => 'Unaizah', 'name_ar' => 'عنيزة'],
            ['id' => 52, 'name' => 'Al-Rass', 'name_ar' => 'الرس']
        ];
    }
}
