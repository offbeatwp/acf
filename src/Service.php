<?php

namespace OffbeatWP\Acf;

use OffbeatWP\Acf\Fields\Acf\AcfDisabledTextField;
use OffbeatWP\Acf\Fields\Acf\AcfHiddenUniqueIdField;
use OffbeatWP\Acf\Fields\Acf\AcfIconSelectField;
use OffbeatWP\Acf\Fields\Acf\AcfThemeColorField;
use OffbeatWP\Acf\Hooks\AcfConvertPostObject;
use OffbeatWP\Acf\Hooks\AcfLoadFieldIconsFilter;
use OffbeatWP\Acf\Hooks\AcfPostAttributeFilter;
use OffbeatWP\Acf\Hooks\AcfPostRelationships;
use OffbeatWP\Acf\Hooks\AcfTermAttributeFilter;
use OffbeatWP\Content\Taxonomy\TermModel;
use OffbeatWP\Services\AbstractService;
use OffbeatWP\Content\Post\PostModel;

class Service extends AbstractService
{

    public function register()
    {
        if (function_exists('get_field')) {
            $this->registerAttributeHooks();
        }

        $this->registerMacros();
        $this->registerRelationHooks();
        $this->registerFields();
    }

    // Registry Methods
    private function registerAttributeHooks(): void
    {
        offbeat('hooks')->addFilter('post_attribute', AcfPostAttributeFilter::class, 10, 3);
        offbeat('hooks')->addFilter('term_attribute', AcfTermAttributeFilter::class, 10, 3);
        offbeat('hooks')->addFilter('acf/load_field', AcfLoadFieldIconsFilter::class, 10, 3);
    }

    private function registerMacros(): void
    {
        PostModel::macro('getField', function ($name, $format = true) {
            if (!function_exists('get_field')) {
                return null;
            }

            /** @var PostModel $this */
            return get_field($name, $this->getId(), $format);
        });

        PostModel::macro('getFieldObject', function ($name, $format = true) {
            if (!function_exists('get_field_object')) {
                return null;
            }

            /** @var PostModel $this */
            return get_field_object($name, $this->getId(), $format);
        });

        PostModel::macro('updateField', function ($name, $value) {
            if (!function_exists('update_field')) {
                return null;
            }

            /** @var PostModel $this */
            return update_field($name, $value, $this->getId());
        });

        TermModel::macro('getField', function ($name, $format = true) {
            if (!function_exists('get_field')) {
                return null;
            }

            /** @var TermModel $this */
            return get_field($name, $this->wpTerm, $format);
        });
    }

    private function registerRelationHooks(): void
    {

        offbeat('hooks')->addFilter('acf/update_value/type=repeater', AcfPostRelationships::class, 10, 3);
        foreach (AcfPostRelationships::RELATION_FIELD_TYPES as $fieldType) {
            offbeat('hooks')->addFilter('acf/update_value/type=' . $fieldType, AcfPostRelationships::class, 10, 3);
        }

        offbeat('hooks')->addFilter('acf/format_value/type=relationship', AcfConvertPostObject::class, 99, 3);
    }

    private function registerFields(): void
    {
        if (function_exists('add_action') && class_exists('acf_field')) {
            add_action('acf/include_field_types', static function () {
                new AcfHiddenUniqueIdField();
                new AcfDisabledTextField();
                new AcfThemeColorField();
                new AcfIconSelectField();
            });
        }
    }
}
