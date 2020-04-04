<?php

namespace App\Http\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class SlashCommandsApi extends Controller
{
    const CONVERSATION_LIST_URL = 'https://slack.com/api/conversations.list';

    public function callApiChannelsListage()
    {
        $requestParams = [
            'token' => env('SLACK_BOT_TOKEN'),
            'exclude_archived' => false,
            'limit' => 1000,
            'types' => 'public_channel'
        ];

        // Call Slack API to get entire channel list
        $client = new Client();
        $dataResponse = $client->post(self::CONVERSATION_LIST_URL, ['form_params' => $requestParams]);

        return $dataResponse->getBody()->getContents();
    }
}
