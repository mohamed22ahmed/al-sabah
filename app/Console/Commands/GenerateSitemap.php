<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'this command for generating sitemap file automatically';

    public function handle()
    {
        $sitemap = Sitemap::create();
        $staticPages = [
            '/',
            '/about-us',
            '/privacy',
            '/terms',
            '/returns',
            '/complains',
            '/cart',
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create(url($page))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
        }

        Product::query()
            ->chunk(100, function ($products) use ($sitemap) {
                foreach ($products as $product) {
                    $sitemap->add(
                        Url::create(
                            route('product.show', $product->id)
                        )
                            ->setPriority(0.9)
                            ->setChangeFrequency(
                                Url::CHANGE_FREQUENCY_WEEKLY
                            )
                    );
                }
            });

        Category::query()
            ->chunk(100, function ($categories) use ($sitemap) {
                foreach ($categories as $category) {
                    $sitemap->add(
                        Url::create(
                            route('show', $category->name)
                        )
                            ->setPriority(0.8)
                            ->setChangeFrequency(
                                Url::CHANGE_FREQUENCY_WEEKLY
                            )
                    );
                }
            });

        $sitemap->writeToFile(
            public_path('sitemap.xml')
        );

        $this->info('Sitemap generated successfully.');
    }
}
