<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}


    @if (!$hideDeleteModal)
        {{--        <div class="fixed h-screen w-full bg-gray-900 opacity-25">--}}
        {{--        </div>--}}

        <div class="h-screen w-full fixed flex items-center justify-center bg-modal">
            <div class="bg-white border border-gray-100 rounded shadow p-8 m-4 max-w-xl max-h-full text-center overflow-y-scroll">
                <div class="mb-4">
                    <h1>Are you sure you want to delete the table</h1>
                </div>
                <div class="mb-8">
                    <form wire:submit.prevent="deleteTable" class="items-center w-full mb-4 md:px-16">

                        <div class="flex justify-center mt-4">
                            <button type="submit" class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark mr-3" wire:click="hideDeleteModal()">Delete</button>
                        </div>

                    </form>
                    <button class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark" wire:click="hideDeleteModal()">Cancel</button>

                </div>

            </div>
        </div>

    @endif


@if($isOwned)

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
                    <form wire:submit.prevent="addTable" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">
                        <div class="mt-3">
                            <label> Table name: </label>

                            <input
                                wire:model="tableName"
                                placeholder="name"
                                required=""
                                type="text"
                                class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                            />
                            <button

                                type="submit"
                                class="mt-3 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide transition duration-200 rounded shadow-md md:w-auto bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                            >
                                Add Table
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
                    <thead>
                    <tr>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">name</th>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">rows</th>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tables as $table)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-4 px-6 border-b border-grey-light">{{ $table->name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ $table->rowsCount }}</td>

                            <td class="py-4 px-6 border-b border-grey-light">
                                <button wire:click="redirectToTableFill({{ $table->id }})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-green hover:bg-green-dark">fill</button>
                                @if($isOwned)
                                    <button wire:click="redirectToTableBuild({{ $table->id }})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">build</button>
                                    <button wire:click="showDeleteModal({{$table->id}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">delete</button>
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
