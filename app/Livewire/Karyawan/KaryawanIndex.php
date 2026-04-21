<?php

namespace App\Livewire\Karyawan;

use App\Models\Karyawan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;


#[Layout('components.layouts.app')]
#[Title('Data Karyawan')]
class KaryawanIndex extends Component
{
    use WithPagination;

    public $search = '' ;

    // Properti untuk modal detail
    public $isDetailModalOpen = false;
    public $karyawanDetail;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $karyawans = Karyawan::with(['departemen', 'jabatan'])
            ->where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('nik', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.karyawan.karyawan-index', compact('karyawans'));
    }

    public function showDetail($id)
    {
        // Ambil Data karyawan dan relasi
        $this->karyawanDetail = Karyawan::with(['departemen', 'jabatan'])->findOrFail($id);
        $this->isDetailModalOpen = true;
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->karyawanDetail = null;
    }

    public function alertNotFinish()
    {
        session()->flash('info', 'Sedang dalam Tahap Pengerjaan...');
    }
}
