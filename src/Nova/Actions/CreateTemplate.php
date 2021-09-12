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
use Zareismail\Gutenberg\Models\GutenbergTemplate;
use Zareismail\Gutenberg\Nova\Template;

class CreateTemplate extends Action
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
        $template = tap(new GutenbergTemplate, function($template) use ($fields) {
            $template->forceFill([
                'name' => $fields->get('name'),
                'template' => $fields->get('template'),
            ])->save();
        }); 

        return [
            'push' => [
                'name' => 'edit',
                'params' => [
                    'resourceName'  => Template::uriKey(),
                    'resourceId'    => $template->getKey(),
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
            Select::make(__('Template Handler'), 'template')
                ->options(Gutenberg::templateCollection()->flip()->map(function($key, $template) {
                    return $template::label();
                }))
                ->required()
                ->rules('required'),

            Text::make(__('Template Name'), 'name')
                ->required()
                ->rules('required'),
        ];
    }
}
