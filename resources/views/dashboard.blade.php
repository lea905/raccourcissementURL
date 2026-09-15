<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50">
                    {{ session('status') }}
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        Créer un nouveau lien court
                    </h2>
                </header>

                <form method="POST" action="{{ route('links.store') }}" class="mt-6 space-y-6 max-w-xl">
                    @csrf
                    <div>
                        <x-input-label for="original_url" value="URL d'origine" />
                        <x-text-input id="original_url" name="original_url" type="url" class="mt-1 block w-full" placeholder="https://www.exemple.com" required />
                        <x-input-error :messages="$errors->get('original_url')" class="mt-2" />
                    </div>

                    <x-primary-button>Raccourcir l'URL</x-primary-button>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Lien d'origine</th>
                            <th scope="col" class="px-6 py-3">Lien court</th>
                            <th scope="col" class="px-6 py-3">Clics</th>
                            <th scope="col" class="px-6 py-3">Dernière visite</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($links as $link)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 max-w-xs truncate" title="{{ $link->original_url }}">
                                    {{ $link->original_url }}
                                </td>
                                <td class="px-6 py-4 font-medium text-blue-600">
                                    <a href="{{ url($link->short_code) }}" target="_blank" class="hover:underline">
                                        {{ url($link->short_code) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $link->clicks_count }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $link->last_visited_at ? \Carbon\Carbon::parse($link->last_visited_at)->diffForHumans() : 'Jamais' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end space-x-8">

                                        <button onclick="copyLink('{{ url($link->short_code) }}')" title="Copier le lien" class="text-gray-400 hover:text-blue-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('links.edit', $link) }}" title="Modifier" class="text-gray-400 hover:text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce lien ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Supprimer" class="text-gray-400 hover:text-red-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Vous n'avez pas encore généré de lien court.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $links->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(function() {
                alert('Lien copié dans le presse-papiers !');
            }).catch(function(err) {
                console.error('Erreur lors de la copie', err);
            });
        }
    </script>
</x-app-layout>
