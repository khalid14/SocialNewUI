<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 7/24/2018
 * Time: 4:09 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class BaseModel extends Model implements Transformable
{
    use TransformableTrait;

    public function transform()
    {
        $reflect = new \ReflectionClass($this);
        $transformer = '\\App\\Transformers\\'.ucfirst($reflect->getShortName()).'Transformer';

        return class_exists($transformer) ? \App::make($transformer)->transform($this) : $this->toArray();
    }
}