@props(['name' => 'quantity', 'value' => 1, 'min' => 1])

<div class="flex items-stretch rounded-2xl overflow-hidden ring-1 ring-gray-300">
  <button type="button" class="px-3 select-none"
          onclick="const i=this.nextElementSibling;i.stepDown();i.dispatchEvent(new Event('change'))">−</button>
  <input type="number" name="{{ $name }}" min="{{ $min }}" value="{{ $value }}"
         class="w-16 text-center outline-none border-x border-gray-200" />
  <button type="button" class="px-3 select-none"
          onclick="const i=this.previousElementSibling;i.stepUp();i.dispatchEvent(new Event('change'))">＋</button>
</div>
