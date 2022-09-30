<?php

namespace Zareismail\Gutenberg\Nova;

use Armincms\Fields\BelongsToMany;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Flexible;

class Plugin extends Resource
{
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Select::make(__('Plugin Status'), 'marked_as')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'),
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required')
                ->default('inactive'),

            Text::make(__('Plugin Name'), 'name')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Plugin')),

            BelongsToMany::make(__('Plugin Dependecy'), 'plugins', static::class),

            Flexible::make('Plugin Assets', 'assets')
                ->required()
                ->rules('required')
                ->collapsed()
                ->addLayout(__('Plugin Asset'), 'asset', [
                    Select::make(__('Asset Type'), 'type')
                        ->required()
                        ->rules('required')
                        ->default('js')
                        ->options([
                            'js' => __('Javascript'),
                            'css' => __('Style Sheet'),
                        ]),

                    Text::make(__('Asset URL'), 'url')
                        ->required()
                        ->rules('required'),

                    Boolean::make(__('To Head'), 'head')
                        ->default(false),
                ]),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
