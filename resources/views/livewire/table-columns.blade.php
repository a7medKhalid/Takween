<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="addColumn" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">



                    <label>Choose a type from this list:
                        <select wire:model="columnType" id="types">
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

                    @if($columnType === "relation")

                        <label> Choose a a table form the list:
                            <select wire:model="columnName" id="tables" wire:change="updateRelationColumnList()">
                                @foreach($tables as $table)
                                    <option value="{{$table['name']}}">{{$table['name']}}</option>
                                @endforeach
                            </select >
                        </label>



                        <label> Chose relation column from the list:
                            <select wire:model="relationColumnName" id="relationColumns">
                                @foreach($relationColumns as $column)
                                    <option value="{{$column['name']}}">{{$column['name']}}</option>
                                @endforeach
                            </select>
                        </label>


                    @else
                        <input
                            wire:model="columnName"
                            placeholder="name"
                            required=""
                            type="text"
                            class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                        />
                    @endif

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide transition duration-200 rounded shadow-md md:w-auto bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                    >
                        Add Column
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
                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">type</th>

                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($columns as $column)
                        <tr class="hover:bg-grey-lighter">
                            <td class="py-4 px-6 border-b border-grey-light">{{ $column->name }}</td>
                            <td class="py-4 px-6 border-b border-grey-light">{{ $column->type }}</td>

                            <td class="py-4 px-6 border-b border-grey-light">
                                <button wire:click="deleteColumn({{$column}})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">delete</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>