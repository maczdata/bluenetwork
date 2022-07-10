 <x-general.delete-dialog>
     <x-slot name="title">
         Approve {{ ucfirst($type) }} verification
     </x-slot>
     <x-slot name="alert">
         Do you want to approve this {{ $type }}?
     </x-slot>
     <x-slot name="actions">
         <button wire:click="approve" type="button"
             class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
             Approve
         </button>
         <button wire:click="$emit('closeModal')" type="button"
             class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
             Cancel
         </button>
     </x-slot>
</x-general.action-section>
