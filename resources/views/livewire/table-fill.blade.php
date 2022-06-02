<div>
    {{-- Success is as dangerous as failure. --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form wire:submit.prevent="addRow()" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">

                    <div class="mt-3">
                        @foreach($columns as $column)


                            @if($column->type === "relation")

                                {{$this->getParentRows($column->name)}}

                                <div class="mt-3">
                                    <label>Enter {{$column->name}}: </label>
                                    <select wire:model="createdRow.{{ $column->name }}" id="parents">

                                        @if($this->parents)
                                            @foreach($this->parents as $parent)
                                                <option value="{{$parent['id']}}" > {{$parent[$column->relationColumnName]}} </option>
                                            @endforeach
                                        @else
                                            <option value="" > Null </option>
                                        @endif


                                    </select>
                                </div>

                            @elseif($column->type === "checkbox")
                                <div class="mt-3">
                                    <label>{{$column->name}}: </label>
                                    <input
                                        wire:model="createdRow.{{$column->name}}"
                                        placeholder="{{$column->name}}"
                                        type="{{$column->type}}"
                                        class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                                    />
                                </div>
                            @elseif($column->type != 'id')
                                <div class="mt-3">
                                    <label>Enter {{$column->name}}: </label>
                                    <input
                                        wire:model="createdRow.{{$column->name}}"
                                        placeholder="{{$column->name}}"
                                        required=""
                                        type="{{$column->type}}"
                                        class="flex-grow w-full h-12 px-4 mb-3 transition duration-200 bg-white border border-gray-300 rounded shadow-sm appearance-none md:mr-2 md:mb-0 focus:border-deep-purple-accent-400 focus:outline-none focus:shadow-outline"
                                    />
                                </div>

                            @endif
                        @endforeach
                        <button

                            type="submit"
                            class="mt-3 inline-flex items-center justify-center w-full h-12 px-6 font-medium tracking-wide transition duration-200 rounded shadow-md md:w-auto bg-deep-purple-accent-400 hover:bg-deep-purple-accent-700 focus:shadow-outline focus:outline-none"
                        >
                            Add Row
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
                        @foreach($columns as $column)
                            <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">{{ $column->name }}</th>
                        @endforeach

                        <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rows as $row)
                        <tr class="hover:bg-grey-lighter">
                            @foreach($columns as $column)
                                <td class="py-4 px-6 border-b border-grey-light">{{ $row[$column->name] }}</td>
                            @endforeach

                            <td class="py-4 px-6 border-b border-grey-light">
                                <button wire:click="deleteRow({{ json_encode($row) }})" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-blue hover:bg-blue-dark">delete</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
