<?php

namespace Database\Seeders;

use App\Models\LabTest;
use Illuminate\Database\Seeder;

class LabTestSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Hematologi Lengkap',
                'slug' => 'hematologi-lengkap',
                'description' => 'Pemeriksaan darah lengkap untuk mengevaluasi hemoglobin, eritrosit, leukosit, trombosit, dan hematokrit.',
                'benefit' => 'Membantu mendeteksi anemia, infeksi, gangguan pembekuan darah, dan kondisi kesehatan umum.',
                'preparation' => 'Puasa tidak diperlukan. Pasien disarankan cukup istirahat dan minum air putih.',
                'price' => 145000,
            ],
            [
                'name' => 'Gula Darah Puasa',
                'slug' => 'gula-darah-puasa',
                'description' => 'Pemeriksaan kadar gula darah setelah puasa untuk skrining dan pemantauan diabetes.',
                'benefit' => 'Membantu memantau kadar glukosa darah dan mendukung evaluasi risiko diabetes.',
                'preparation' => 'Pasien dianjurkan puasa 8 sampai 10 jam sebelum pemeriksaan.',
                'price' => 55000,
            ],
            [
                'name' => 'Profil Lipid Lengkap',
                'slug' => 'profil-lipid-lengkap',
                'description' => 'Pemeriksaan kolesterol total, LDL, HDL, dan trigliserida untuk menilai risiko penyakit jantung.',
                'benefit' => 'Membantu mengevaluasi profil lemak darah dan risiko gangguan kardiovaskular.',
                'preparation' => 'Pasien dapat mengikuti instruksi puasa sesuai arahan petugas atau dokter.',
                'price' => 180000,
            ],
            [
                'name' => 'Asam Urat',
                'slug' => 'asam-urat',
                'description' => 'Pemeriksaan kadar asam urat dalam darah untuk membantu deteksi risiko gout.',
                'benefit' => 'Membantu memantau kadar asam urat dan risiko nyeri sendi akibat gout.',
                'preparation' => 'Hindari konsumsi makanan tinggi purin secara berlebihan sebelum pemeriksaan.',
                'price' => 45000,
            ],
        ];

        foreach ($services as $service) {
            LabTest::updateOrCreate(
                ['slug' => $service['slug']],
                array_merge($service, ['status' => 'active'])
            );
        }
    }
}
