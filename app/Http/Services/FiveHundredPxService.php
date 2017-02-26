<?php


namespace App\Http\Services;


use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\ClientException;
use App\Exceptions\HttpRequestException;
use App\Exceptions\ClientNotAuthenticatedException;
use GuzzleHttp\Subscriber\Oauth\Oauth1;


class FiveHundredPxService
{

    const API_URL = "https://api.500px.com/v1/";

    /** @var Client $client */
    private $client;

    /**
     * FiveHundredPxService constructor.
     * @param User $user
     */
    public function authenticateClient(User $user)
    {
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'token'           => $user->getAttribute('access_token'),
            'token_secret'    => $user->getAttribute('access_token_secret')
        ]);
        $stack->push($middleware);

        $this->client = new Client([
            'base_uri' => self::API_URL,
            'handler'  => $stack
        ]);
    }


    /**
     * @param string $endpoint
     * @param array $parameters
     * @return mixed
     * @throws HttpRequestException
     */
    public function get(string $endpoint, array $parameters = [])
    {
        return $this->request('GET', $endpoint, $parameters);
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @return mixed
     * @throws HttpRequestException
     */
    public function post(string $endpoint, array $parameters = [])
    {
        return $this->request('POST', $endpoint, $parameters);
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @return mixed
     * @throws HttpRequestException
     */
    public function delete(string $endpoint, array $parameters = [])
    {
        return $this->request('DELETE', $endpoint, $parameters);
    }

    /**
     * @param string|string $method
     * @param string|string $endpoint
     * @param array $parameters
     * @return mixed
     * @throws ClientNotAuthenticatedException
     * @throws HttpRequestException
     */
    public function request(string $method, string $endpoint, array $parameters = [])
    {
        if (!$this->client) {
            throw new ClientNotAuthenticatedException;
        }

        try {
            $request = $this->client->request($method, $endpoint, array_merge(['auth' => 'oauth'], $parameters));
        } catch (ClientException $e) {
            throw new HttpRequestException('There was a problem with your request. (' . $e->getMessage() . ')');
        }
        if ($request->getStatusCode() !== 200) {
            throw new HttpRequestException('There was a problem with your request. (' . $request->getStatusCode() . ')',
                $request->getStatusCode());
        }

        return json_decode($request->getBody());

    }

    /**
     * Serializes a user endpoint result
     * to an Eloquent model.
     *
     * @param $follow
     * @return \App\FiveHundredPxFollow
     */
    public function serializeFollow(\stdClass $follow, FiveHundredPxUser $user)
    {
        $fiveHundredFollow = new \App\FiveHundredPxFollow();
        $fiveHundredFollow->follow_id = $follow->id;
        $fiveHundredFollow->name = $follow->fullname ?? null;
        $fiveHundredFollow->nickname = $follow->username ?? null;
        $fiveHundredFollow->avatar = $follow->userpic_url ?? null;
        $fiveHundredFollow->email = $follow->email ?? null;
        $fiveHundredFollow->fiveHundredUser()->associate($user);
        $fiveHundredFollow->save();

        return $fiveHundredFollow;
    }


}