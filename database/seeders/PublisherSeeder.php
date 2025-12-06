<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishers = [
            ['name' => 'Gramedia Pustaka Utama', 'email' => 'info@gramedia.com', 'phone' => '021-5300011', 'address' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'Erlangga', 'email' => 'info@erlangga.co.id', 'phone' => '021-4890555', 'address' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'Mizan', 'email' => 'contact@mizanstore.com', 'phone' => '022-5229999', 'address' => 'Bandung', 'country' => 'Indonesia'],
            ['name' => 'Bentang Pustaka', 'email' => 'info@bentangpustaka.com', 'phone' => '0274-412333', 'address' => 'Yogyakarta', 'country' => 'Indonesia'],
            ['name' => 'Tiga Serangkai', 'email' => 'tiga.serangkai@gmail.com', 'phone' => '0271-714344', 'address' => 'Solo', 'country' => 'Indonesia'],
            ['name' => 'Andi Publisher', 'email' => 'andi@andipublisher.com', 'phone' => '0274-561881', 'address' => 'Yogyakarta', 'country' => 'Indonesia'],
            ['name' => 'Informatika', 'email' => 'info@informatika.co.id', 'phone' => '022-7271111', 'address' => 'Bandung', 'country' => 'Indonesia'],
            ['name' => 'Deepublish', 'email' => 'cs@deepublish.com', 'phone' => '0274-435462', 'address' => 'Yogyakarta', 'country' => 'Indonesia'],
            ['name' => 'Penguin Random House', 'email' => 'info@penguinrandomhouse.com', 'phone' => '+1-212-782-9000', 'address' => 'New York', 'country' => 'USA'],
            ['name' => 'Oxford University Press', 'email' => 'webquery@oup.com', 'phone' => '+44-1865-556767', 'address' => 'Oxford', 'country' => 'UK'],
        ];

        foreach ($publishers as $publisher) {
            \App\Models\Publisher::create($publisher);
        }
    }
}
