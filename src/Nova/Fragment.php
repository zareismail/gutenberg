<?php

namespace Zareismail\Gutenberg\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Gutenberg\Gutenberg;

class Fragment extends Resource
{
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

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

            URL::make(__('Fragment Name'), function () {
                return $this->getUrl();
            })->displayUsing(function () {
                return $this->name;
            })->exceptOnForms(),

            BelongsTo::make(__('Related Website'), 'website', Website::class)
                ->required()
                ->rules('required'),

            BelongsTo::make(__('Display Fragment By'), 'layout', Layout::class)->showCreateRelationButton(),

            Select::make(__('Fragment Status'), 'marked_as')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'),
                    'maintenance' => __('In Maintenance'),
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required')
                ->default('inactive'),

            Select::make(__('Fragment Handler'), 'fragment')
                ->options(static::fragments($request))
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Text::make(__('Fragment Name'), 'name')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Fragment'))
                ->hideFromIndex(),

            Slug::make(__('Fragment Prefix'), 'prefix')
                ->from('name')
                ->sortable()
                ->required()
                ->hideFromIndex()
                ->rules([
                    'required',
                    Rule::unique('gutenberg_fragments')
                        ->ignore($this->id)
                        ->where(function ($query) {
                            return $query->where('website_id', $this->website_id);
                        }),
                ]),

            Boolean::make(__('Fallback Fragment'), 'fallback')
                ->help(__('Determine if you need to ignore prefixing fragment paths'))
                ->sortable()
                ->rules([
                    Rule::unique('gutenberg_fragments')
                        ->ignore($this->id)
                        ->where(function ($query) {
                            return $query->where($this->getQualifiedFallback(), 1)
                                ->where('website_id', $this->website_id);
                        }),
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
        return [
            Filters\Handler::make(static::fragments($request)->flip()->toArray()),
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
        return [];
    }

    /**
     * Get available fragments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function fragments(Request $request)
    {
        return Gutenberg::fragmentCollection()->flip()->map(function ($key, $fragment) {
            return __(class_basename($fragment));
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
