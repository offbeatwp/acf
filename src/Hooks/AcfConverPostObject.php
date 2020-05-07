<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Hooks\AbstractFilter;

class AcfConverPostObject extends AbstractFilter {
    public function filter($value, $postId, $field)
    {
        if ($field['return_format'] != 'object' || empty($value)) {
            return $value;
        }

        foreach ($value as $key => $postObject) {
            $value[$key] = offbeat('post')->get($postObject);
        }

        return $value;
    }
}