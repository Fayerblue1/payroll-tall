<?php

namespace App\Livewire\Karyawan;

use App\Models\Departemen;
use App\Models\Jabatan;
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

    // Properti MOdal form CRUD
    public $isFormModalOpen = false;
    public $karyawan_id, $nik, $nama, $email, $telepon, $jenis_kelamin;
    public $departemen_id, $jabatan_id, $tanggal_masuk, $status;
    public $bank, $no_rekening, $gaji_pokok, $tunjangan; 


    // Properti untuk modal detail
    public $isDetailModalOpen = false;
    public $karyawanDetail;

    // Dropdown Dinamis Untuk Jabatan
    public $jabatans_dropdown = [];

    public function updatedDepartemenId($value)
    {
        // Ambil datar jabatan yg HANYA milik departemen yang berkaitan
        $this->jabatans_dropdown = Jabatan::where('departemen_id', $value)->get();

        // Reset pilih jabatan dan gaji pokok
        $this->jabatan_id = null;
        $this->gaji_pokok = 0;
    }

    // AUto Fill gaji
    public function updatedaji($value)
    {
        if($value) {
            $jabatan = Jabatan::find($value);
            if($jabatan) {
                $this->gaji_pokok = $jabatan ->gaji_pokok;
            }
        }
    }

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

        // Ambil Semua departemen untuk dropdown filter
        $departemens_dropdown = Departemen::orderBy('nama', 'asc')->get();
        return view('livewire.karyawan.karyawan-index', compact('karyawans', 'departemens_dropdown'));
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

    public function closeFormModal()
    {
        $this->isFormModalOpen = false;
        $this->resetValidation();
        $this->resetInputForm();
    }

    public function resetInputForm()
    {
        $this->karyawan_id = null;
        $this->nik = '';
        $this->nama = '';
        $this->email = '';
        $this->telepon = '';
        $this->jenis_kelamin = '';
        $this->departemen_id = '';
        $this->jabatan_id = '';
        $this->tanggal_masuk = date('Y-m-d');
        $this->status = 'aktif';
        $this->bank = '';
        $this->no_rekening = '';
        $this->gaji_pokok = '';
        $this->tunjangan = '';
        $this->jabatans_dropdown = [];

    }

    // --- Fungsi CRUD ----
    public function create()
    {
        $this->resetInputForm();
        $this->isFormModalOpen = true;
    }

    public function edit($id)
    {
        $karyawan = Karyawan::FindOrFail($id);

        $this->karyawan_id = $karyawan->id;
        $this->nik = $karyawan->nik;
        $this->nama = $karyawan->nama;
        $this->email = $karyawan->email;
        $this->telepon = $karyawan->telepom;
        $this->jenis_kelamin = $karyawan->jenis_kelamin;
        $this->departemen_id = $karyawan->departemen_id;
        $this->jabatan_id = $karyawan->jabatan_id;
        $this->tanggal_masuk = $karyawan->tanggal_msauk;
        $this->status = $karyawan->status;
        $this->bank = $karyawan->bank;
        $this->no_rekening = $karyawan->no_rekening;
        $this->gaji_pokok = $karyawan->gaji_pokok;
        $this->tunjangan = $karyawan->tunjangan;


        $this->jabatans_dropdown = Jabatan::where('departemen_id',$this->departemen_id)->get();

        $this->isFormModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'nik' => 'required|unique:karyawan,nik,' . $this->karyawan_id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawan,email,' . $this->karyawan_id,
            'telepon' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'departemen_id' => 'required|exists:departemen,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'tanggal_masuk' => 'required|date',
            'status' => 'required|in:aktif,non-aktif',
            'bank' => 'required',
            'no_rekening' => 'required',
            'gaji_pokok' => 'required|integer|min:0',
            'tunjangan' => 'nullable|integer|min:0',
        ]);

        Karyawan::updateOrCreate(
            ['id' => $this->karyawan_id],
            [
                'nik' => $this->nik,
                'nama' => $this->nama,
                'email' => $this->email,
                'telepon' => $this->telepon,
                'jenis_kelamin' => $this->jenis_kelamin,
                'departemen_id' => $this->departemen_id,
                'jabatan_id' => $this->jabatan_id,
                'tanggal_masuk' => $this->tanggal_masuk,
                'status' => $this->status,
                'bank' => $this->bank,
                'no_rekening' => $this->no_rekening,
                'gaji_pokok' => $this->gaji_pokok,
                'tunjangan' => $this->tunjangan,

            ]
        );

        session()->flash('message', $this->karyawan_id ? 'Data Karyawan berhasil di perbarui.' : 'Data karyawan berhasi di buat');
        $this->closeFormModal();
    }

    public function delete($id)
    {
        Karyawan::findOrFail()->delete();
        //$karyawan = Karyawan::findOrFail($id);
        //$karyawaan->delete();

        session()->flash('message', 'DAta Karyawan Berhasil di hapus');
    }
}
