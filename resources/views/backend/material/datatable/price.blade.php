<x-forms.patch :action="route('admin.material.updatePrice', $material)">

    <input type="number" step="any" name="price" class="form-control" placeholder="{{ $material->name ? $material->name.'.'  :'' }} Enter para guardar actual">

</x-forms.patch>