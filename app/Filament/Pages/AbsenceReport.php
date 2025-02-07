<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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

class AbsenceReport extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.absence-report';

    public function mount(){

    }

    public function table(Table $table) {
        return $table
            ->headerActions([
                Action::make("flush")->requiresConfirmation()->action(function(){
                    return Attendance::query()->delete();
                })
            ])
            ->query(Attendance::
                select("attendances.*", "participants.name as participant_name", "participants.tag_no as participant_tag_no")->
                leftJoin("participants", "attendances.participant_id", "=", "participants.id"))
            ->columns([
                TextColumn::make('participant_name'),
                TextColumn::make('participant_tag_no'),
                TextColumn::make('check_in'),
                TextColumn::make('reader_no'),
            ])
            ->filters([
                Filter::make('date_form')->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('attendances.created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('attendances.created_at', '<=', $date),
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
