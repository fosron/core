<?php

namespace Apiato\Core\Abstracts\Transformers;

use Apiato\Core\Traits\CallableTrait;
use League\Fractal\TransformerAbstract as FractalTransformer;

/**
 * Class Transformer.
 *
 * @author  Mahmoud Zalt <mahmoud@zalt.me>
 */
abstract class Transformer extends FractalTransformer
{

    use CallableTrait;

    /**
     * @return  mixed
     */
    public function user()
    {
        return $this->call('Authentication@GetAuthenticatedUserTask');
    }

    /**
     * @param $adminResponse
     * @param $clientResponse
     *
     * @return  array
     */
    public function ifAdmin($adminResponse, $clientResponse)
    {
        $user = $this->user();

        if (!is_null($user) && $user->hasAdminRole()) {
            return array_merge($clientResponse, $adminResponse);
        }

        return $clientResponse;
    }

    /**
     * @param mixed                       $data
     * @param callable|FractalTransformer $transformer
     * @param null                        $resourceKey
     *
     * @return \League\Fractal\Resource\Item
     */
    public function item($data, $transformer, $resourceKey = null)
    {
        // set a default resource key if none is set
        if (!$resourceKey && $data) {
            $resourceKey = $data->getResourceKey();
        }

        return parent::item($data, $transformer, $resourceKey);
    }

    /**
     * @param mixed                       $data
     * @param callable|FractalTransformer $transformer
     * @param null                        $resourceKey
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function collection($data, $transformer, $resourceKey = null)
    {
        // set a default resource key if none is set
        if (!$resourceKey && $data->isNotEmpty()) {
            $obj = $data->first();
            $resourceKey = $obj->getResourceKey();
        }

        return parent::collection($data, $transformer, $resourceKey);
    }

}
