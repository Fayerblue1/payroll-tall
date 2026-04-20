<?php

namespace App\Livewire\Master;

use App\Models\Departemen;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Manajemen Departemen')]
class DepartemenIndex extends Component
{
    use WithPagination;
    //Properti Form
    public $departemen_id, $kode, $nama;

    //Properti UI
    public $isOpen = false;
    public $search = '' ;

    //Riset pagination ketika melakukan search
    public function updatingSearch()
    {
        $this -> resetPage();
    }

    public function render()
    {
        $departemens = Departemen::where('nama', 'like' , '%' . $this->search . '%')
        ->orWhere('kode' , 'like', '%' . $this->search . '%' )
        ->orderBy('id' , 'desc')
        ->paginate(10);
        return view('livewire.master.departemen-index', compact('departemens'));
    }

    //Mebuka modal 
    public function openModal()
    {
        $this->isOpen = true;
    }
    //Menutup Modal
    public function closeModal()
    {
        $this->isOpen = false;
    }
    //Reset Form
    public  function resetInputFields()
    {
        $this->departemen_id = null;
        $this->kode = '';
        $this->nama = '';
        
    }

    //MEmbuka modal untuk create
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    //create dan update Data
    public function store()
    {
        $this->validate([
            'kode' => 'required|unique:departemen,kode,' . $this->departemen_id,
            'nama' => 'required|string|max:255'
        ]);

        Departemen::updateOrCreate(
            ['id' => $this->departemen_id],

            [
                'kode' => strtoupper($this->kode), //Memastika kode yang disimpan ke DM adalah Kapital
                'nama' => $this->nama
            ]
        );
        
        session()->flash('message', $this->departemen_id ? 'Data Departemen berhasil Di perbarui' : 'Data Departemen Berhasil dibuat');

        $this->closeModal();
        $this->resetInputFields();
    }

    //MEmbuka modal untuk Edit
    public function edit($id)
    {
        $departemen = Departemen::findOrFail($id);
        $this->departemen_id = $id;
        $this->kode = $departemen->kode;
        $this->nama = $departemen->nama;

        $this->openModal();
    }

    //Menghapus Data
    public function delete($id)
    {
        $departemen = Departemen::withCount('Jabatan')->findOrFail($id);

        if($departemen->jabatan_count > 0) {
            session()->flash('error', 'Gagal! Departemen masih Digunakan oleh data jabatan.');
            return;
        }
        
        $departemen->delete();
        session()->flash('message', 'Data Departemen Berhasi Di hapus');
    }
}
