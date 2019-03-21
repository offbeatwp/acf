<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Hooks\AbstractFilter;

class AcfPostRelationships extends AbstractFilter {
    public function filter ($value, $postId, $field, $_value) {
        $relationships = $value;

        if (!is_array($relationships) && !empty($relationships)) {
            $relationships = [$relationships];
        }
        $relationships = array_map('intval', $relationships);

        $post = offbeat('post')->get($postId);

        $method = $post->getMethodByRelationKey($field['name']);
        if (!is_null($method) && is_callable([$post, $method])) {
            if (method_exists($post->$method(), 'attach')) {
                $post->$method()->attach($relationships, false);
            } else {
                $post->$method()->associate($relationships, false);
            }
        }

        return $value;
    }
}
