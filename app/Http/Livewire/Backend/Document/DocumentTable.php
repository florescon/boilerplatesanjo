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

    public $perPage = '8';

    public $sortField = 'title';
    public $sortAsc = true;
    
    public $searchTerm = '';

    public $image;

    public $imageShow;

    protected $listeners = ['delete' => '$refresh', 'restore' => '$refresh'];

    public $title, $file_emb, $file_dst, $email, $comment, $is_enabled, $is_disabled;

    public $file_dst_label, $file_emb_label, $file_pdf_label, $file_image_label;

    public $stitches = 0;

    public $width, $height, $file_pdf;

    public $photoStatus;

    public $created, $updated, $deleted, $selected_id;

    protected $rules = [
        'title' => 'required|min:3|max:60',
        'file_dst' => 'nullable|mimetypes:application/octet-stream|max:5048',
        'file_emb' => 'nullable|mimetypes:application/vnd.ms-office|max:5048',
        'file_pdf' => 'nullable|nullable|mimes:pdf|max:5048',
        'width' => 'sometimes|integer|min:1|max:500',
        'height' => 'sometimes|integer|min:1|max:500',
        'comment' => 'nullable|max:300',
        'stitches' => 'sometimes|integer|min:1|max:9999999',
        'image' => 'nullable|image|max:5048',
    ];

    protected $messages = [
        'file_dst.mimetypes' => 'Formato incorrecto',
        'file_emb.mimetypes' => 'Formato incorrecto',
        'file_pdf.mimetypes' => 'Formato incorrecto',
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
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedDeleted()
    {
        $this->resetPage();
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
        $this->resetPage();
        $this->perPage = '8';
    }

    public function createmodal()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        // dd($this->file_dst);
        // $yas = $this->file_emb->getMimeType();
        // dd($yas);

        $this->validate();

        if($this->file_dst || $this->file_emb) {
            $date = date("Y-m-d");
            $documentModel = new Document;
            $fileDST = $this->file_dst ? $this->file_dst->store("documents/".$date,'public') : null;
            $fileEMB = $this->file_emb ? $this->file_emb->store("documents/".$date,'public') : null;
            $filePDF = $this->file_pdf ? $this->file_pdf->store("documents/".$date,'public') : null;
    
            if($this->image){
                $imageName = $this->image->store("documents/".$date,'public');
            }

            $documentModel->title = $this->title;
            $documentModel->file_dst = $this->file_dst ? $fileDST : null;
            $documentModel->file_emb = $this->file_emb ? $fileEMB : null;
            $documentModel->file_pdf = $this->file_pdf ? $filePDF : null;
            $documentModel->image = $this->image ? $imageName : null;
            $documentModel->comment = $this->comment ?? null;
            $documentModel->width = $this->width ?? null;
            $documentModel->height = $this->height ?? null;
            $documentModel->stitches = $this->stitches ?? 0;
            $documentModel->save();

            $this->emit('swal:alert', [
                'icon' => 'success',
                'title'   => __('Created'), 
            ]);
        }
        else {
            $this->emit('swal:alert', [
                'icon' => 'warning',
                'title'   => 'No puedes crear algo en blanco :)', 
            ]);
        }

        $this->resetInputFields();
        $this->emit('documentStore');
    }

    public function edit($id)
    {
        $record = Document::findOrFail($id);
        $this->selected_id = $id;
        $this->title = $record->title;

        $this->file_dst_label = $record->file_dst_label;
        $this->file_emb_label = $record->file_emb_label;
        $this->file_pdf_label = $record->file_pdf_label;

        $this->file_image_label = $record->image;

        $this->file_dst = null;
        $this->file_emb = null;
        $this->file_pdf = null;

        $this->image = null;
        $this->comment = $record->comment;
        $this->stitches = $record->stitches;
        $this->width = $record->width;
        $this->height = $record->height;
    }

    public function show($id)
    {
        $record = Document::withTrashed()->findOrFail($id);
        $this->title = $record->title;
        $this->file_dst = $record->file_dst_label;
        $this->file_emb = $record->file_emb_label;
        $this->file_pdf = $record->file_pdf_label;
        $this->stitches = number_format($record->stitches, 0, '', ',');        
        $this->comment = $record->comment;
        $this->width = $record->width;
        $this->height = $record->height;
        $this->imageShow = $record->image;
        $this->is_enabled = $record->is_enabled_document;
        $this->created = $record->created_at;
        $this->updated = $record->updated_at;
    }

    public function update()
    {
        $rules = [
            'selected_id' => 'required|numeric',
            'title' => 'required|min:3',
            'comment' => 'sometimes|max:300',
            'width' => 'sometimes|integer|min:1|max:500',
            'height' => 'sometimes|integer|min:1|max:500',
            'stitches' => 'sometimes|integer|min:1|max:9999999',
        ];

        if ($this->file_dst) {
            $rules['file_dst'] = 'sometimes|nullable|mimetypes:application/octet-stream|max:5048';
        }

        if ($this->file_emb) {
            $rules['file_emb'] = 'sometimes|nullable|mimetypes:application/vnd.ms-office|max:5048';
        }

        if ($this->file_pdf) {
            $rules['file_pdf'] = 'sometimes|nullable|mimetypes:application/pdf|max:5048';
        }

        if ($this->image) {
            $rules['image'] = 'sometimes|image|max:5048';
        }

        $this->validate($rules);

        if ($this->selected_id) {
            $record = Document::find($this->selected_id);
            $record->update([
                'title' => $this->title,
                'file_dst' => $this->file_dst ? $this->file_dst->store("documents", 'public') : $record->file_dst,
                'file_emb' => $this->file_emb ? $this->file_emb->store("documents", 'public') : $record->file_emb,
                'file_pdf' => $this->file_pdf ? $this->file_pdf->store("documents", 'public') : $record->file_pdf,
                'image' => $this->image ? $this->image->store("documents", 'public') : $record->image,
                'comment' => $this->comment,
                'width' => $this->width,
                'height' => $this->height,
                'stitches' => $this->stitches,
            ]);

            $this->resetInputFields();
        }

        $this->emit('documentUpdate');

        $this->emit('swal:alert', [
            'icon' => 'success',
            'title' => __('Updated'),
        ]);
    }


    private function resetInputFields()
    {
        $this->title = '';
        $this->file_emb = '';
        $this->file_dst = null;
        $this->file_pdf = null;
        $this->comment = '';
        $this->width = '';
        $this->height = '';
        $this->image = null;
        $this->stitches = 0;
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
        abort_if(!in_array($extension, ['csv', 'xlsx', 'html', 'xls', 'tsv', 'ids', 'ods']), Response::HTTP_NOT_FOUND);
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

    public function removeImage()
    {
        $this->image = '';
    }

    public function removeDST()
    {
        $this->file_dst = '';
        $this->resetValidation();
        $this->emit('fileDstRemoved');
    }

    public function removeEMB()
    {
        $this->file_emb = '';
        $this->resetValidation();
        $this->emit('fileEmbRemoved');
    }

    public function removePDF()
    {
        $this->file_pdf = '';
        $this->resetValidation();
        $this->emit('filePdfRemoved');
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
