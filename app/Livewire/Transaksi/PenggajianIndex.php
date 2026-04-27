<?php

namespace App\Livewire\Transaksi;

use App\Models\Karyawan;
use App\Models\Penggajian;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Proses Penggajian')]
class PenggajianIndex extends Component
{

    use WithPagination;

    public $bulan;
    public $tahun;
    public $search = '';


    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset page jika user mengganti bulan/tahun (filter)
    public function updatedBulan()
    {
        $this->resetPage();
    }
    public function updatedTahun()
    {
        $this->resetPage();
    }
    public function render()
    {
        $penggajians = Penggajian::with('karyawan.departemen', 'karyawan.jabatan')
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->when($this->search, function ($query) {
                $query->whereHas('karyawan', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('livewire.transaksi.penggajian-index', compact('penggajians'));
    }

    // Fungsi untuk membuat detail penggajian
    public function generatePayroll()
    {
        // 1. Cek apakah sudah ada penggajian untuk bulan/tahun ini
        $sudahAda = Penggajian::where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->exists();

        if ($sudahAda) {
            session()->flash('error', 'Gagal! Gaji untuk priode ini' . $this->bulan . ' /' . $this->tahun . 'Sudah pernah di proses');
            return;
        }

        // 2. Ambil semua karyawan aktif
        $karyawans = Karyawan::where('status', 'aktif')->get();
        if ($karyawans->isEmpty()) {
            session()->flash('error', 'Gagal! Tidak ada Karyawan aktif untuk di gaji.');
            return;
        }

        // 3. Proses Penggajian untuk setiap karyawan (Looping/massal)
        $count = 0;
        foreach ($karyawans as $karyawan) {
            $potongan = $karyawan->gaji_pokok * 0.03;
            $total_gaji = ($karyawan->gaji_pokok + $karyawan->tunjangan) - $potongan;
            Penggajian::create([
                'karyawan_id' => $karyawan->id,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
                'tanggal_proses' => date('Y-m-d'),
                'gaji_pokok' => $karyawan->gaji_pokok,
                'tunjangan' => $karyawan->tunjangan,
                'potongan' => $potongan,
                'total_gaji' => $total_gaji
            ]);
            $count++;
        }
        session()->flash('succes', 'Berhasil! Gaji untuk Priode' . $this->bulan . '/' . 'telah diproses.Total Karyawan yang di gaji' . $count);
    }


    public function delete($id)
    {
        Penggajian::findOrFail($id)->delete();
        session()->flash('message', 'Data Pengajian Berhasil dihapus');
    }
}
