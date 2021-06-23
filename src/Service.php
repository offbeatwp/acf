<?php
namespace OffbeatWP\Acf;

use OffbeatWP\Acf\Fields\HiddenUniqueIdField;
use OffbeatWP\Acf\Hooks\AcfConverPostObject;
use OffbeatWP\Acf\Hooks\AcfPostAttributeFilter;
use OffbeatWP\Acf\Hooks\AcfPostRelationships;
use OffbeatWP\Acf\Hooks\AcfTermAttributeFilter;
use OffbeatWP\Content\Taxonomy\TermModel;
use OffbeatWP\Services\AbstractService;
use OffbeatWP\Content\Post\PostModel;

class Service extends AbstractService {

    public function register() {
        $this->registerAttributeHooks();
        $this->registerMacros();
        $this->registerRelationHooks();
        $this->registerFields();
    }

    // Registry Methods
    private function registerAttributeHooks() {
        offbeat('hooks')->addFilter('post_attribute', AcfPostAttributeFilter::class, 10, 3);
        offbeat('hooks')->addFilter('term_attribute', AcfTermAttributeFilter::class, 10, 3);
    }

    private function registerMacros() {
        PostModel::macro('getField', function ($name, $format = true) {
            if (!function_exists('get_field')) {
                return null;
            }

            return get_field($name, $this->getId(), $format);
        });

        PostModel::macro('getFieldObject', function ($name, $format = true) {
            if (!function_exists('get_field_object')) {
                return null;
            }

            return get_field_object($name, $this->getId(), $format);
        });

        PostModel::macro('updateField', function ($name, $value) {
            if (!function_exists('update_field')) {
                return null;
            }

            return update_field($name, $value, $this->getId());
        });

        TermModel::macro('getField', function ($name, $format = true) {
            if (!function_exists('get_field')) {
                return null;
            }

            return get_field($name, $this->wpTerm, $format);
        });
    }

    private function registerRelationHooks() {
        offbeat('hooks')->addFilter('acf/update_value/type=relationship', AcfPostRelationships::class, 10, 4);
        offbeat('hooks')->addFilter('acf/update_value/type=post_object', AcfPostRelationships::class, 10, 4);
        offbeat('hooks')->addFilter('acf/format_value/type=relationship', AcfConverPostObject::class, 99, 3);
    }

    private function registerFields() {
        if (class_exists('acf_field') && function_exists('add_action')) {
            add_action('acf/include_field_types', function () {
                new HiddenUniqueIdField();
            });
        }
    }

    // Macro functions
    protected function getArray(string $identifier): array {
        if (!function_exists('get_field')) {
            return [];
        }

        return get_field($identifier, $this->wpTerm, true) ?: [];
    }
}
