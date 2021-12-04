<?php

namespace App\Http\Livewire\Backend\Document;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\File;
use App\Models\Document;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Http\Livewire\Backend\DataTable\WithBulkActions;
use App\Http\Livewire\Backend\DataTable\WithCachedRows;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Exports\DocumentsExport;
use Excel;

class DocumentTable extends Component
{
    use Withpagination, WithBulkActions, WithCachedRows, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage',
        'deleted' => ['except' => FALSE],
    ];

    public $perPage = '10';

    public $sortField = 'title';
    public $sortAsc = true;
    
    public $searchTerm = '';

    protected $listeners = ['delete' => '$refresh', 'restore' => '$refresh'];

    public $title, $file_emb, $file_dst, $email, $comment, $is_enabled, $is_disabled;

    public $created, $updated, $deleted, $selected_id;

    protected $rules = [
        'title' => 'required|min:3',
        'file_dst' => 'sometimes|mimetypes:application/octet-stream|max:2048',
        'file_emb' => 'sometimes|mimetypes:application/vnd.ms-office|max:2048',
    ];

    public function getRowsQueryProperty()
    {
        
        return Document::query()
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('comment', 'like', '%' . $this->searchTerm . '%');
            })
            ->when($this->deleted, function ($query) {
                $query->onlyTrashed();
            })
            ->when($this->sortField, function ($query) {
                $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->rowsQuery->paginate($this->perPage);
        });
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedSearchTerm()
    {
        $this->page = 1;
    }

    public function updatedPerPage()
    {
        $this->page = 1;
    }

    public function updatedDeleted()
    {
        $this->page = 1;
        $this->selectAll = false;
        $this->selectPage = false;
        $this->selected = [];
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function clear()
    {
        $this->searchTerm = '';
        $this->page = 1;
        $this->perPage = '10';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        // $yas = $this->file_emb->getMimeType();
        // dd($yas);

        $validatedData = $this->validate();

        if($this->file_dst || $this->file_emb) {
            $date = date("Y-m-d");
            $documentModel = new Document;
            $fileDST = $this->file_dst ? $this->file_dst->store("documents/".$date,'public') : null;
            $fileEMB = $this->file_emb ? $this->file_emb->store("documents/".$date,'public') : null;

            $documentModel->title = $this->title;
            $documentModel->file_dst = $this->file_dst ? $fileDST : null;
            $documentModel->file_emb = $this->file_emb ? $fileEMB : null;
            $documentModel->comment = $this->comment ?? null;
            $documentModel->save();
        }

        $this->resetInputFields();
        $this->emit('documentStore');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Created'), 
        ]);
    }

    public function edit($id)
    {
        $record = Document::findOrFail($id);
        $this->selected_id = $id;
        $this->title = $record->title;
        $this->file_dst = $record->file_dst;
        $this->file_emb = $record->file_emb;
        $this->comment = $record->comment;
    }

    public function show($id)
    {
        $record = Document::withTrashed()->findOrFail($id);
        $this->title = $record->title;
        $this->file_dst = $record->file_dst_label;
        $this->file_emb = $record->file_emb_label;
        $this->comment = $record->comment;
        $this->is_enabled = $record->is_enabled_document;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'title' => 'required|min:3',
            'file_dst' => 'sometimes|nullable|mimetypes:application/octet-stream|max:2048',
            'file_emb' => 'sometimes|nullable|mimetypes:application/vnd.ms-office|max:2048',
            'comment' => 'sometimes',
        ]);

        if ($this->selected_id) {
            $record = Document::find($this->selected_id);
            $record->update([
                'title' => $this->title,
                'file_dst' => $this->file_dst ? $this->file_dst->store("documents",'public') : null,
                'file_emb' => $this->file_emb ? $this->file_emb->store("documents",'public') : null,
                'comment' => $this->comment
            ]);
            $this->resetInputFields();
        }

        $this->emit('documentUpdate');

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Updated'), 
        ]);
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->file_emb = '';
        $this->file_dst = '';
        $this->comment = '';
    }

    public function export()
    {
        return response()->streamDownload(function () {
            echo $this->selectedRowsQuery->toCsv();
        }, 'document-list.csv');
    }

    private function getSelectedDocuments()
    {
        return $this->selectedRowsQuery->get()->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }
    public function exportMaatwebsite($extension)
    {   
        abort_if(!in_array($extension, ['csv', 'xlsx', 'pdf', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
        return Excel::download(new DocumentsExport($this->getSelectedDocuments()), 'documents.'.$extension);
    }

    public function restore($id)
    {
        if($id){
            $restore_color = Document::withTrashed()
                ->where('id', $id)
                ->restore();
        }

      $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Restored'), 
        ]);
    }

    public function enable(Document $document)
    {
        if($document)
            $document->update([
                'is_enabled' => true
            ]);

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Enabled'), 
        ]);
    }

    public function disable(Document $document)
    {
        if($document)
            $document->update([
                'is_enabled' => false
            ]);

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Disabled'), 
        ]);
    }

    public function delete(Document $document)
    {
        if($document)
            $document->delete();

       $this->emit('swal:alert', [
            'icon' => 'success',
            'title'   => __('Deleted'), 
        ]);
    }

    public function render()
    {
        return view('backend.document.table.document-table', [
            'documents' => $this->rows,
        ]);
    }
}
