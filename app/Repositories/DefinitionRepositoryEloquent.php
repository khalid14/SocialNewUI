<?php

namespace App\Repositories;

use App\Models\Setting;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\DefinitionRepository;
use App\Models\Definition;
use App\Validators\DefinitionValidator;

/**
 * Class DefinitionRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class DefinitionRepositoryEloquent extends BaseRepository implements DefinitionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Definition::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param Setting $setting
     * @param $definitions
     * @return bool
     */
    public function syncDefinitions(Setting $setting, $definitions) {
        try {
            // ----- removing old definitions
            foreach ($setting->definitions as $crntDefinition) {
                $crntDefinition->delete();
            }

            // ----- creating new definitions
            foreach ($definitions as $crntDefinition) {
                $newDef = $this->create([
                    'setting_id'    => $setting->id,
                    'lang'          => $crntDefinition['lang'],
                    'definition'    => $crntDefinition['definition']
                ]);
                $newDef->save();
                $newDefs[] = $newDef;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
}
