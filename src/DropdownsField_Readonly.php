<?php

namespace Level51\DropdownsField;

class DropdownsField_Readonly extends DropdownsField {

    public function Value() {
        $value = parent::Value();

        if ($value && is_array($value)) {
            $value = array_map(function ($item) {
                return sprintf('%s (%s)', $item['key'], $item['value']);
            }, $value);

            $value = implode('; ', $value);
        }

        return $value ?: _t(__CLASS__ . '.EMPTY_STRING', '(none)');
    }
}
