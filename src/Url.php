<?php

namespace Inspheric\Fields;

use Laravel\Nova\Fields\Text;

class Url extends Text
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'url-field';

    /**
     * The callback to be used to resolve the field's label.
     *
     * @var \Closure
     */
    public $labelCallback;

    /**
     * The label to display instead of the URL.
     *
     * @param  string $label
     * @return $this
     */
    public function label(string $label = null)
    {
        return $this->withMeta(['label' => $label]);
    }

    /**
     * Define the callback that should be used to resolve the field's label.
     *
     * @param  callable  $labelCallback
     * @return $this
     */
    public function labelUsing(callable $labelCallback)
    {
        $this->labelCallback = $labelCallback;

        return $this;
    }

    /**
     * Display the domain only of the URL as the label.
     *
     * @return $this
     */
    public function domainLabel()
    {
        return $this->labelUsing(function($value) {
            $value = parse_url($value, PHP_URL_HOST) ?: $value;
            return preg_replace('`^(www\d?|m)\.`', '', $value);
        });
    }

    /**
     * Display the name of the field as the label.
     *
     * @return $this
     */
    public function nameLabel()
    {
        return $this->label($this->name);
    }

    /**
     * Whether the URL should be displayed as a clickable
     * link on the detail page.
     *
     * @param  bool $clickable
     * @return $this
     */
    public function clickable(bool $clickable = true)
    {
        return $this->withMeta(['clickable' => $clickable]);
    }

    /**
     * Whether the URL should be displayed as a clickable
     * link on the index page.
     *
     * @param  bool $clickable
     * @return $this
     */
    public function clickableOnIndex(bool $clickable = true)
    {
        return $this->withMeta(['clickableOnIndex' => $clickable]);
    }

    public function target(string $target = '_blank'){
        return $this->withMeta(['target' => $target]);
    }

    /**
     * Whether the URL should be displayed as a clickable
     * link on both the index and detail pages.
     *
     * @param  bool $clickable
     * @return $this
     */
    public function alwaysClickable(bool $clickable = true)
    {
        return $this->clickable($clickable)
            ->clickableOnIndex($clickable);
    }

    /**
     * @inheritDoc
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        parent::resolveForDisplay($resource, $attribute);

        if (is_callable($this->labelCallback)) {
                $this->label(call_user_func($this->labelCallback, $this->value, $resource));
        }
    }
}
