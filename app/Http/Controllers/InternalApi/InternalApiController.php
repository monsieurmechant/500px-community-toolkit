<?php

namespace App\Http\Controllers\InternalApi;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InternalApiController extends Controller
{

    /**
     * Returns a formatted JSON Api Response
     *
     * @param Model $model
     * @param int $code
     * @param null|string $includes the includes parameter
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnItem(Model $model, $code = 200, $includes = null)
    {

        /** @var \App\Services\Api\CreateApiDataFromModel $resource */
        $resource = resolve(\App\Services\Api\CreateApiDataFromModel::class)->with($model);

        if ($includes) {
            $resource->includes($includes);
        }

        try {
            return response()->json($resource->handle(), $code);
        } catch (BadRequestHttpException $e) {
            return $this->returnError(400, $e->getMessage());
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }

    /**
     * Returns a formatted JSON Api Response
     * of a Collection of Items.
     *
     * @param Collection $collection
     * @param int $code
     * @param null|string $includes the includes parameter
     * @param \League\Fractal\Pagination\Cursor|null $cursor
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnCollection(Collection $collection, $code = 200, $includes = null, $cursor = null)
    {
        if ($collection->count() < 1) {
            return response()->json([], $code);
        }

        /** @var \App\Services\Api\CreateApiDataFromCollection $resource */
        $resource = resolve(\App\Services\Api\CreateApiDataFromCollection::class)->with($collection);

        if ($includes) {
            $resource->includes($includes);
        }

        if ($cursor) {
            $resource->cursor($cursor);
        }

        try {
            return response()->json($resource->handle(), $code);
        } catch (BadRequestHttpException $e) {
            return $this->returnError(400, $e->getMessage());
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }

    /**
     * Returns a formatted error message
     *
     * @param int $code
     * @param string $message
     * @param null|string $type
     * @return \Illuminate\Http\JsonResponse
     */
    protected final function returnError($code, $message, $type = null)
    {
        $response = [
            'message' => $message,
        ];

        if ($type) {
            $response['type'] = $type;
        }
        return response()->json(['error' => $response], $code);
    }

    /**
     * Formats a form date using the a timezone.
     * Returns a DateTimeString.
     *
     * @param $date
     * @param \App\User $user
     * @return string
     */
    protected function inputDate($date, \App\User $user)
    {
        $date = Carbon::parse($date, $user->timezone->getAttribute('utc')[0]);
        return $date->setTimezone(Carbon::now()->tz)->toDateTimeString();
    }
}
