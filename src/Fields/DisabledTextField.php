<?php
namespace OffbeatWP\Acf\Fields;

use OffbeatWP\AcfCore\Fields\AcfField;

class DisabledTextField extends AcfField {
    public function init(): void
    {
        $this->setAttribute('acffield', [
            'type' => 'offbeat_disabled_text_field'
        ]);
    }
}