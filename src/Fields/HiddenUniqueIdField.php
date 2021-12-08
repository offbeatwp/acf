<?php
namespace OffbeatWP\Acf\Fields;

use OffbeatWP\AcfCore\Fields\AcfField;

class HiddenUniqueIdField extends AcfField {
    public function __construct() {
        $this->setAttribute('acffield', [
            'type' => 'offbeat_auto_generated_id'
        ]);
    }
}