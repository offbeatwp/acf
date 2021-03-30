<?php
namespace OffbeatWP\Acf;

use OffbeatWP\Acf\Hooks\AcfConverPostObject;
use OffbeatWP\Acf\Hooks\AcfPostAttributeFilter;
use OffbeatWP\Acf\Hooks\AcfPostRelationships;
use OffbeatWP\Acf\Hooks\AcfTermAttributeFilter;
use OffbeatWP\Content\Taxonomy\TermModel;
use OffbeatWP\Services\AbstractService;
use OffbeatWP\Content\Post\PostModel;

class Service extends AbstractService {

    public function register() {
        offbeat('hooks')->addFilter('post_attribute', AcfPostAttributeFilter::class, 10, 3);
        offbeat('hooks')->addFilter('term_attribute', AcfTermAttributeFilter::class, 10, 3);

        PostModel::macro('getField', function ($name, $format = true) {
            if (!function_exists('get_field')) return null;

            return get_field($name, $this->getId(), $format);
        });

        PostModel::macro('getFieldObject', function ($name, $format = true) {
            if (!function_exists('get_field_object')) return null;

            return get_field_object($name, $this->getId(), $format);
        });

        PostModel::macro('updateField', function ($name, $value) {
            if (!function_exists('update_field')) return null;

            return update_field($name, $value, $this->getId());
        });

        TermModel::macro('getField', function ($name, $format = true) {
            if (!function_exists('get_field')) return null;

            return get_field($name, $this->wpTerm, $format);
        });

        offbeat('hooks')->addFilter('acf/update_value/type=relationship', AcfPostRelationships::class, 10, 4);
        offbeat('hooks')->addFilter('acf/update_value/type=post_object', AcfPostRelationships::class, 10, 4);

        offbeat('hooks')->addFilter('acf/format_value/type=relationship', AcfConverPostObject::class, 99, 3);
    }
}
