<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Source;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Source::insert([
            [
                'name'       => 'News API',
                'identifier' => 'newsapi',
                'url'        => 'https://newsapi.org',
                'api_key'    => env("NEWSAPI_KEY"),
            ],
            [
                'name'       => 'New York Times',
                'identifier' => 'nytimes',
                'url'        => 'https://api.nytimes.com',
                'api_key'    => env("NYT_KEY"),
            ],
            [
                'name'       => 'The Guardian',
                'identifier' => 'guardian',
                'url'        => 'https://content.guardianapis.com',
                'api_key'    => env("GUARDIAN_KEY"),
            ],
        ]);
    }
}
