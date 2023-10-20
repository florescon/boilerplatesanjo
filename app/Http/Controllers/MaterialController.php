<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Out;
use App\Models\MaterialHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\Material\MaterialUpdated;
use PDF;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.material.index');
    }

    public function create()
    {
        return view('backend.material.create');
    }

    public function out()
    {
        return view('backend.material.out');
    }

    public function recordsMaterial()
    {
        return view('backend.material.records');
    }

    public function recordsHistoryMaterial()
    {
        return view('backend.material.records-history');
    }

    public function recordsHistoryMaterialGroup()
    {
        return view('backend.material.records-history-group');
    }

    public function deleted()
    {
        return view('backend.material.deleted');
    }

    public function ticket_out(Out $out)
    {
        $pdf = PDF::loadView('backend.material.ticket-out',compact('out'))->setPaper([0, 0, 2385.98, 296.85], 'landscape');

        return $pdf->stream();
    }

    public function edit(Material $material)
    {
        return view('backend.material.edit-material', compact('material'));
    }

    public function print(Material $material)
    {
        return view('backend.material.print-material', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'part_number' => ['min:3', 'max:30', 'regex:/^\S*$/u', Rule::unique('materials')->ignore($material->id)],
            'name' => ['required', 'min:3', 'max:40'],
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'acquisition_cost' => ['nullable', 'numeric', 'sometimes', 'regex:/^\d+(\.\d{1,2})?$/'],
            'unit_id' => ['numeric', Rule::requiredIf(!$material->unit_id)],
            'color_id' => ['numeric', Rule::requiredIf(!$material->color_id)],
            'size_id' => ['nullable', 'sometimes', 'numeric'],
            'description' => ['min:5', 'max:100', 'nullable'],
            'vendor_id' => ['numeric', Rule::requiredIf(!$material->vendor_id)],
            'family_id' => ['numeric', Rule::requiredIf(!$material->family_id)],
        ]);

        $materialUpdated = $material->update($validated);

        event(new MaterialUpdated($material));

        return redirect()->route('admin.material.edit', $material->id)->withFlashSuccess(__('The feedstock was successfully updated.'));
    }

    public function updateStock(Request $request, Material $material)
    {
        $this->validate($request, [
            'stock' => 'numeric',
        ]);

        if($request->stock > 0){
            $material->increment('stock', abs($request->stock));
        }
        else{
            $material->decrement('stock', abs($request->stock));
        }

        return redirect()->back()
          ->withFlashSuccess('Materia prima actualizada con éxito');
    }

    public function short_ticket(Request $request, Material $material)
    {
        $this->validate($request, [
            'quantity' => 'numeric',
        ]);

        $quantity = $request->quantity ?? 0;

        $pdf = PDF::loadView('backend.material.ticket.short',compact('material', 'quantity'))->setPaper([0, 0, 888.98, 600.85], 'portrait');

        return $pdf->stream();
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Material::with('unit', 'color', 'size')
        ->where('name', 'like', '%' . $search . '%')->orWhere('part_number', 'like', '%' . $search . '%')->orderBy('name')
        ->paginate(5);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}