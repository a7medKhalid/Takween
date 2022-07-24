<div>
    {{-- Success is as dangerous as failure. --}}

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
                <form wire:submit.prevent="addRow()" class="flex flex-col items-center w-full mb-4 md:flex-row md:px-16">

                    <div class="mt-3">
                        {{-- loop to show all row columns                       --}}
                        @foreach($columns as $column)


                            @if($column->type === "relation")

                                {{$this->getParentRows($column)}}

                                <div class="mt-3">
                                    <label>Enter {{$column->name}}: </label>
                                    <select wire:model="createdRow.{{ $column->name }}" id="parents">

                                        @if($this->parents)
                                            @foreach($this->parents as $parent)
                                                <option value="{{$parent['id']}}" >{{$parent['id']}} | {{$parent[$column->relationColumnName]}} </option>
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

                <nav class="m-4 items-center" aria-label="Page navigation example">
                    <ul class="inline-flex -space-x-px">
                        <li>
                            <a wire:click="previous()" class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                        </li>
                        <li>
                            <input wire:model="pageNumber" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        </li>
                        <li>
                            <a wire:click="next()" class="py-2 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                        </li>
                    </ul>
                </nav>
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
                    @foreach(array_reverse($rows) as $row)
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
