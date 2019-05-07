<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Validation\ValidationException;
use Exception;

class GeoLocationController extends Controller
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->guzzleClient = $client;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function get(Request $request): JsonResponse
    {
        $this->validateRequest($request);

        try {
            $response = $this->guzzleClient->request('GET',
                'https://maps.googleapis.com/maps/api/geocode/json', [
                    'query' => [
                        'address' => $request->address,
                        'key' => env('GOOGLE_API_KEY'),
                        'language' => 'ja',
                    ],
                ]);

            return response()->json(json_decode($response->getBody()->getContents()));

        } catch (Exception $e) {
            return response()->json(json_decode($e->getMessage()));
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function validateRequest(Request $request): array
    {
        return $this->validate($request, [
            'address' => ['required', 'string', 'max:100'],
        ]);
    }
}
