<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Hooks\AbstractFilter;

class AcfPostAttributeFilter extends AbstractFilter {
    public function filter ($value, string $name, $model) {
        if (!empty($fieldValue = get_field($name, $model->id))) {
            return $fieldValue;
        }

        return $value;
    }
}