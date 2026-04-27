<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // === 1. BUAT AKUN ADMIN ===
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // === 2. BUAT DEPARTEMEN ===
        $it = Departemen::create(['kode' => 'IT','nama' => 'Teknologi Informasi',]);
        $hrd = Departemen::create(['kode' => 'HRD','nama' => 'Sumber Daya Manusia',]);
        $fin = Departemen::create(['kode' => 'FIN','nama' => 'Keuangan',]);
        $ops = Departemen::create(['kode' => 'OPS','nama' => 'Operasional',]);
        
        // === 3. BUAT JABATAN ===
        $progr = Jabatan::create(['departemen_id' => $it->id,'nama' => 'Programmer','gaji_pokok' => 8_000_000,]);
        $analis = Jabatan::create(['departemen_id' => $it->id,'nama' => 'Analis Sistem','gaji_pokok' => 7_000_000,]);
        $hrd_staff = Jabatan::create(['departemen_id' => $hrd->id,'nama' => 'HRD Staff','gaji_pokok' => 6_000_000,]);
        $hrd_manager = Jabatan::create(['departemen_id' => $hrd->id,'nama' => 'HRD Manager','gaji_pokok' => 10_000_000,]);
        $akuntan = Jabatan::create(['departemen_id' => $fin->id,'nama' => 'Akuntan','gaji_pokok' => 7_000_000,]);
        $keuangan_staff = Jabatan::create(['departemen_id' => $fin->id,'nama' => 'Keuangan Staff','gaji_pokok' => 6_000_000,]);
        $sopir = Jabatan::create(['departemen_id' => $ops->id,'nama' => 'Sopir','gaji_pokok' => 4_000_000,]);
        $gudang = Jabatan::create(['departemen_id' => $ops->id,'nama' => 'Gudang','gaji_pokok' => 4_500_000,]); 

        // === 4. BUAT KARYAWAN CONTOH ===
        $karyawan = [
            ['nik' => 'KRY001','nama' => 'Budi Santoso','departemen_id' => $it->id,'jabatan_id' => $progr->id,'gaji'=> 8_000_000,'tunjangan' => 200_000,'email' => 'budi@example.com'],
            ['nik' => 'KRY002','nama' => 'Siti Aminah','departemen_id' => $hrd->id,'jabatan_id' => $hrd_staff->id,'gaji'=> 6_000_000,'tunjangan' => 150_000,'email' => 'siti@example.com'],
            ['nik' => 'KRY003','nama' => 'Andi Wijaya','departemen_id' => $fin->id,'jabatan_id' => $akuntan->id,'gaji'=> 7_000_000,'tunjangan' => 180_000,'email' => 'andi@example.com'],
            ['nik' => 'KRY004','nama' => 'Dewi Lestari','departemen_id' => $ops->id,'jabatan_id' => $sopir->id,'gaji'=> 4_000_000,'tunjangan' => 100_000,'email' => 'dewi@example.com'],
            ['nik' => 'KRY005','nama' => 'Rina Kumala','departemen_id' => $hrd->id,'jabatan_id' => $hrd_manager->id,'gaji'=> 10_000_000,'tunjangan' => 250_000,'email' => 'rina@example.com'],
            ['nik' => 'KRY006','nama' => 'Ali Hassan','departemen_id' => $it->id,'jabatan_id' => $analis->id,'gaji'=> 7_000_000,'tunjangan' => 150_000,'email' => 'ali@example.com'],
            ['nik' => 'KRY007','nama' => 'Sari Dewi','departemen_id' => $fin->id,'jabatan_id' => $keuangan_staff->id,'gaji'=> 6_000_000,'tunjangan' => 150_000,'email' => 'sari@example.com'],
            ['nik' => 'KRY008','nama' => 'Joko Prasetyo','departemen_id' => $ops->id,'jabatan_id' => $gudang->id,'gaji'=> 4_500_000,'tunjangan' => 120_000,'email' => 'joko@example.com'],
        ];

        foreach ($karyawan as $data) {
            Karyawan::create([
                'nik' => $data['nik'],
                'nama' => $data['nama'],
                'email' => $data['email'],
                'telepon' => '08' .rand(100_000_000, 999_999_999),
                'jenis_kelamin' => rand(0,1) ? 'L' : 'P',
                'tanggal_masuk' => now()->subYears(rand(1,5))->subMonths(rand(6,36))->toDateString(),
                'departemen_id' => $data['departemen_id'],
                'jabatan_id' => $data['jabatan_id'],
                'gaji_pokok' => $data['gaji'],
                'tunjangan' => $data['tunjangan'],
                'status' => 'aktif',
                'bank' => ['BCA', 'Mandiri', 'BNI', 'BRI'][rand(0,3)],
                'no_rekening' => '1234' .rand(100_000, 999_999),
            ]);
        }
    }
}
