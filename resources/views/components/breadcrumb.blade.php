@php($lastIndex = count($items) - 1)
<nav class="breadcrumb" aria-label="Breadcrumb">
  <ol class="breadcrumb__list">
    @foreach($items as $index => $item)
      <li class="breadcrumb__item">
        @if(!empty($item['url']) && $index !== $lastIndex)
          <a href="{{ $item['url'] }}" class="breadcrumb__link">{{ $item['label'] }}</a>
        @else
          <span class="breadcrumb__current">{{ $item['label'] }}</span>
        @endif
        @if($index !== $lastIndex)
          <span class="breadcrumb__separator">/</span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>
@push('styles')
<style>
  .breadcrumb {line-height:1.4; }
  .breadcrumb__list { list-style:none; margin:0; padding:0; display:flex; flex-wrap:nowrap; align-items:center; gap:.5rem; }
  .breadcrumb__item { display:inline-flex; align-items:center; white-space:nowrap; }
  .breadcrumb__item:last-child .breadcrumb__separator { display:none; }
  .breadcrumb__separator { margin:0 .25rem; color:#999; }
  .breadcrumb__link { color: var(--color-primary,#2563eb); text-decoration:none; }
  .breadcrumb__link:hover { text-decoration:underline; }
  .breadcrumb__separator { margin:0 .5rem; color:#999; }
  .breadcrumb__current { color:#555; font-weight:500; }
</style>
@endpush