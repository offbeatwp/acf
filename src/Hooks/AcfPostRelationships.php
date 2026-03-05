<?php

namespace OffbeatWP\Acf\Hooks;

use Exception;
use OffbeatWP\Content\Post\PostModel;
use OffbeatWP\Hooks\AbstractFilter;

class AcfPostRelationships extends AbstractFilter
{
    const RELATION_FIELD_TYPES = ['relationship', 'post_object'];

    /**
     * @throws Exception
     * @param mixed $value
     * @param int|string $postId
     * @param array $field
     * @return mixed
     */
    public function filter($value, $postId, $field)
    {
        if (!is_numeric($postId)) {
            return $value;
        }

        $post = offbeat('post')->get($postId);
        if (!$post) {
            throw new Exception('Post not found');
        }

        if ($field['type'] === 'repeater' && !empty($field['sub_fields'])) {
            foreach ($field['sub_fields'] as $subfield) {
                if (!in_array($subfield['type'], self::RELATION_FIELD_TYPES)) {
                    continue;
                }

                $this->applyRelationships(
                    $this->aggregateRelationshipsFromRepeaterField(is_array($value) ? $value : [], $subfield['key']),
                    $post,
                    $field['name'] . ':' . $subfield['name'],
                );
            }
        } elseif (in_array($field['type'], self::RELATION_FIELD_TYPES, true)) {
            $this->applyRelationships(
                !is_array($value) ? [$value] : $value,
                $post,
                $field['name']
            );
        }

        return $value;
    }

    private function aggregateRelationshipsFromRepeaterField(array $value, string $fieldKey): array
    {
        return array_reduce($value, function ($a, $item) use ($fieldKey) {
            $fieldValue = $item[$fieldKey] ?? [];
            return array_merge($a, is_array($fieldValue) ? $fieldValue : [$fieldValue]);
        }, []);
    }

    private function applyRelationships(array $relationships, PostModel $post, string $key): void
    {
        $method = $post->getMethodByRelationKey($key);
        if (!$method || !is_callable([$post, $method])) {
            return;
        }

        $relationships = array_map('intval', $relationships);

        if (method_exists($post->$method(), 'attach')) {
            $post->$method()->attach($relationships, false);
        } else {
            $post->$method()->associate($relationships, false);
        }
    }
}
