<div>
    {{-- The whole world belongs to you. --}}

    @if (!$hideExportModal)
{{--        <div class="fixed h-screen w-full bg-gray-900 opacity-25">--}}
{{--        </div>--}}

        <div class="h-screen w-full fixed flex items-center justify-center bg-modal">
            <div class="bg-white border border-gray-100 rounded shadow p-8 m-4 max-w-xl max-h-full text-center overflow-y-scroll">

                <div class="mb-8">
                    <form wire:submit.prevent="exportDatabase" class="items-center w-full mb-4 md:px-16">

                        <label>Choose export type from this list: </label>
                        <select wire:model="exportType" id="types">
                                <option >Export Type</option>
                                <option value="json">JSON</option>
                                <option value="sqlite">sqlite</option>
{{--                                <option value="nestedJson">nested JSON</option>--}}
                            </select>

                        <div class="mt-4">
                            <button type="submit" class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark mr-3" wire:click="hideExportModal()">Download</button>
                        </div>

                    </form>
                    <div class="mt-4">
                        <button class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark" wire:click="hideExportModal()">Cancel</button>
                    </div>
                </div>

            </div>
        </div>

    @endif

    @if (!$hideDeleteModal)
        {{--        <div class="fixed h-screen w-full bg-gray-900 opacity-25">--}}
        {{--        </div>--}}

        <div class="h-screen w-full fixed flex items-center justify-center bg-modal">
            <div class="bg-white border border-gray-100 rounded shadow p-8 m-4 max-w-xl max-h-full text-center overflow-y-scroll">
                <div class="mb-4">
                    <h1>Are you sure you want to delete the database</h1>
                </div>
                <div class="mb-8">
                    <form wire:submit.prevent="deleteDataBase" class="items-center w-full mb-4 md:px-16">

                        <div class="flex justify-center mt-4">
                            <button type="submit" class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark mr-3" wire:click="hideDeleteModal()">Delete</button>
                        </div>

                    </form>
                    <button class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark" wire:click="hideDeleteModal()">Cancel</button>

                </div>

            </div>
        </div>

    @endif



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="addDataBase" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">
                    <div class="mt-3">
                        <label> Database name: </label>

                        <input
                            wire:model="databaseName"
                            placeholder="name"
                            required=""
                            type="text"
                            class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                        />


                        <button
                            type="submit"
                            class="mt-3 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide transition duration-200 rounded shadow-md md:w-auto bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                        >
                            Add Database
                        </button>
                        @if($errors->has('database'))
                            <span class="text-red-500">{{ $errors->first('database') }}</span>

                        @endif

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div>
                    <p class="py-4 px-6 bg-grey-lightest font-bold uppercase text-xl text-grey-dark border-b border-grey-light"> Owned databases </p>
                </div>

                <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
                    <thead>
                    <tr>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">name</th>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">rows</th>

                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($databases as $database)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-4 px-6 border-b border-grey-light">{{ $database->name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ $database->rowsCount }}</td>

                            <td class="py-4 px-6 border-b border-grey-light">
                                <button wire:click="redirectToTables({{ $database->id }})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-green hover:bg-green-dark">tables</button>
                                <button wire:click="redirectToEditors({{ $database->id }})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">editors</button>
                                <button wire:click="showExportModal({{$database->id}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">export</button>
                                <button wire:click="showDeleteModal({{$database->id}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">delete</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <div>
                    <p class="py-4 px-6 bg-grey-lightest font-bold uppercase text-xl text-grey-dark border-b border-grey-light"> invited databases </p>
                </div>

                <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
                    <thead>
                    <tr>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">name</th>
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">rows</th>

                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invitedDatabases as $database)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-4 px-6 border-b border-grey-light">{{ $database->name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">1000</td>

                            <td class="py-4 px-6 border-b border-grey-light">
                                <button wire:click="redirectToTables({{ $database->id }})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-green hover:bg-green-dark">tables</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
