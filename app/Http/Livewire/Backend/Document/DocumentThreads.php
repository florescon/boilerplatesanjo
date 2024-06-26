<?php

namespace App\Http\Livewire\Backend\Document;

use Livewire\Component;
use App\Models\Document;
use App\Models\DocumentThread;
use App\Models\Thread;
use DB;

class DocumentThreads extends Component
{
    public $document;
    public $threads;

    public $selected_threads = [];

    protected $listeners = ['emitRender' => '$refresh'];

    public function mount(Document $document)
    {
        $document->load('doc_threads');
        $this->document = $document;
        $this->threads = Thread::all();
    }

    public function save()
    {
        foreach($this->selected_threads as $selected){
            $documentThread = DocumentThread::firstOrNew(['document_id' => $this->document->id,  'thread_id' => $selected]);
            $documentThread->save();
        }

        return redirect()->route('admin.document.threads', $this->document->id);
    }

    public function removeThead($documentId)
    {
        $delete = DB::table('document_threads')->where('id', $documentId)->delete();

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);

        $this->emit('emitRender');
    }

    public function closeTab()
    {
        $this->emit('closeBrowserTab');
    }

    public function render()
    {
        return view('backend.document.livewire.document-threads');
    }
}
