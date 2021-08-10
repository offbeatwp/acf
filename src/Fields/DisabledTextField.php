<?php

namespace OffbeatWP\Acf\Fields;

use acf_field;

class DisabledTextField extends acf_field
{
    public function __construct()
    {
        $this->name = 'offbeat_disabled_text_field';
        $this->label = 'Disabled Field';
        $this->category = 'OffbeatWP';

        parent::__construct();
    }

    public function render_field(array $field)
    {
        printf(
            '<input type="text" name="%s" value="%s" disabled="disabled" readonly>',
            esc_attr($field['name']),
            esc_attr($field['value'])
        );
    }
}
