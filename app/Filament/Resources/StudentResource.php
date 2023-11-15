<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\GlobalSearch\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\StudentResource\Pages;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\StudentResource\RelationManagers\GuardiansRelationManager;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Personal Information')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('student_id')
                                ->required()
                                ->minLength(10),
                        ])
                        ->icon('heroicon-o-users'),
                    Step::make('Address')
                        ->schema([
                            TextInput::make('address_1'),
                            TextInput::make('address_2'),
                        ])
                        ->icon('heroicon-o-home'),
                    Step::make('School')
                        ->schema([
                            Select::make('standard_id')
                                ->required()
                                ->relationship('standard', 'name')
                        ])
                        ->icon('heroicon-o-academic-cap'),
                ])
                ->skippable()
                ->startOnStep(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('standard.name')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('start')
                    ->query(fn (Builder $query): Builder => $query->where('standard_id', 1)),
                SelectFilter::make('standard_id')
                    ->options([
                        1 => 'Standard 1',
                        4 => 'Standard 4',
                        9 => 'Standard 9',
                    ])
                    ->label('Select Standard'),
                SelectFilter::make('All Standard')
                    ->relationship('standard', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ActionGroup::make([
                    TableAction::make('Promote')
                        ->action(function (Student $student) {
                            $student->standard_id += 1;
                            $student->save();
                        })
                        ->color('success')
                        ->requiresConfirmation(),
                    TableAction::make('Demote')
                        ->action(function (Student $student) {
                            if ($student->standard_id > 1) {
                                $student->standard_id -= 1;
                                $student->save();
                            }
                        })
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Promote All')
                        ->action(function (Collection $students) {
                            $students->each(function ($student) {
                                $student->standard_id += 1;
                                $student->save();
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GuardiansRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Name' => $record->name,
            'Standard' => $record->standard->name,
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('Edit')
                ->iconButton()
                ->icon('heroicon-s-pencil')
                ->url(static::getUrl('edit', ['record' => $record])),
            Action::make('Delete')
                ->iconButton()
                ->icon('heroicon-s-eye')
                ->url(static::getUrl('index'))
        ];
    }
}
