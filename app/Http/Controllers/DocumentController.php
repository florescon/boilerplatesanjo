<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.document.index');
    }

    public function print(Document $document)
    {
        $document->load('doc_threads');
        return view('backend.document.print', compact('document'));
    }

    public function threads(Document $document)
    {
        return view('backend.document.threads', compact('document'));
    }

    public function download_dst(Document $document)
    {
        $headers = [
          'Content-Type' => 'mimetypes:application/octet-stream',
        ];
  
        return Storage::disk('public')->download($document->file_dst, $document->title.'.DST', $headers);
    }

    public function download_emb(Document $document)
    {
        $headers = [
          'Content-Type' => 'mimetypes:mimetypes:application/vnd.ms-office',
        ];
  
        return Storage::disk('public')->download($document->file_emb, $document->title.'.EMB', $headers);
    }

    public function download_pdf(Document $document)
    {
        $headers = [
          'Content-Type' => 'mimetypes:mimetypes:application/pdf',
        ];
  
        return Storage::disk('public')->download($document->file_pdf, $document->title.'.PDF', $headers);
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Document::select(['id', 'title', 'comment'])->where('title', 'like', '%' . $search . '%')->orderBy('title')->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
