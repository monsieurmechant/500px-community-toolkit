<?php


namespace App\Services\Api;


use League\Fractal;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Collection as Resource;

class CreateApiDataFromCollection
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
     * an Eloquent Collection.
     *
     * @param Collection|null $collection
     * @return array
     * @throws \Exception
     */
    public function handle($collection = null)
    {
        if ($collection && get_class($collection) === Collection::class) {
            $this->with($collection);
        }

        if(!$this->hasResource()) {
            throw new \Exception('You must provide a valid \Illuminate\Database\Eloquent\Collection.');
        }

        return $this->manager->createData($this->resource)->toArray();
    }

    /**
     * Sets the collection that will be used
     * to return a transformed array.
     *
     * @param Collection $collection
     * @return $this
     */
    public function with(Collection $collection)
    {
        $this->resource = new Resource(
            $collection, $this->getTransformerFromCollection($collection)
        );
        return $this;
    }

    /**
     * This method looks at the request relationships
     * and eager-loads them. It also eager-loads
     * all the default includes in each
     * relationship transformer.
     *
     * @see http://fractal.thephpleague.com/transformers/#including-data
     * @see CreateApiDataFromCollectionTest::testItAcceptsSomeIncludes()
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
            $includeClassName = ucfirst(trim($include));
            if (strpos($includeClassName, 's', strlen($includeClassName) - 1) !== false) {
                $includeClassName = substr($includeClassName, 0, -1);
            }

            $includeClassName = '\App\Transformers\\' . ucfirst($includeClassName) . 'Transformer';

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
     * Sets a cursor for pagination on the resource.
     *
     * @see http://fractal.thephpleague.com/pagination/#using-cursors
     * @see CreateApiDataFromCollectionTest::testItAcceptsACursor()
     * @param Fractal\Pagination\Cursor $cursor
     * @return $this
     * @throws \Exception
     */
    public function cursor(Fractal\Pagination\Cursor $cursor)
    {
        if(!$this->hasResource()) {
            throw new \Exception('You must provide a valid \Illuminate\Database\Eloquent\Collection.');
        }

        $this->resource->setCursor($cursor);
        return $this;
    }
    /**
     * This function returns the Fractal Transformer
     * for any given Eloquent Collection. It uses
     * reflection to get the class name of the
     * first Eloquent Model in the collection.
     *
     * @see CreateApiDataFromCollectionTest::testItReturnsAFractalTransformerFromACollection()
     * @param Collection $collection
     * @return Fractal\TransformerAbstract
     */
    public function getTransformerFromCollection(Collection $collection)
    {
        $className = ('\App\Transformers\\' . (new \ReflectionClass($collection->first()))->getShortName() . 'Transformer');

        return new $className;
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

    /**
     * Returns the cursor object
     *
     * @see http://fractal.thephpleague.com/transformers/#including-data
     * @return null|Fractal\Pagination\Cursor
     */
    public function getCursor()
    {
        return $this->resource->getCursor();
    }

}