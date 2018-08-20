<?php

namespace CrashTestRatings\Http\Controllers;

use Illuminate\Http\Request;

class CrashTest extends Controller
{
    /**
     * $apiEndPoint NHTSA API endpoint
     * @var string
     */
    private $apiEndPoint = 'https://one.nhtsa.gov/webapi/api/SafetyRatings';

    /**
     * $defaultResponse Default response for our API
     * @var array
     */
    private $defaultResponse = ['Count' => 0, 'Results' => []];

    /**
     * Get vehicles crash test data in JSON
     * @param  Request $request      Object of Illuminate\Http\Request
     * @param  string  $modelYear    Vehicle Model Year
     * @param  string  $manufacturer Vehicle Manufacturer
     * @param  string  $model        Vehicle Model
     * @return json
     */
    public function getVehiclesReport(Request $request, $modelYear = '', $manufacturer = '', $model = '')
    {
        $input = $request->all(); // For POST Request

        // For GET Request
        $input['modelYear']    = isset($input['modelYear']) && $input['modelYear'] != ''
            ? $input['modelYear'] : $modelYear;
        $input['manufacturer'] = isset($input['manufacturer']) && $input['manufacturer'] != ''
            ? $input['manufacturer'] : $manufacturer;
        $input['model']        = isset($input['model']) && $input['model'] != '' ? $input['model'] : $model;

        $response = $this->defaultResponse; // Default response

        if ($input['modelYear'] != '' && $input['manufacturer'] != '' && $input['model'] != '') {
            $json     = $this->getVehiclesData($input);
            $response = json_decode($json, true); // true to get output in array

            // If API is working fine
            if (isset($response) && isset($response['Results']) && count($response['Results']) > 0) {
                unset($response['Message']); // Removing Message key from API's response

                foreach ($response['Results'] as $key => $vehicle) {
                    $response['Results'][$key]['Description'] = $response['Results'][$key]['VehicleDescription'];
                    unset($response['Results'][$key]['VehicleDescription']);

                    if (isset($input['withRating']) && $input['withRating'] == 'true') {
                        $response['Results'][$key]['CrashRating'] = $this->getVehicleRating($vehicle['VehicleId']);
                    }
                }
            } else {
                $response = $this->defaultResponse;
            }
        }

        return response()->json($response);
    }

    /**
     * Get vehicles data from NHTSA API in JSON format
     * @param  array $input Array of vehicle input i.e. modelYear, manufacturer, and model
     * @return json
     */
    private function getVehiclesData($input)
    {
        $apiUrl = $this->apiEndPoint;
        $apiUrl .= '/modelyear/' . $input['modelYear'];
        $apiUrl .= '/make/' . urlencode($input['manufacturer']);
        $apiUrl .= '/model/' . urlencode($input['model']) . '?format=json';

        return file_get_contents($apiUrl);
    }

    /**
     * Get individual vehicle's rating
     * @param  integer $vehicleId Vehicle ID returned by NHTSA API
     * @return integer|string
     */
    private function getVehicleRating($vehicleId)
    {
        $apiUrl = $this->apiEndPoint . '/vehicleId/' . $vehicleId . '?format=json';

        $rating = 'Not Rated'; // Default rating, even if API do not return anything

        if ($response = file_get_contents($apiUrl)) {
            $data = json_decode($response, true); // true to get output in array

            // API is working fine
            if (isset($data['Results']) && isset($data['Results'][0]) && isset($data['Results'][0]['OverallRating'])) {
                $rating = $data['Results'][0]['OverallRating'];
            }
        }

        return $rating;
    }
}
