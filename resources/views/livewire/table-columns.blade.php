<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}

{{--    add optional input for relation name --}}

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
                <form wire:submit.prevent="addColumn" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">

                    <div class="mt-3">
                        <div class="mt-3">
                            <label>Choose a type from this list:
                                <select wire:model="columnType" id="types">
                                    <option value="">select type</option>
                                    <option value="text">text</option>
                                    <option value="number">number</option>
                                    <option value="checkbox">boolean</option>
                                    <option value="tel">phone</option>
                                    <option value="email">email</option>
                                    <option value="url">link</option>
                                    <option value="date">date</option>
                                    <option value="relation">relation</option>
                                </select>
                            </label>
                        </div>

                        @if($columnType === "relation")

                            <div class="mt-3">
                                <label> Choose a a table form the list:
                                    <select wire:model="tableName" id="tables" wire:change="updateRelationColumnList()">
                                        <option value="">select table</option>
                                    @foreach($tables as $table)
                                            <option value="{{$table['name']}}">{{$table['name']}}</option>
                                        @endforeach
                                    </select >
                                </label>
                            </div>

                            <div class="mt-3">
                                <label> Chose relation column from the list:
                                    <select wire:model="relationColumnName" id="relationColumns">
                                        <option value="">select column</option>
                                        @if($relationColumns)
                                            @foreach($relationColumns as $column)
                                                <option value="{{$column['name']}}">{{$column['name']}}</option>
                                            @endforeach
                                        @else
                                            <option value="" > Null </option>
                                        @endif

                                    </select>
                                </label>
                            </div>

                            <div class="mt-3">
                                <label> Chose custom relation name: </label>

                                <input
                                    wire:model="isCustomRelationName"
                                    type="checkbox"
                                    class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                                />
                            </div>

                            @if($isCustomRelationName)

                                <div class="mt-3">
                                    <label>Enter custom relation name: </label>
                                    <input
                                        wire:model="customRelationName"
                                        placeholder="name"
                                        required=""
                                        type="text"
                                        class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                                    />
                                </div>
                            @endif

                        @else
                            <div class="mt-3">
                                <label> Column Name: </label>
                                <input
                                    wire:model="columnName"
                                    placeholder="name"
                                    required=""
                                    type="text"
                                    class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                                />
                            </div>

                        @endif

                        <button
                            type="submit"
                            class="mt-3 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide transition duration-200 rounded shadow-md md:w-auto bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                        >
                            Add Column
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
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">type</th>

                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($columns as $column)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-4 px-6 border-b border-grey-light">{{ $column->name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ $column->type }}</td>

                            @if($column->type != 'id')
                                <td class="py-4 px-6 border-b border-grey-light">
                                    <button wire:click="deleteColumn({{$column}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">delete</button>
                                </td>
                            @else
                                <td class="py-4 px-6 border-b border-grey-light">
                                    <button class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">-----</button>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
