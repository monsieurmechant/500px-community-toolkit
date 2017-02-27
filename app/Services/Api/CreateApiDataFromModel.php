<?php


namespace App\Services\Api;

use League\Fractal;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Resource\Item as Resource;

class CreateApiDataFromModel
{

    /** @var Fractal\Manager $manager */
    protected $manager;

    /**  @var Resource $resource */
    protected $resource;

    public function __construct(Fractal\Manager $manager)
    {
        $manager->setSerializer(new Fractal\Serializer\DataArraySerializer());
        $this->manager = $manager;
    }

    /**
     * This class returns a Transformed array from
     * an Eloquent Model.
     *
     * @param null|Model $model
     * @return array
     * @throws \Exception
     */
    public function handle($model = null)
    {

        if ($model && $model instanceof Model) {
            $this->with($model);
        }

        if(!$this->hasResource()) {
            throw new \Exception('You must provide a valid \Illuminate\Database\Eloquent\Model.');
        }

        return $this->manager->createData($this->resource)->toArray();
    }

    /**
     * Sets the model that will be used
     * to return a transformed array.
     *
     * @param Model $model
     * @return $this
     */
    public function with(Model $model)
    {
        $this->resource = new Resource(
            $model, $this->getTransformerFromModel($model)
        );
        return $this;
    }

    /**
     * This function returns the Fractal Transformer
     * for any given Eloquent Model. It uses
     * reflection to get the class name
     * of the Model.
     *
     * @param Model $model
     * @return Fractal\TransformerAbstract
     */
    public function getTransformerFromModel(Model $model)
    {
        $className = ('\App\Transformers\\' . (new \ReflectionClass($model))->getShortName() . 'Transformer');
        return new $className;
    }


    /**
     * This method looks at the request relationships
     * and eager-loads them. It also eager-loads
     * all the default includes in each
     * relationship transformer.
     *
     * @param array|string $includes Array or csv string of resources to include
     * @return $this
     * @throws \Exception
     */
    public function includes($includes)
    {
        if(!$this->hasResource()) {
            throw new \Exception('You must provide a valid \Illuminate\Database\Eloquent\Collection.');
        }

        $this->manager->parseIncludes($includes);
        $includes = [];
        foreach ($this->manager->getRequestedIncludes() as $include) {
            $includeClassName = ucfirst($include);
            if (strpos($includeClassName, 's', strlen($includeClassName) - 1) !== false) {
                $includeClassName = substr($includeClassName, 0, -1);
            }

            $includeClassName = '\App\Transformers\\' . $includeClassName . 'Transformer';

            if (!class_exists($includeClassName)) {
                continue;
            }
            foreach ((new $includeClassName)->getDefaultIncludes() as $defaultInclude) {
                $includes[] = $include . '.' . $defaultInclude;
            }
        }
        $this->getResource()->getData()->load(...$includes);

        return $this;
    }

    /**
     * Does this class has a collection.
     *
     * @return bool
     */
    public function hasResource()
    {
        return isset($this->resource);
    }

    /**
     * Returns the class collection.
     *
     * @return \League\Fractal\Resource\ResourceInterface|null
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Returns the includes requested
     *
     * @see http://fractal.thephpleague.com/transformers/#including-data
     * @return array
     */
    public function getIncludes()
    {
        return $this->manager->getRequestedIncludes();
    }

}