<?php
namespace OffbeatWP\Acf\Fields;

use OffbeatWP\Acf\Fields\Acf\AcfIconSelectField;
use OffbeatWP\AcfCore\Fields\AcfField;

class IconSelectField extends AcfField {
    public function __construct() {
        if (class_exists('acf_field')) {
            $this->setAttribute('acffield', [
                'type' => AcfIconSelectField::FIELD_NAME
            ]);
        }
    }
}