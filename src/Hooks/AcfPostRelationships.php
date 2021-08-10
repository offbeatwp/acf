<?php
namespace OffbeatWP\Acf\Hooks;

use Exception;
use OffbeatWP\Hooks\AbstractFilter;

class AcfPostRelationships extends AbstractFilter {
    /** @throws Exception */
    public function filter ($value, $postId, $field, $_value) {
        if (!is_numeric($postId)) {
            return $value;
        }

        $post = offbeat('post')->get($postId);

        if (!$post) {
            throw new Exception('Post not found');
        }

        $method = $post->getMethodByRelationKey($field['name']);

        if (!is_callable([$post, $method]) || is_null($method)) {
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

        if (!is_array($relationships)) {
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
