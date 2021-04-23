<?php

namespace OffbeatWP\Acf\Fields;

use acf_field;

class HiddenUniqueIdField extends acf_field
{
    public function __construct()
    {
        $this->name = 'offbeat_auto_generated_id';
        $this->label = 'Hidden Unique Id';
        $this->category = 'OffbeatWP';

        parent::__construct();
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
            th[data-type="'. $this->name .'"], td[data-type="'. $this->name .'"] {
                display: none !important;
            }
        </style>');
    }

    public function update_value($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return uniqid();
    }
}
