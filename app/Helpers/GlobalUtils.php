<?php


namespace App\Helpers;


use App\GlobalParams;
use Illuminate\Contracts\Validation\Validator;

class GlobalUtils
{
    /**
     * Computing distance between two geographical points.
     * Used Vincenty formula.
     *
     * @param $geo1
     * @param $geo2
     * @return float|int Distance between points in km
     */
    public static function geoDistance($geo1, $geo2)
    {
        $latFrom = deg2rad($geo1['lat']);
        $lonFrom = deg2rad($geo1['long']);
        $latTo = deg2rad($geo2['lat']);
        $lonTo = deg2rad($geo2['long']);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * GlobalParams::EARTH_RADIUS;
    }

    public static function validatiorErrorResponse(Validator $validator)
    {
        return response()->json(['status' => 'error', 'messages' => $validator->getMessageBag()->all()])->setStatusCode(400);
    }
}
