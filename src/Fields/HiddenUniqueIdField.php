<?php
namespace OffbeatWP\Acf\Fields;

use OffbeatWP\AcfCore\Fields\AcfField;

class HiddenUniqueIdField extends AcfField {
    public function init(): void
    {
        $this->setAttribute('acffield', [
            'type' => 'offbeat_auto_generated_id'
        ]);
    }
}