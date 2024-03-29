<?php

namespace Zareismail\Gutenberg\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Zareismail\Gutenberg\Gutenberg;

class Website extends Resource
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

            URL::make(__('Website Name'), function () {
                return $this->getUrl();
            })->displayUsing(function () {
                return $this->name;
            })->exceptOnForms(),

            BelongsTo::make(__('Display Website By'), 'layout', Layout::class)
                ->showCreateRelationButton(),

            Select::make(__('Website Status'), 'marked_as')
                ->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'),
                    'maintenance' => __('In Maintenance'),
                ])
                ->displayUsingLabels()
                ->required()
                ->rules('required')
                ->default('inactive'),

            Select::make(__('Website Handler'), 'component')
                ->options(static::components($request))
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('Website Language'), 'locale')
                ->options(static::locales())
                ->displayUsingLabels()
                ->sortable()
                ->required()
                ->rules('required')
                ->default(function () {
                    $locales = array_keys((array) config('gutenberg.locales'));

                    return in_array(app()->getLocale(), $locales) ? app()->getLocale() : current($locales);
                }),

            Text::make(__('Website Name'), 'name')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Website'))
                ->onlyOnForms(),

            Text::make(__('Website Title'), 'title')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Website'))
                ->hideFromIndex(),

            Slug::make(__('Website Directory'), 'directory')
                ->from('name')
                ->sortable()
                ->required()
                ->onlyOnForms()
                ->rules([
                    'required',
                    Rule::unique('gutenberg_websites')
                        ->ignore($this->id)
                        ->where(function ($query) {
                            return $query->where('locale', '!=', $this->locale);
                        }),
                ]),

            Boolean::make(__('Fallback Website'), 'fallback')
                ->help(__('Determine if you need to ignore prefixing website paths'))
                ->sortable()
                ->rules([
                    Rule::unique('gutenberg_websites')
                        ->ignore($this->id)
                        ->where(function ($query) {
                            return $query->where($this->getQualifiedFallback(), 1);
                        }),
                ]),

            Textarea::make(__('Website Description'), 'description')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Website description')),

            HasMany::make(__('Website Fragments'), 'fragments', Fragment::class),
        ];
    }

    /**
     * Get the available locales.
     *
     * @return array
     */
    public static function locales()
    {
        return collect((array) config('gutenberg.locales'))->map(function ($language) {
            return __($language);
        })->toArray();
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
            Filters\Handler::make(static::components($request)->flip()->toArray()),
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
     * Get available components.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function components(Request $request)
    {
        return Gutenberg::componentCollection()->flip()->map(function ($key, $component) {
            return __(class_basename($component));
        });
    }
}
