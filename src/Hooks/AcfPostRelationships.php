<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Hooks\AbstractFilter;

class AcfPostRelationships extends AbstractFilter {
    public function filter ($value, $postId, $field, $_value) {
        if (!is_numeric($postId)) {
            return $value;
        }

        $post = offbeat('post')->get($postId);
        $method = $post->getMethodByRelationKey($field['name']);

        if (is_null($method) || !is_callable([$post, $method])) {
            return $value;
        }

        if (empty($value)) {
            if (method_exists($post->$method(), 'detachAll')) {
                $post->$method()->detachAll();
            } else {
                $post->$method()->dissociateAll();
            }

            return $value;
        }

        $relationships = $value;

        if (!is_array($relationships) && !empty($relationships)) {
            $relationships = [$relationships];
        }

        $relationships = array_map('intval', $relationships);

        if (method_exists($post->$method(), 'attach')) {
            $post->$method()->attach($relationships, false);
        } else {
            $post->$method()->associate($relationships, false);
        }
        
        return $value;
    }
}
