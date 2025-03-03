<?php

namespace App\Filament\Pages;

use App\Imports\ParticipantImport;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportParticipant extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.import-participant';

    protected static bool $shouldRegisterNavigation = false;

    public $success_imports, $error_imports;
    public $file;

    public function mount(){
        if(!Auth::user()){
            abort(403, 'Not Authorized');
        }
    }

    public function downloadTemplate(){
        if(Storage::disk('public')->exists('template/template-import-participant.xlsx')){
            return Storage::disk('public')->download('template/template-import-participant.xlsx');
        }else {
            return Notification::make() 
            ->title('File template not found. Please contact IT')
            ->danger()
            ->send();
        }
    }

    public function importParticipant(){
        $this->success_imports = '';
        $this->error_imports = '';

        $this->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        try {
            $importer = new ParticipantImport();
            Excel::import($importer, $this->file);

            Notification::make()->success()->title('Import Success')->send();

            $this->file = "";
            $this->success_imports = 'Success Create All Question';
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
    
            $this->error_imports = [];
            
            foreach ($failures as $failure) {
                $this->error_imports[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }
        }
    }
}
