<div 
{{ $attributes->merge(['md:grid md:grid-cols-2 md:gap-3']) }}
>
    <x-general.section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-general.section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        @if(isset($content))

            {{ $content }}
        @endif
        <form 
            method="{{ $method }}" 
            action="{{ $action }}" {!! (isset($formencoding) && !is_null($formencoding) ? 'enctype="'.$formencoding.'"':'') !!}>
            @csrf
            @if (isset($otherMethod))
                {{ $otherMethod }}
            @endif
            <div
                class="px-4 py-5 bg-white sm:p-6 shadow {{ isset($actions) ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md' }}">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div
                    class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
