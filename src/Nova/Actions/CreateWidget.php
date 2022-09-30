<?php

namespace Zareismail\Gutenberg\Nova\Actions;

use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\Models\GutenbergWidget;
use Zareismail\Gutenberg\Nova\Widget;

class CreateWidget extends Action
{

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $widget = tap(Widget::newModel(), function ($widget) use ($fields) {
            $widget->forceFill([
                'name' => $fields->get('name'),
                'widget' => $fields->get('widget'),
            ])->save();
        });

        return Action::visit("/resources/" . Widget::uriKey() . "/{$widget->getKey()}/edit");
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make(__('Widget Handler'), 'widget')
                ->options(Gutenberg::widgetCollection()->flip()->map(function ($key, $widget) {
                    return [
                        'label' => __(class_basename($widget)),
                        'group' => $widget::group(),
                    ];
                }))
                ->required()
                ->rules('required'),

            Text::make(__('Widget Name'), 'name')
                ->required()
                ->rules('required'),
        ];
    }
}
