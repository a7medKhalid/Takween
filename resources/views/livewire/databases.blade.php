<div>
    {{-- The whole world belongs to you. --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="addDataBase" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">
                    <input
                        wire:model="databaseName"
                        placeholder="name"
                        required=""
                        type="text"
                        class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                    />
                    <button

                        type="submit"
                        class="inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide transition duration-200 rounded shadow-md md:w-auto bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                    >
                        Add Database
                    </button>
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
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">rows</th>

                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($databases as $database)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-4 px-6 border-b border-grey-light">{{ $database->name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">1000</td>

                            <td class="py-4 px-6 border-b border-grey-light">
                                <button wire:click="redirectToTables({{ $database->id }})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-green hover:bg-green-dark">tables</button>
                                <button wire:click="" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">editors</button>
                                <button wire:click="showModal({{$database->id}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">export</button>
                                <button wire:click="deleteDataBase({{$database}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">delete</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @if (!$hideModal)
        <div class="h-screen w-full absolute flex items-center justify-center bg-modal">
            <div class="bg-white rounded shadow p-8 m-4 max-w-xs max-h-full text-center overflow-y-scroll">
                <div class="mb-4">
                    <h1>Welcome!</h1>
                </div>
                <div class="mb-8">
                    <form wire:submit.prevent="exportDatabase" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">

                        <label>Choose export type from this list:
                            <select wire:model="exportType" id="types">
                                <option value="json">JSON</option>
                                <option value="sqlite">sqlite</option>
                                <option value="nestedJson">nested JSON</option>
                            </select>
                        </label>

                        <div class="flex justify-center mb-4">
                            <button type="submit" class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark mr-3" wire:click="hideModal()">Download</button>
                            <button class="flex-no-shrink text-white py-2 px-4 rounded bg-gray-800 hover:bg-teal-dark" wire:click="hideModal()">Cancel</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    @endif









</div>
