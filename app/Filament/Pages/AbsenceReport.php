<?php

namespace App\Filament\Pages;

use App\Exports\AbsenceExport;
use App\Models\Attendance;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class AbsenceReport extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.absence-report';

    public $date_start, $date_end;

    public function mount(){

    }

    public function table(Table $table) {
        return $table
            ->headerActions([
                Action::make("flush")->requiresConfirmation()->action(function(){
                    return Attendance::query()->delete();
                }),
                Action::make('Export')->color('success')->action(function() {
                    if(empty($this->date_start) || empty($this->date_end)){
                        Notification::make()->title('Please select date start and date end')->danger()->send();
                        return;
                    }
                    return Excel::download(new AbsenceExport($this->date_start, $this->date_end), 'Absence-report-fuqingmgl.xlsx');
                })
            ])
            ->query(Attendance::
                select("attendances.*", "participants.name as participant_name", "participants.tag_no as participant_tag_no")->
                leftJoin("participants", "attendances.participant_id", "=", "participants.id"))
            ->columns([
                TextColumn::make('participant_name'),
                TextColumn::make('participant_tag_no'),
                TextColumn::make('check_in'),
                TextColumn::make('is_new'),
                TextColumn::make('reader_no'),
            ])
            ->filters([
                Filter::make('date_form')->form([
                    DatePicker::make('date_start'),
                    DatePicker::make('date_end'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when(
                            $data['date_start'],
                            function (Builder $query, $date): Builder {
                                $this->date_start = $date;
                                return $query->whereDate('attendances.created_at', '>=', $date);
                            },
                        )
                        ->when(
                            $data['date_end'],
                            function (Builder $query, $date): Builder {
                                $this->date_end = $date;
                                return $query->whereDate('attendances.created_at', '<=', $date);
                            },
                        );
                })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('delete')->action(function(Attendance $attendance){
                    return $attendance->delete();
                })->requiresConfirmation()
            ])
            ->bulkActions([
                BulkAction::make('delete_selected')->action(function(Collection $attendances){
                    return $attendances->each->delete();
                })->requiresConfirmation()
            ])
            ;
    }
}
