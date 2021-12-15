<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Hooks\AbstractFilter;

class AcfPostAttributeFilter extends AbstractFilter {
    public function filter ($value, string $name, $model) {
        return get_field($name, $model->id) ?: $value;
    }
}