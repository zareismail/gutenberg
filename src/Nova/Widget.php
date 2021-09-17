<?php

namespace Zareismail\Gutenberg\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select; 
use Laravel\Nova\Fields\Text; 
use Zareismail\Gutenberg\Gutenberg;

class Widget extends Resource
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

            Select::make(__('Widget Handler'), 'widget')
                ->options(Gutenberg::widgetCollection()->flip()->map(function($key, $widget) {
                    return __(class_basename($widget));
                }))
                ->displayUsingLabels()
                ->readonly(),

            Select::make(__('Widget Status'), 'marked_as')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'), 
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required')
                ->default('inactive'),  

            Text::make(__('Widget Name'), 'name')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Widget')), 

            $this->mergeWhen(! $request->isResourceIndexRequest(), function() use ($request) {
                return $this->resource->fields($request);
            }),
        ];
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
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
        return [
            Actions\CreateWidget::make()
                ->standalone()
                ->canSee(function($request) {
                    return $request->user()->can('create', config('gutenberg.models.'.static::class)) &&
                         ! $request->viaRelationship();
                }),
        ];
    }
}
