<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Setting;

/**
 * Class SettingTransformer.
 *
 * @package namespace App\Transformers;
 */
class SettingTransformer extends TransformerAbstract
{
    /**
     * Transform the Setting entity.
     *
     * @param \App\Models\Setting $model
     *
     * @return array
     */
    public function transform(Setting $model)
    {
        $data = [
            'id'            => $model->id,
            'audience'      => (bool) $model->eu_only == true ? 'eu' : 'world',
            'shop'          => $model->shop->name,
            'masterSwitch'  => (bool) $model->enabled,
            'blockingMode'  => (bool) $model->blocking_mode,
            'position'      => $model->position,
            'comply'        => $model->comply,
            'candy'         => [
                'bg'    => ['hex' => $model->color_bg],
                'button'=> ['hex' => $model->color_button]
            ],
            'definitions'   => $model->definitions->toArray()
        ];

        return $data;
    }
}
