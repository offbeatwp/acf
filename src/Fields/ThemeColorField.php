<?php
namespace OffbeatWP\Acf\Fields;

use OffbeatWP\AcfCore\Fields\AcfField;

class ThemeColorField extends AcfField {
    public function __construct() {
        $this->setAttribute('acffield', [
            'type' => 'offbeat_theme_color_field'
        ]);
    }
}