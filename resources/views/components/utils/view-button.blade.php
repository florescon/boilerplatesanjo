@props(['href' => '#', 'permission' => false])

<x-utils.link :href="$href" target="_blank" class="btn btn-info btn-sm" icon="fas fa-search" :text="__('View')" permission="{{ $permission }}" />
