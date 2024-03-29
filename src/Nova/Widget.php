<?php

namespace Zareismail\Gutenberg\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\Templates\Blank;

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
        'id', 'name',
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
                ->options(static::widgets($request))
                ->displayUsingLabels()
                ->readonly(),

            BelongsTo::make(__('Display Widget By'), 'template', Template::class)
                ->required()
                ->rules('required')
                ->withoutTrashed(),

            Select::make(__('Widget Display Status'), 'marked_as')
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

            Number::make(__('Cache Time'), 'ttl')
                ->displayUsing(function ($value) {
                    return $this->resource->isCacheable()
                        ? $value.' '.__('(s)')
                        : __('Not supported');
                })
                ->nullable()
                ->min(0)
                ->default(300)
                ->help(__('Seconds of widget caching (*zero means ignoring cache).'))
                ->hideWhenUpdating(function () {
                    return ! $this->resource->isCacheable();
                }),

            $this->mergeWhen(! $request->isResourceIndexRequest(), function () use ($request) {
                return $this->resource->fields($request);
            }),
        ];
    }

    public static function relatableGutenbergTemplates($request, $query)
    {
        $resource = $request->findResourceOrFail();
        $canQuery = $resource->hasCypressWidget() && method_exists(
            $resource->cypressWidget(),
            'relatableTemplates'
        );

        return $query->when($canQuery, function ($query) use ($resource, $request) {
            $widget = $resource->cypressWidget();

            $widget::relatableTemplates($request, $query);
        }, function ($query) {
            $query->handledBy(Blank::class);
        });
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
        return [
            Filters\Handler::make(static::widgets($request)->flip()->toArray()),
        ];
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
            Actions\ConfigCache::make()->onlyOnIndex(),

            Actions\CreateWidget::make()
                ->standalone()
                ->onlyOnIndex()
                ->canSee(function ($request) {
                    return $request->user()->can('create', config('gutenberg.models.'.static::class)) &&
                        ! $request->viaRelationship();
                }),
        ];
    }

    /**
     * Get available widgets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function widgets(Request $request)
    {
        return Gutenberg::widgetCollection()->flip()->map(function ($key, $widget) {
            return __(class_basename($widget));
        });
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return \Laravel\Nova\URL|string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.static::uriKey();
    }
}
