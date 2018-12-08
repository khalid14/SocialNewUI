<?php

namespace App\Repositories;

use App\Contracts\DefinitionRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\SettingRepository;
use App\Models\Setting;
use App\Validators\SettingValidator;

/**
 * Class SettingRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SettingRepositoryEloquent extends BaseRepository implements SettingRepository
{

    /**
     * @var DefinitionRepository
     */
    public $defninitionsRepo;

    public function __construct(Application $app, DefinitionRepository $definitionRepository)
    {
        parent::__construct($app);
        $this->defninitionsRepo = $definitionRepository;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Setting::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $attributes
     * @param $id
     * @return array|mixed
     */
    public function update(array $attributes, $id)
    {
        try {
            $model = $this->model->findOrFail($id);

            // ----- updating settings
            $model->enabled = $attributes['masterSwitch'];
            $model->eu_only = $attributes['audience'] == 'eu' ? true : false;
            $model->blocking_mode = $attributes['blockingMode'];
            $model->position = $attributes['position'];
            $model->comply = $attributes['comply'];
            $model->color_bg = $attributes['candy']['bg']['hex'];
            $model->color_button = $attributes['candy']['button']['hex'];
            $model->save();

            $status = $this->defninitionsRepo->syncDefinitions($model, $attributes['definitions']);
            return  [
                'status' => true
            ];
        } catch (\Exception $e) {
            return  [
                'status' => false
            ];
        }


    }
}
