<?php

namespace Zareismail\Gutenberg; 

use Zareismail\Cypress\Http\Requests\CypressRequest;

trait HasTemplate  
{      
    /**
     * Bootstrap the resource for the given request.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Resource $layout 
     * @return void                  
     */
    public function bootstrapTemplate(CypressRequest $request, $layout)
    { 
        $this->withMeta([
            '_template' => tap($this->template(), function($template) use ($request, $layout) {
                $template
                        ->plugins
                        ->filter->isActive()
                        ->flatMap->gutenbergPlugins()
                        ->each->boot($request, $layout);
            }),
        ]);
    }

    /**
     * Get the template instance.
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function template()
    {
        return $this->findTemplate($this->getTemplateId());
    }

    /**
     * Get the template id.
     * 
     * @return integer
     */
    abstract public function getTemplateId(): int; 

    /**
     * Find template for the given key.
     * 
     * @param integer  $templateId 
     * @return \Zareismail\Gutenberg\Models\GutenbergTemplate           
     */
    public function findTemplate($templateId)
    {
        return tap(Gutenberg::cachedTemplates()->find($templateId), function($template) {
            abort_if(is_null($template), 422, 'Not found template to display widget');
        });
    }

    /**
     * Get the available template for the given template name.
     * 
     * @return \Illuminate\Support\Collection
     */
    protected static function availableTemplates(string $templateName)
    {
        return Gutenberg::cachedTemplates()
                    ->where('template', $templateName)
                    ->keyBy->getKey()
                    ->map->name;
    }   

    /**
     * Serialize the widget fro template.
     * 
     * @return array
     */
    abstract public function serializeForTemplate(): array;

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    { 
        $template = $this->metaValue('_template');

        return $template->gutenbergTemplate($this->serializeForTemplate())->render(); 
    }
}
