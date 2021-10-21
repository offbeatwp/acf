<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Content\Taxonomy\TermModel;
use OffbeatWP\Hooks\AbstractFilter;

class AcfTermAttributeFilter extends AbstractFilter {
    public function filter($value, string $name, TermModel $model) {
        return get_field($name, $model->wpTerm) ?: $value;
    }
}