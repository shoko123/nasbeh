<?php

namespace App\Http\Requests;

use App\Models\Functional\MediaModel;
use Illuminate\Validation\Rules\File;

class MediaModifyRequest extends DigRequest
{
    public function authorize(): bool
    {
        $p = $this->input('model').'-media';

        return $this->user('sanctum')->can($p);
    }

    public function rules(): array
    {
        $media_collections = MediaModel::media_collections();
        $media_collection_rule = 'nullable|in:'.implode(',', $media_collections);

        return [
            'model' => self::$rule_known_model_name,
            'model_id' => $this->rule_id_exists_in_model_table(),
            'media_id' => 'nullable|exists:media,id',
            'media_collection_name' => $media_collection_rule,
            'media_files.*' => [
                'nullable',
                File::image()
                    ->min(10)
                    ->max(3 * 1024),
            ],
        ];
    }
}
