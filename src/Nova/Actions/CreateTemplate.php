<?php

namespace Zareismail\Gutenberg\Nova\Actions;

use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\Nova\Template;

class CreateTemplate extends Action
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
        $template = tap(Template::newModel(), function ($template) use ($fields) {
            $template->forceFill([
                'name' => $fields->get('name'),
                'template' => $fields->get('template'),
            ])->save();
        });

        return Action::visit("/resources/" . Template::uriKey() . "/{$template->getKey()}/edit");
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
            Select::make(__('Template Handler'), 'template')
                ->options(Gutenberg::templateCollection()->flip()->map(function ($key, $template) {
                    return [
                        'label' => $template::label(),
                        'group' => $template::group(),
                    ];
                })->toArray())
                ->required()
                ->rules('required'),

            Text::make(__('Template Name'), 'name')
                ->required()
                ->rules('required'),
        ];
    }
}
