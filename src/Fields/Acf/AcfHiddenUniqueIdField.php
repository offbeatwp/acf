<?php

namespace OffbeatWP\Acf\Fields\Acf;

use acf_field;

class AcfHiddenUniqueIdField extends acf_field
{
    public function initialize()
    {
        $this->name = 'offbeat_auto_generated_id';
        $this->label = 'Hidden Unique Id';
        $this->category = 'OffbeatWP';
    }

    public function render_field(array $field)
    {
        printf(
            '<input type="hidden" name="%s" value="%s" readonly>',
            esc_attr($field['name']),
            esc_attr($field['value'])
        );
    }

    public function input_admin_head()
    {
        printf('<style>
            [data-type="'. $this->name .'"]:not(.acf-field-object) {
                display: none !important;
            }
        </style>');
    }

    public function update_value($value): string
    {
        return $value ?: uniqid('ob', false);
    }
}
