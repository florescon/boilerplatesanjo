<form autocomplete="off" method="POST" action="{{ route('admin.material.updateStock', ['id' => $material->id]) }}">
{{method_field('patch')}}
@csrf

    <input type="number" step="any" name="stock" class="form-control" placeholder="{{ $material->name ? $material->name.'.'  :'' }} Enter para guardar actual">

</form>