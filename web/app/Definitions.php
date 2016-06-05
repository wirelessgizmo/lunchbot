<?php

namespace App;

final class Definitions
{

    const REQUEST_HELP = 'help';
    const REQUEST_LIST = 'list';
    const REQUEST_CLOSE = 'close';

    static $HINT_TEXT = array(
        'response_type' => 'ephemeral',
        'text' => 'Use `/lunch` to collaborate on lunch orders',
        'attachments' => array(array('text' => '`/lunch Oporto`
    `/lunch Oporto add Large Bondi Meal`
    `/lunch list`
    `/lunch Oporto close`',
            "mrkdwn_in" => array(
                "text",
            ))));

}