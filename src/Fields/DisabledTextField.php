<?php
namespace OffbeatWP\Acf\Fields\Acf;

use OffbeatWP\AcfCore\Fields\AcfField;

class DisabledTextField extends AcfField {
    public function __construct() {
        $this->setAttribute('acffield', [
            'type' => 'offbeat_disabled_text_field'
        ]);
    }
}