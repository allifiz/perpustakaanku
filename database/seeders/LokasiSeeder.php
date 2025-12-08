<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['code' => 'R001', 'name' => 'Ruang Baca Utama', 'type' => 'room', 'description' => 'Ruang baca utama untuk pengunjung', 'capacity' => 100],
            ['code' => 'R002', 'name' => 'Ruang Referensi', 'type' => 'room', 'description' => 'Khusus buku referensi (tidak dipinjamkan)', 'capacity' => 50],
            ['code' => 'S-A01', 'name' => 'Rak A1 - Karya Umum', 'type' => 'shelf', 'description' => 'DDC 000-099 (Komputer, Informasi, dll)', 'capacity' => 200],
            ['code' => 'S-A02', 'name' => 'Rak A2 - Filsafat & Psikologi', 'type' => 'shelf', 'description' => 'DDC 100-199', 'capacity' => 150],
            ['code' => 'S-A03', 'name' => 'Rak A3 - Agama', 'type' => 'shelf', 'description' => 'DDC 200-299', 'capacity' => 150],
            ['code' => 'S-B01', 'name' => 'Rak B1 - Ilmu Sosial', 'type' => 'shelf', 'description' => 'DDC 300-399 (Pendidikan, Hukum, Ekonomi)', 'capacity' => 250],
            ['code' => 'S-B02', 'name' => 'Rak B2 - Bahasa', 'type' => 'shelf', 'description' => 'DDC 400-499', 'capacity' => 150],
            ['code' => 'S-C01', 'name' => 'Rak C1 - Sains', 'type' => 'shelf', 'description' => 'DDC 500-599 (Matematika, Fisika, Biologi)', 'capacity' => 300],
            ['code' => 'S-C02', 'name' => 'Rak C2 - Teknologi', 'type' => 'shelf', 'description' => 'DDC 600-699 (Kedokteran, Teknik, Pertanian)', 'capacity' => 300],
            ['code' => 'S-D01', 'name' => 'Rak D1 - Kesenian', 'type' => 'shelf', 'description' => 'DDC 700-799 (Seni, Musik, Olahraga)', 'capacity' => 200],
            ['code' => 'S-D02', 'name' => 'Rak D2 - Kesusastraan', 'type' => 'shelf', 'description' => 'DDC 800-899 (Novel, Puisi, Drama)', 'capacity' => 250],
            ['code' => 'S-E01', 'name' => 'Rak E1 - Sejarah & Geografi', 'type' => 'shelf', 'description' => 'DDC 900-999', 'capacity' => 200],
        ];

        foreach ($locations as $location) {
            // Menggunakan updateOrCreate agar tidak error jika data sudah ada
            Location::updateOrCreate(
                ['code' => $location['code']], 
                $location
            );
        }
    }
}
