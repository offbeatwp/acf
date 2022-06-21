<?php

namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Acf\Fields\Acf\AcfIconSelectField;
use OffbeatWP\Hooks\AbstractFilter;

class AcfLoadFieldIconsFilter extends AbstractFilter {
    public function filter($field) {
        if (!isset($field['type']) || $field['type'] !== AcfIconSelectField::FIELD_NAME) {
            return $field;
        }

        $field['choices'] = [];

        $path = '/assets/icons/';
        if (!empty($field['icon_subfolder'])) {
            $path .= $field['icon_subfolder'] . '/';
        }

        $iconGlob = glob(get_stylesheet_directory() . $path . '*.svg') ?: [];

        foreach ($iconGlob as $filename) {
            $basename = basename($filename, '.svg');
            $field['choices'][$basename] = "<i class='oif oif-{$basename}'></i>";
        }

        return $field;
    }
}