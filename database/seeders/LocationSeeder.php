<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['code' => 'R001', 'name' => 'Reading Room 1', 'type' => 'room', 'description' => 'Main reading room', 'capacity' => 100],
            ['code' => 'R002', 'name' => 'Reference Room', 'type' => 'room', 'description' => 'Reference books only', 'capacity' => 50],
            ['code' => 'S-A01', 'name' => 'Shelf A1 - General Works', 'type' => 'shelf', 'description' => 'DDC 000-099', 'capacity' => 200],
            ['code' => 'S-A02', 'name' => 'Shelf A2 - Philosophy', 'type' => 'shelf', 'description' => 'DDC 100-199', 'capacity' => 150],
            ['code' => 'S-A03', 'name' => 'Shelf A3 - Religion', 'type' => 'shelf', 'description' => 'DDC 200-299', 'capacity' => 150],
            ['code' => 'S-B01', 'name' => 'Shelf B1 - Social Sciences', 'type' => 'shelf', 'description' => 'DDC 300-399', 'capacity' => 250],
            ['code' => 'S-B02', 'name' => 'Shelf B2 - Language', 'type' => 'shelf', 'description' => 'DDC 400-499', 'capacity' => 150],
            ['code' => 'S-C01', 'name' => 'Shelf C1 - Science', 'type' => 'shelf', 'description' => 'DDC 500-599', 'capacity' => 300],
            ['code' => 'S-C02', 'name' => 'Shelf C2 - Technology', 'type' => 'shelf', 'description' => 'DDC 600-699', 'capacity' => 300],
            ['code' => 'S-D01', 'name' => 'Shelf D1 - Arts', 'type' => 'shelf', 'description' => 'DDC 700-799', 'capacity' => 200],
            ['code' => 'S-D02', 'name' => 'Shelf D2 - Literature', 'type' => 'shelf', 'description' => 'DDC 800-899', 'capacity' => 250],
            ['code' => 'S-E01', 'name' => 'Shelf E1 - History', 'type' => 'shelf', 'description' => 'DDC 900-999', 'capacity' => 200],
        ];

        foreach ($locations as $location) {
            \App\Models\Location::create($location);
        }
    }
}
