<?php

namespace Level51\ObjectTagField;

use SilverStripe\Forms\FormField;
use SilverStripe\View\Requirements;

/**
 * Class ObjectTagField
 * @package Level51\ObjectTagField
 *
 * @todo cleanup and documentation
 */
class ObjectTagField extends FormField {

    protected $source;

    protected $valueOptions;

    public function __construct($name, $title = null, $source = [], $valueOptions = [], $value = null) {
        $this->setSource($source);
        $this->setValueOptions($valueOptions);

        if (!isset($title))
            $title = $name;

        parent::__construct($name, $title, $value);
    }

    public function Field($properties = array()) {
        Requirements::javascript('level51/silverstripe-object-tagfield: client/dist/objectTagField.js');
        Requirements::css('level51/silverstripe-object-tagfield: client/dist/objectTagField.css');

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
     * @return array|mixed|null
     */
    public function Value() {
        if ($this->value) {
            $val = json_decode($this->value, true);

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

    public function setSource($source) {
        $this->source = $source;

        return $this;
    }

    public function setValueOptions($options) {
        $this->valueOptions = $options;

        return $this;
    }

    public function getSource() {
        return $this->source;
    }

    public function getValueOptions() {
        return $this->valueOptions;
    }

    public function setSubmittedValue($value, $data = null) {
        if (!$value) return $this;

        return $this->setValue($value, $data);
    }
}
