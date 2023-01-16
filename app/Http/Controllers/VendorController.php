<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.vendor.index');
    }

    public function create()
    {
        return view('backend.vendor.create');
    }

    public function edit(Vendor $vendor)
    {
        return view('backend.vendor.edit-vendor', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3', 'max:100'],
            'email' => ['required', 'min:3', 'max:50', Rule::unique('vendors')->ignore($vendor->id)],
            'phone' => ['nullable', 'numeric', 'sometimes', 'regex:/^\d+(\.\d{1,2})?$/'],
            'city_id' => ['numeric', Rule::requiredIf(!$vendor->city_id)],
            'address' => ['nullable', 'min:3', 'max:100'],
            'rfc' => ['nullable', 'min:3', 'max:50'],
            'description' => ['min:5', 'max:200', 'nullable'],
        ]);

        $vendorUpdated = $vendor->update($validated);

        // event(new VendorUpdated($vendor));

        return redirect()->route('admin.vendor.edit', $vendor->id)->withFlashSuccess(__('The vendor was successfully updated.'));
    }

    public function associates(Vendor $vendor)
    {
        $link = route('admin.vendor.index');
        $attribute = $vendor;
        $nameModel = 'Vendor';
        return view('backend.product.associates-subproducts', compact('attribute', 'link', 'nameModel'));
    }

    public function associates_materia(Vendor $vendor)
    {
        $link = route('admin.vendor.index');
        $attribute = $vendor;
        $nameModel = 'Vendor';
        return view('backend.material.associates-material', compact('attribute', 'link', 'nameModel'));
    }

    public function deleted()
    {
        return view('backend.vendor.deleted');
    }

    public function select2LoadMore(Request $request)
    {
        $search = $request->get('search');
        $data = Vendor::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->orderBy('name')->paginate(15);
        return response()->json(['items' => $data->toArray()['data'], 'pagination' => $data->nextPageUrl() ? true : false]);
    }
}
