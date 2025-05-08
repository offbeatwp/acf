<?php

namespace OffbeatWP\Acf\Fields\Acf;

use acf_field;

class AcfIconSelectField extends acf_field
{
    const FIELD_NAME = 'offbeat_icon_select';

    public function initialize()
    {
        $this->name = self::FIELD_NAME;
        $this->label = __('Icon Select', 'offbeatwp');
        $this->category = 'OffbeatWP';
        $this->defaults = [
            'choices' => [],
            'default_value' => '',
            'return_format' => 'value',
            'layout' => 'horizontal',
        ];
    }

    public function render_field($field)
    {
        // vars
        $html = '';
        $buttons = [];
        $selectedValue = esc_attr($field['value']);

        // bail early if no choices
        if (empty($field['choices'])) {
            echo '<i>' . __('No selectable icons were detected', 'offbeatwp') . '</i>';
            return;
        }

        // buttons
        foreach ($field['choices'] as $value => $label) {
            // append
            $buttons[] = [
                'name' => $field['name'],
                'value' => $value,
                'label' => $label,
                'checked' => ($selectedValue === esc_attr($value)),
            ];
        }

        // div
        $div = ['class' => 'acf-button-group'];

        if ($field['layout'] === 'vertical') {
            $div['class'] .= ' -vertical';
        }

        if ($field['class']) {
            $div['class'] .= ' ' . $field['class'];
        }

        $div['data-allow_null'] = 1;

        // hidden input
        $html .= acf_get_hidden_input(['name' => $field['name']]);

        // open
        /** @noinspection PhpDeprecationInspection Replacement only exists since acf 5.8.1 */
        $html .= '<div ' . acf_esc_attr($div) . '>';

        // loop
        foreach ($buttons as $button) {
            // checked
            if ($button['checked']) {
                $button['checked'] = 'checked';
            } else {
                unset($button['checked']);
            }

            // append
            $html .= acf_get_radio_input($button);
        }

        // close
        $html .= '</div>';

        // return
        echo $html;
    }

    public function render_field_settings($field)
    {
        // encode choices (convert from array)
        $field['choices'] = acf_encode_choices($field['choices']);

        // layout
        acf_render_field_setting(
            $field,
            [
                'label' => __('Layout', 'acf'),
                'instructions' => '',
                'type' => 'radio',
                'name' => 'layout',
                'layout' => 'horizontal',
                'choices' => [
                    'horizontal' => __('Horizontal', 'acf'),
                    'vertical' => __('Vertical', 'acf'),
                ],
            ]
        );

        // return_format
        acf_render_field_setting(
            $field,
            [
                'label' => __('Return Value', 'acf'),
                'instructions' => __('Specify the returned value on front end', 'acf'),
                'type' => 'radio',
                'name' => 'return_format',
                'layout' => 'horizontal',
                'choices' => [
                    'value' => __('Value', 'acf'),
                    'label' => __('Label', 'acf'),
                    'array' => __('Both (Array)', 'acf'),
                ],
            ]
        );

        // subfolder
        acf_render_field_setting(
            $field,
            [
                'label' => __('Icon Subfolder', 'offbeatwp'),
                'instructions' => '',
                'type' => 'text',
                'name' => 'icon_subfolder',
                'layout' => 'horizontal'
            ]
        );
    }

    public function update_field($field)
    {
        return acf_get_field_type('radio')->update_field($field);
    }

    public function load_value($value, $post_id, $field)
    {
        return acf_get_field_type('radio')->load_value($value, $post_id, $field);
    }

    public function translate_field($field)
    {
        return acf_get_field_type('radio')->translate_field($field);
    }

    public function format_value($value, $post_id, $field)
    {
        return acf_get_field_type('radio')->format_value($value, $post_id, $field);
    }

    public function get_rest_schema(array $field)
    {
        $schema = parent::get_rest_schema($field);

        if (isset($field['default_value']) && $field['default_value'] !== '') {
            $schema['default'] = $field['default_value'];
        }

        /**
         * If a user has defined keys for the buttons,
         * we should use the keys for the available options to POST to,
         * since they are what is displayed in GET requests.
         */
        $button_keys = array_diff(
            array_keys($field['choices']),
            array_values($field['choices'])
        );

        $schema['enum'] = empty($button_keys) ? $field['choices'] : $button_keys;
        $schema['enum'][] = null;

        // Allow null via UI will value to empty string.
        $schema['enum'][] = '';

        return $schema;
    }

    public function input_admin_footer()
    {
        echo '<script>
        document.querySelectorAll(`.acf-field-offbeat-icon-select`).forEach((group) => {
            const iconLabels = group.querySelectorAll(`.acf-button-group label`);
            iconLabels.forEach((label) => {
                label.addEventListener(`click`, () => {
                    iconLabels.forEach((_label) => {
                        _label.classList.remove(`selected`);
                    });
                    
                    label.classList.add(`selected`);
                    label.querySelector(`input type=["radio"]`).click();
                });
            }); 
        });
        </script>';
    }

    public function input_admin_enqueue_scripts()
    {
        // style
        wp_enqueue_style('acf-icon-select', get_template_directory_uri() . '/vendor/offbeatwp/acf/assets/css/acf-icon-select.css', [], false);
    }
}