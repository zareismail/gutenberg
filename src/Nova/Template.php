<?php

namespace Zareismail\Gutenberg\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Zareismail\Gutenberg\Gutenberg;

class Template extends Resource
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
        return array_merge([
            ID::make(__('ID'), 'id')->sortable(),

            Select::make(__('Template Handler'), 'template')
                ->options(static::handlers($request))
                ->displayUsingLabels()
                ->readonly()
                ->sortable(),

            Text::make(__('Template Name'), 'name')
                ->sortable()
                ->required()
                ->rules('required')
                ->placeholder(__('New Gutenberg Template')),

            Tag::make(__('Required Plugins'), 'plugins', Plugin::class)
                ->showCreateRelationButton()
                ->searchable(false),

            Textarea::make(__('Template Note'), 'note')
                ->sortable()
                ->placeholder(__('Write something ...')),

            $this->mergeWhen(
                collect($this->resource->variables())->keyBy->name()->map->help()->count(),
                $this->usageFields()
            ),

            Code::make(__('Template HTML'), 'html')
                ->required()
                ->rules('required')
                ->language('htmlmixed')
                ->stacked()
                ->autoHeight(),

        ]);
    }

    public function usageFields()
    {
        return [
            KeyValue::make(__('Available Variables'))
                ->readonly()
                ->onlyOnForms()
                ->keyLabel(__('Variable Name'))
                ->valueLabel(__('Variable Help'))
                ->resolveUsing(function () {
                    return collect($this->resource->variables())->keyBy->name()->map->help();
                }),

            Text::make(__('Variable Using'))
                ->required()
                ->onlyOnForms()
                ->textAlign('left')
                ->help(__('Use the mentioned value to replace variable value.'))
                ->fillUsing(function () {
                })
                ->resolveUsing(function () {
                    return '{{  variableName or Default Value }}';
                })
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                        'style' => 'direction: ltr !important',
                    ],
                ]),

            Text::make(__('Translation Using'))
                ->required()
                ->onlyOnForms()
                ->textAlign('left')
                ->help(__('Use the mentioned value to replace translation string.'))
                ->fillUsing(function () {
                })
                ->resolveUsing(function () {
                    return '{{ _(translation string) }}';
                })
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                        'style' => 'direction: ltr !important',
                    ],
                ]),

            Text::make(__('If Condition'))
                ->required()
                ->onlyOnForms()
                ->textAlign('left')
                ->help(__('Wrap your string in the below pattern to display when condition is truth.'))
                ->fillUsing(function () {
                })
                ->resolveUsing(function () {
                    return '{% if variableName %} your string {% endif %}';
                })
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                        'style' => 'direction: ltr !important',
                    ],
                ]),

            Text::make(__('Unless Condition'))
                ->required()
                ->onlyOnForms()
                ->help(__('Wrap your string in the below pattern to display when condition is not truth.'))
                ->fillUsing(function () {
                })
                ->resolveUsing(function () {
                    return '{% unless variableName %} your string {% endunless %}';
                })
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                        'style' => 'direction: ltr !important',
                    ],
                ]),

            Text::make(__('Is Condition'))
                ->required()
                ->onlyOnForms()
                ->help(__('Wrap your string in the below pattern to display when the compare is equal.'))
                ->fillUsing(function () {
                })
                ->resolveUsing(function () {
                    return '{% is firstVariable,secondVariable %} your string {% endis %}';
                })
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                        'style' => 'direction: ltr !important',
                    ],
                ]),

            Text::make(__('Each Loop'))
                ->required()
                ->onlyOnForms()
                ->help(__('Navigate through items and display their contents.'))
                ->fillUsing(function () {
                })
                ->resolveUsing(function () {
                    return '{# each variableName,itemName,indexName #} your string {# endeach #}';
                })
                ->withMeta([
                    'extraAttributes' => [
                        'readonly' => true,
                        'style' => 'direction: ltr !important',
                    ],
                ]),
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
        return [
            Filters\Handler::make(static::handlers($request)->flip()->toArray()),
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
            Actions\CreateTemplate::make()
                ->standalone()
                ->onlyOnIndex(),
        ];
    }

    /**
     * Get template handlers
     *
     * @param  Request  $request [description]
     * @return [type]           [description]
     */
    public static function handlers(Request $request)
    {
        return Gutenberg::templateCollection()->flip()->map(function ($key, $template) {
            return class_exists($template) ? $template::label() : $template;
        });
    }
}
