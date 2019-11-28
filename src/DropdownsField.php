<?php

namespace Level51\DropdownsField;

use SilverStripe\Forms\FormField;
use SilverStripe\View\Requirements;

/**
 * A FormField which renders multiple dropdowns and stores the results as a JSON object.
 *
 * @package Level51\DropdownsField
 *
 * @todo    cleanup and documentation
 */
class DropdownsField extends FormField {

    protected $source;

    protected $valueOptions;

    protected $hasEmptyDefault = false;

    protected $emptyString = '';

    /**
     * DropdownsField constructor.
     *
     * @param string $name         Database field name
     * @param string $title        Title / field label
     * @param array  $source       List of all first level options, each entry with a "key" and a "label". A dropdown will be rendered for erach entry
     * @param array  $valueOptions List of all options each dropdown should have, each entry must also have a "key" and a "label"
     */
    public function __construct($name, $title = null, $source = [], $valueOptions = []) {
        $this->setSource($source);
        $this->setValueOptions($valueOptions);

        if (!isset($title))
            $title = $name;

        $this->emptyString = _t(__CLASS__ . '.EMPTY_STRING');

        parent::__construct($name, $title, null);
    }

    /**
     * @param array $properties
     *
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     */
    public function Field($properties = array()) {
        Requirements::javascript('level51/silverstripe-dropdownsfield: client/dist/dropdownsField.js');
        Requirements::css('level51/silverstripe-dropdownsfield: client/dist/dropdownsField.css');

        return parent::Field($properties);
    }

    /**
     * Get the payload passed to the vue component.
     *
     * @return string
     */
    public function getPayload() {
        $value = $this->Value();
        $source = $this->getSource();

        // Get the value for each dropdown if set
        foreach ($source as $index => $sourceItem) {
            if ($value) {
                $v = array_filter($value, function ($item) use ($sourceItem) {
                    return $item['key'] === $sourceItem['key'];
                });
            }

            $source[$index]['value'] = isset($v) && $v && !empty($v) ? array_shift($v)['value'] : null;
        }

        return json_encode([
            'id'           => $this->ID(),
            'name'         => $this->getName(),
            'i18n'         => $this->getFrontendI18NPayload(),
            'source'       => $source,
            'valueOptions' => $this->getValueOptions()
        ]);
    }

    /**
     * Get the field value as array if a value is set.
     *
     * @return array|null
     */
    public function Value() {
        if ($this->value) {
            $val = is_array($this->value) ? $this->value : json_decode($this->value, true);

            return array_map(function ($key) use ($val) {
                return [
                    'key'   => $key,
                    'value' => $val[$key]
                ];
            }, array_keys($val));
        }

        return null;
    }

    /**
     * Get a list of all labels used within the frontend.
     *
     * @return array
     * @todo define i18n payload if needed
     */
    public function getFrontendI18NPayload() {
        $payload = [];
        $keys = [];

        foreach ($keys as $key) {
            $payload[$key] = _t(__CLASS__ . '.' . $key);
        }

        return $payload;
    }

    /**
     * Set the field source.
     *
     * A dropdown will be rendered for each source entry.
     * Each entry must have at least a "key" and a "label" property.
     *
     * @param array $source
     *
     * @return $this
     */
    public function setSource($source) {
        $this->source = $source;

        return $this;
    }

    /**
     * Set the available value options.
     *
     * Each dropdown, defined by the $source, will have this options available for selection.
     * Each entry must have at least a "key" and a "label" property.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setValueOptions($options) {
        $this->valueOptions = $options;

        return $this;
    }

    /**
     * @param $bool
     *
     * @return $this
     */
    public function setHasEmptyDefault($bool) {
        $this->hasEmptyDefault = $bool;

        return $this;
    }

    /**
     * @param $string
     *
     * @return $this
     */
    public function setEmptyString($string) {
        $this->setHasEmptyDefault(true);
        $this->emptyString = $string;
        return $this;
    }

    /**
     * @return array
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getValueOptions() {
        $options = $this->valueOptions;

        if ($this->hasEmptyDefault)
            array_unshift($options, ['key' => '', 'label' => $this->emptyString]);

        return $options;
    }
}
