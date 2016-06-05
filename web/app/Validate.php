<?php

namespace App;

const SLACK_KEY = '5EVrWCHPRQTWP4y8ak4znfpr';

/**
 * Simple validator class to determine if the request string is what we expect
 * Class Validate
 * @package App
 */
class Validate{

    /**
     * Validates the request
     *
     * @param $request
     * @return bool
     */
    public static function request($request){

        /** Is the token valid? */
        if ($request->get('token') !== SLACK_KEY || $request->get('text') == '') {
            return false;
        };

        $components = explode(' ', $request->get('text'));

        /** If you're passing more than one spaced request, the second must be either close or add */
        if (count($components) !== 1) {
            if (!($components[1] == 'add' || $components[1] == 'close')) {
                return false;
            }
        }

        return true;

    }

}
