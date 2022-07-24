<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <a class="flex items-center hover:text-blue-500 hover:underline transition duration-300 mt-4 ml-4" href="#" onClick="history.go(-1)">
                    <svg class="rotate-180 h-4" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5l7 7-7 7"></path>
                    </svg>
                    <p class="ml-2  text-xs  " >Return </p>

                </a>
                <form wire:submit="addEditor" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">
                    <div class="mt-3">
                        <label> Editor Email: </label>

                        <input
                            wire:model="editorEmail"
                            placeholder="email"
                            required=""
                            type="email"
                            class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                        />
                        {{--                    <label> Choose a a table form the list:--}}
                        {{--                        <select wire:model="databaseId">--}}
                        {{--                            @foreach($databases as $database)--}}
                        {{--                                <option value="{{$database['id']}}">{{$database['name']}}</option>--}}
                        {{--                            @endforeach--}}
                        {{--                        </select >--}}
                        {{--                    </label>--}}
                        <button

                            type="submit"
                            class="mt-3 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide transition duration-200 rounded shadow-md md:w-auto bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                        >
                            Add editor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
                    <thead>
                    <tr>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">name</th>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">email</th>
{{--                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">rows added</th>--}}

                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($editors as $editor)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-4 px-6 border-b border-grey-light">{{ $editor->name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ $editor->email }}</td>
{{--                            <td class="py-4 px-6 border-b border-grey-light"> 1000 </td>--}}

                            <td class="py-4 px-6 border-b border-grey-light">
                                @if ($editor->isValid)
                                    <button wire:click="deactivateEditor({{$editor}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">deactivate</button>
                                @else
                                    <button wire:click="activateEditor({{$editor}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">activate</button>
                                @endif

                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>





</div>
