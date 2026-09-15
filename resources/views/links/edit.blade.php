<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le lien court') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        Édition du lien : {{ url($shortLink->short_code) }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Mettez à jour l'URL de destination pour ce lien court.
                    </p>
                </header>

                <form method="POST" action="{{ route('links.update', $shortLink) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="original_url" value="URL d'origine" />
                        <x-text-input id="original_url" name="original_url" type="url" class="mt-1 block w-full" value="{{ old('original_url', $shortLink->original_url) }}" required />
                        <x-input-error :messages="$errors->get('original_url')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Enregistrer les modifications</x-primary-button>
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
