<?php


if ( ! function_exists('trans'))
{
    $app_info = [];

    /**
     * Translate the given message.
     *
     * @param  string  $id
     * @param  array   $parameters
     * @param  string  $domain
     * @param  string  $locale
     * @return string
     */
    function trans($id = null, $parameters = array(), $domain = 'messages', $locale = null)
    {
        if (is_null($id)) return app('translator');

        # [INFO] To add info app just add INFO_APP_ prefix to .env file
        global $app_info;
        if(sizeof($app_info) == 0) {
            $app_info = array_filter($_ENV, function($k) {
                return strpos($k, 'INFO_APP_') === 0;
            }, ARRAY_FILTER_USE_KEY);
        }

        $parameters = array_merge($parameters, $app_info);

        return app('translator')->trans($id, $parameters, $domain, $locale);
    }
}
