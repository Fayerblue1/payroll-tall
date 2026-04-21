<?php

namespace App\Livewire\Master;

use App\Models\Departemen;
use App\Models\Jabatan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('MAnajemenn Jabatan')]
class JabatanIndex extends Component
{
    use WithPagination;

    
    //properti From
    public $jabatan_id, $departemen_id, $nama, $gaji_pokok;

    //properti UI
    public $isOpen = false;
    public $search = '' ;

    public function render()
    {
        //Query dengan relasi departemen

        $jabatans = Jabatan::with('departemen')
            ->where('nama', 'like', '%' . $this->search . '%' )
            ->orWhereHas('departemen' , function ($query){
                $query->where('nama', 'like', '%' . $this->search . '%' );
            }) 
            ->orderBy('id', 'desc')
            ->paginate(10);

            //Mengambil nama departemen untuk Drop down
            $departemens = Departemen::orderBy('nama', 'asc')->get();

            return view('livewire.master.jabatan-index', compact('jabatans' , 'departemens'));
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
        $jabatan_id = null;
        $this->departemen_id = '';
        $this->nama = '';
        $this->gaji_pokok = '';
        
    }

    //MEmbuka modal untuk create
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    //Riset pagination ketika melakukan search
    public function updatingSearch()
    {
        $this -> resetPage();
    }

    public function store ()
    {
        $this->validate([
            'departemen_id' => 'required|exists:departemen,id',
            'nama' => 'required|string|max:100',
            'gaji_pokok' => 'required|numeric|min:0',
        ]);

        Jabatan::updateOrCreate(
            ['id' => $this ->departemen_id],
            [
                'departemen_id' => $this ->departemen_id,
                'nama' => $this ->nama,
                'gaji_pokok' => $this ->gaji_pokok,
            ] 
        );
        session()->flash('message', $this->jabatan_id ? 'Jabaan Berhasil Diperbarui.' : 'Jabatan Berhasil Dibuat');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $this ->jabatan_id = $id;
        $this -> departemen_id = $jabatan->departemen_id;
        $this -> nama = $jabatan->nama;
        $this -> gaji_pokok = $jabatan->gaji_pokok;

        $this->openModal();
    }

    public function delete($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            $jabatan->delete();
            session()->flash('messsage', 'Jabatan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getcode() === 23000) { // Kode error untuk integrity constraint violation
                session()->flash('error', 'Gagal! Jabatan masih di gunakan oleh data lain.');
            }else {
                 session()->flash('error', 'Terjadi kea=salahan saaat menghapus jabatan');
            }
        }
    }
}
