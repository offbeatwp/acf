<?php

namespace OffbeatWP\Acf\Fields\Acf;

use acf_field;
use DateTimeZone;
use OffbeatWP\Support\Wordpress\WpDateTime;

class AcfDisabledTextField extends acf_field
{
    public function initialize()
    {
        $this->name = 'offbeat_disabled_text_field';
        $this->label = 'Disabled Field';
        $this->category = 'OffbeatWP';
    }

    public function render_field(array $field)
    {
        $value = $field['value'];
        $renderAs = $field['render_as'] ?? null;
        $renderAsMap = $this->getMapKeysAndValues($field['render_as_map'] ?? '');

        if ($renderAs === 'date_from_timestamp') {
            if (!is_numeric($value)) {
                $value = strtotime($value);
            }

            if ($value && is_string($value)) {
                $value = WpDateTime::createFromFormat('U', $value, new DateTimeZone('UTC'))->format();
            } else {
                $value = '';
            }
        } elseif ($renderAs === 'true_false') {
            $value = ($value) ? __('Yes') : __('No');
        } elseif ($renderAs === 'map_values' && array_key_exists($value, $renderAsMap)) {
            $value = $renderAsMap[$value];
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
            'label'   => 'Render as',
            'type'    => 'select',
            'name'    => 'render_as',
            'choices' => [
                'text' => 'Text',
                'date_from_timestamp' => 'Date from Timestamp',
                'true_false' => 'True/False',
                'map_values' => 'Map values'
            ]
        ]);

        acf_render_field_setting($field, [
            'label'   => 'Key Value Map',
            'type'    => 'textarea',
            'name'    => 'render_as_map',
            'conditional_logic' => [
                'status' => 'show',
                'rules' => [['field' => 'render_as', 'operator' => '==', 'value' => 'map_values']]
            ]
        ]);
    }

    /** @return array<string, string> */
    private function getMapKeysAndValues(string $value): array
    {
        $data = [];

        foreach (explode(PHP_EOL, $value) as $line) {
            $keyVal = explode(' : ', $line);

            if (count($keyVal) === 2) {
                $data[$keyVal[0]] = str_replace(["\r", "\n"], '', $keyVal[1]);
            }
        }

        return $data;
    }
}
