{{-- 
  | Custom Modal Component
  | Penggunaan: <x-custom-modal :id="$id" :title="$title" :size="$size">
  |   Modal content goes here
  | </x-custom-modal>
--}}

@props([
    'id' => 'modal-' . uniqid(),
    'title' => 'Modal',
    'size' => 'md', // 'sm', 'md', 'lg', 'xl'
    'closeButton' => true,
    'backdrop' => true
])

@php
    $sizeClass = match($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        'full' => 'max-w-4xl',
        default => 'max-w-md'
    };
@endphp

<div 
    class="fixed inset-0 {{ $backdrop ? 'bg-black bg-opacity-50 backdrop-blur-sm' : '' }} hidden items-center justify-center z-[9999] p-4" 
    id="{{ $id }}-modal-bg"
    data-modal="{{ $id }}"
    onclick="event.target === this && CustomModal.close('{{ $id }}')"
>
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-2xl {{ $sizeClass }} w-full transform transition-all duration-300 flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="sticky top-0 px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
            @if($closeButton)
                <button 
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors hover:bg-gray-100 dark:hover:bg-gray-800 p-1 rounded-lg"
                    onclick="CustomModal.close('{{ $id }}')"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto px-6 py-4 text-gray-700 dark:text-gray-300">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
    // Extend CustomModal object dengan show/close methods untuk Blade modals
    if (window.CustomModal) {
        CustomModal.showModal = function(id) {
            const modal = document.querySelector(`#${id}-modal-bg`);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.querySelector('.rounded-lg').style.transform = 'scale(1)';
                }, 0);
            }
        };
        
        CustomModal.close = function(id) {
            const modal = document.querySelector(`#${id}-modal-bg`);
            if (modal) {
                modal.querySelector('.rounded-lg').style.transform = 'scale(0.95)';
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            }
        };
    }
</script>
