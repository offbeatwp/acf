<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Content\Post\PostModel;
use OffbeatWP\Hooks\AbstractFilter;

class AcfPostAttributeFilter extends AbstractFilter {
    public function filter($value, string $name, PostModel $model) {
        return get_field($name, $model->getId()) ?: $value;
    }
}