<?php

namespace Zareismail\Gutenberg;

use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Cypress\Plugin;

class GutenbergPlugin extends Plugin
{
    /**
     * The plugin asset.
     *
     * @var \Zareismail\Gutenberg\Models\GutenbergPlugin
     */
    protected $asset;

    /**
     * Initiate a new plugin instance.
     *
     * @param  \Zareismail\Gutenberg\Models\GutenbergPlugin  $plugin
     */
    public function __construct(array $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Bootstrap the resource for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @param  \Zareismail\Cypress\Layout  $layout
     * @return void
     */
    public function boot(CypressRequest $request, $layout)
    {
        $layout->appendPlugins([
            $this,
        ]);
    }

    /**
     * Determine if the plugin should be loaded as html meta.
     *
     * @return bool
     */
    public function asMetadata(): bool
    {
        return $this->asset['head'] ?? false;
    }

    /**
     * get the plugin asset url.
     *
     * @return string
     */
    public function url()
    {
        return $this->asset['url'] ?? '';
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->asset['type'] == 'js'
            ? '<script src="'.$this->url().'"></script>'
            : '<link rel="stylesheet" type="text/css" href="'.$this->url().'">';
    }
}
