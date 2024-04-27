<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create([
            'tag'=> 'Горы'
        ]);
        Tag::create([
            'tag'=> 'Активный отдых'
        ]);
        Tag::create([
            'tag'=> 'Море'
        ]);
        Tag::create([
            'tag'=> 'Реки'
        ]);
        Tag::create([
            'tag'=> 'Культура'
        ]);
        Tag::create([
            'tag'=> 'Музеи'
        ]);
    }
}
