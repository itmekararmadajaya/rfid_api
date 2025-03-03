<x-filament-panels::page>
    <div style="background-color: white; padding: 32px; width: 50%; ">
        <table>
            <tbody>
                <tr>
                    <td>
                        <span>Tag No</span>
                    </td>
                    <td style="padding: 2px 8px">:</td>
                    <td>
                        <span>
                            {{$participant->tag_no}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Name</span>
                    </td>
                    <td style="padding: 2px 8px">:</td>
                    <td>
                        <span>{{$participant->name}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Mandarin Name</span>
                    </td>
                    <td style="padding: 2px 8px">:</td>
                    <td>
                        <span>{{$participant->mandarin_name}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Position</span>
                    </td>
                    <td style="padding: 2px 8px">:</td>
                    <td>
                        <span>{{$participant->position}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>City</span>
                    </td>
                    <td style="padding: 2px 8px">:</td>
                    <td>
                        <span>{{$participant->city}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Table No</span>
                    </td>
                    <td style="padding: 2px 8px">:</td>
                    <td>
                        <span>{{$participant->table_no}}</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="padding-top: 12px">
            <x-filament::button color="success" wire:click="absence">
                Absence
            </x-filament::button>
            <a href="{{route('filament.admin.resources.participants.index')}}">
                <x-filament::button color="gray">
                    Back
                </x-filament::button>
            </a>
        </div>
    </div>
</x-filament-panels::page>
