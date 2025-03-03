<x-filament-panels::page>
    <div>
        <span>Instruksi Import</span>
        <div style="margin: 12px 0px">
            <ul class="list-disc list-inside">
                <li>Pastikan file yang diimport sesuai dengan <strong>template (.xlsx)</strong>. Template dapat diundun <button wire:click="downloadTemplate" style="color: blue">disini</button>.</li>
                <li>Harap mengisi template tanpa mengubah/menghapus baris pertama</li>
                <li>Jika ada data yang duplikat, data terakhir yang akan masuk ke database</li>
            </ul>
        </div>
    </div>
    <div>
        <div>
            <div class="w-1/2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                <input wire:model="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Upload file sesuai template (.xlsx)</p>
                @error('file') <span class="fi-fo-field-wrp-error-message text-sm text-danger-600">{{ $message }}</span> @enderror
            </div> 
        </div>
        <div>
            @if ($success_imports != "")
                <div>
                    <strong style="color: green" class="text-success-600">{{$success_imports}}</strong>
                </div>
            @endif
            @if ($error_imports != "")
            <div>
                <span class="title">Report Error</span>
                <table class="bordered-table">
                    <thead>
                        <th>Row</th>
                        <th>Column</th>
                        <th>Error</th>
                    </thead>
                    <tbody>
                        @foreach ($error_imports as $error)
                            <tr>
                                <td>{{$error['row']}}</td>
                                <td>{{$error['attribute']}}</td>
                                <td>
                                    <div>
                                        @foreach ($error['errors'] as $msg)
                                            <ul>
                                                <li>{{$msg}}</li>
                                            </ul>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div>
                    <strong>Silahkan perbaiki data tersebut, kemudian upload ulang.</strong>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="">
        <x-filament::button wire:click="importParticipant" style="margin-right: 5px;">
            Import
        </x-filament::button>
        <a href="{{route('filament.admin.resources.participants.index')}}">
            <x-filament::button color="gray">
                Back
            </x-filament::button>
        </a>
    </div>
</x-filament-panels::page>