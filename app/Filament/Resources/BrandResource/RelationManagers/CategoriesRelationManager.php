<?php

namespace App\Filament\Resources\BrandResource\RelationManagers;

use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'categories';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->default('test')
                    ->maxLength(255),

                Forms\Components\TextInput::make('name_ar')
                    ->maxLength(255)
                    ->required()
                    ->default('test')

                    ->label('Arabic Name'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),


                Forms\Components\Repeater::make('menuItems')
                    ->relationship()
                    ->label('Menu Items')
                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                    ->schema([
                        Forms\Components\Section::make('Basic Info')
                            ->description('Provide the name and description in both English and Arabic.')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->default('test')

                                            ->label('Name (EN)'),


                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('description')
                                            ->required()
                                            ->default('test')

                                            ->label('Description (EN)'),


                                    ]),
                            ])
                            ->columns(1)
                            ->collapsible(),

                        Forms\Components\Section::make('Pricing & Availability')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->required()
                                            ->default('2')

                                            ->prefix('BHD'),

                                        Forms\Components\Toggle::make('is_available')
                                            ->label('Is Available')
                                            ->default(true),
                                    ]),
                            ])
                            ->columns(1)
                            ->collapsible(),

                        Forms\Components\Section::make('Image')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->helperText('First upload an image and save the form. Then edit the same form, try to remove the image by clicking on "x".
                                    Similarly while editing try to upload another image.
                                    ')
                                    ->directory('menu-items')
                                    ->nullable(),
                            ])
                            ->columns(1)
                            ->collapsible(),
                    ])

                    ->columnSpanFull()
                    ->collapsed()
                    ->defaultItems(1),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),


                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('menu_items_count')
                    ->counts('menuItems')
                    ->label('Items Count'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['brand_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
