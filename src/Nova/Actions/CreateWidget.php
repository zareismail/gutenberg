<?php

namespace Zareismail\Gutenberg\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\Models\GutenbergWidget;
use Zareismail\Gutenberg\Nova\Widget;

class CreateWidget extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $widget = tap(new GutenbergWidget, function($widget) use ($fields) {
            $widget->forceFill([
                'name' => $fields->get('name'),
                'widget' => $fields->get('widget'),
            ])->save();
        }); 

        return [
            'push' => [
                'name' => 'edit',
                'params' => [
                    'resourceName'  => Widget::uriKey(),
                    'resourceId'    => $widget->getKey(),
                ],
            ],
        ];
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make(__('Widget Handler'), 'widget')
                ->options(Gutenberg::widgetCollection()->flip()->map(function($key, $widget) {
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
