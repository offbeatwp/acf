<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Hooks\AbstractFilter;

class AcfConverPostObject extends AbstractFilter {
    /**
     * @param mixed $value
     * @param int|numeric-string $postId
     * @param array $field
     * @return mixed
     */
    public function filter($value, $postId, $field)
    {
        if ($field['return_format'] !== 'object' || !$value) {
            return $value;
        }

        foreach ($value as $key => $postObject) {
            $value[$key] = offbeat('post')->get($postObject);
        }

        return $value;
    }
}