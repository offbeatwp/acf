<?php

namespace OffbeatWP\Acf\Fields;

use acf_field;
use Illuminate\Support\Carbon;

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
        $value = $field['value'];

        if ($field['render_as'] === 'date_from_timestamp') {
            $value = Carbon::createFromTimestamp($value)->format('d-m-Y H:m:s');
        }

        printf(
            '<input type="text" name="%s" value="%s" disabled="disabled" readonly>',
            esc_attr($field['name']),
            esc_attr($value)
        );
    }

    public function render_field_settings(array $field)
    {
        acf_render_field_setting($field, [
            'label'			=> 'Render as',
            'type'			=> 'select',
            'choices'       => ['text' => 'Text', 'date_from_timestamp' => 'Date from Timestamp'],
            'name'			=> 'render_as'
        ]);
    }
}
