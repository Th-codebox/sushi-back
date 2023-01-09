<?php

namespace App\Services\CRM\Traits;

use Illuminate\Support\Str;

trait Slug
{
    /**
     * @param array $data
     * @param string|null $slug
     * @return string
     */
    protected static function generateSlug(array $data, ?string $slug): string
    {
        $generatedSlug = '';

        $id = ((array_key_exists('id', $data) && (is_string($data['id']) || is_int($data['id']))) ? $data['id'] : null);

        if ($slug) {
            $generatedSlug .= Str::slug($slug);
        } else {
            $generatedSlug .= ((array_key_exists('name', $data) && (is_string($data['name']) || is_int($data['name']))) ? Str::slug($data['name']) : $id);
        }

        try {
            $slugExist = static::findOne(['slug' => $generatedSlug, '!id' => $id]);
        } catch (\Throwable $e) {
            $slugExist = false;
        }

        while ($slugExist !== false) {

            $generatedSlug .= '--' . ($id ?? strtolower(Str::random(2)));

            try {
                $slugExist = static::findOne(['slug' => $generatedSlug, '!id' => $id]);
            } catch (\Throwable $e) {
                $slugExist = false;
            }
        }

        return $generatedSlug;
    }
}
