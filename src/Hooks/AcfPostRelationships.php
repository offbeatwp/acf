<?php
namespace OffbeatWP\Acf\Hooks;

use OffbeatWP\Exceptions\OffbeatModelNotFoundException;
use OffbeatWP\Hooks\AbstractFilter;

class AcfPostRelationships extends AbstractFilter {
    /**
     * @param mixed $value The new value.
     * @param int|string $postId The post ID where the value is saved.
     * @param array $field The field array containing all settings.
     * @param mixed $_value The original value before modification.
     * @return mixed
     */
    public function filter($value, $postId, $field, $_value) {
        self::updateRelation($value, $postId, $field['name']);
        return $value;
    }

    /**
     * @param mixed $value The new value.
     * @param int|numeric-string $postId The post ID to update.
     * @param int|string $metaKey The key of the meta-field that the relation is attached to.
     * @return void
     */
    public static function updateRelation($value, $postId, $metaKey) {
        if (!is_numeric($postId)) {
            return;
        }

        $post = offbeat('post')->get($postId);

        if (!$post) {
            throw new OffbeatModelNotFoundException('Post not found with id: ' . $postId);
        }

        $method = $post->getMethodByRelationKey($metaKey);

        if ($method === null || !is_callable([$post, $method])) {
            return;
        }

        if (!$value) {
            if (method_exists($post->$method(), 'detachAll')) {
                $post->$method()->detachAll();
            } else {
                $post->$method()->dissociateAll();
            }

            return;
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
    }
}
