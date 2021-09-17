<?php

namespace Zareismail\Gutenberg\Nova;

use Armincms\Fields\BelongsToMany;
use Illuminate\Http\Request; 
use Laravel\Nova\Fields\BelongsToMany as NovaBelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select; 
use Laravel\Nova\Fields\Text; 
use Zareismail\Gutenberg\Gutenberg;

class Layout extends Resource
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

            Select::make(__('Layout Status'), 'marked_as')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'), 
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required')
                ->default('inactive'), 

            Text::make(__('Layout Name'), 'name')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Layout')), 

            BelongsToMany::make(__('Layout Plugins'), 'plugins', Plugin::class)
                ->fields(function() {
                   return [
                        Number::make(__('Widget Order'), 'order')
                            ->default(time())
                            ->required()
                            ->rules('required'),
                   ]; 
                }), 

            NovaBelongsToMany::make(__('Configure Widgets'), 'widgets', Widget::class)
                ->fields(function() {
                   return [
                        Number::make(__('Widget Order'), 'order')
                            ->default(time())
                            ->required()
                            ->rules('required'),
                   ]; 
                }),
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
