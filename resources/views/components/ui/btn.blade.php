@props(['type' => 'button'])
<button type="{{ $type }}" {{ $attributes->merge([
  'class' => 'inline-flex items-center justify-center rounded-2xl px-4 py-2 font-semibold
              bg-gray-900 text-white hover:bg-gray-800 active:scale-[.98] shadow-sm transition
              ring-1 ring-gray-900/5'
]) }}>
  {{ $slot }}
</button>
