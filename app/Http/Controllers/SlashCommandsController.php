<?php

namespace App\Http\Controllers;

use App\Http\Api\SlashCommandsApi;
use Illuminate\Http\Request;

class SlashCommandsController extends Controller
{
    /**
     * @param Request $request
     * @return false|string
     *
     * Parameters received by POST are:
     * -> command => /canais
     * -> text => text that act as an parameter when calling slash command
     * -> response_url' => Temporary webhook URL to answer to generate response messages
     * -> trigger_id' => Short-lived ID that allows to deal with modals
     * -> user_id' => ID of user that trigger command
     * -> user_name' => deprecated parameter
     * -> channel_id' => ID of channel that called command
     */
    public function slashChannel(Request $request)
    {
        if ($request['text'] == 'help') {
            return $this->slashChannelHelp();
        }

        // Call API and return 400 if result it's no OK
        $slashCommandApi = new SlashCommandsApi();
        $apiResult = $slashCommandApi->callApiChannelsListage();
        $apiResult = json_decode($apiResult);

        if (!$apiResult->ok) {
            return 400;
        } else {
            $resultData = '';
            $channels = array();

            // Build another array just with the data we want
            foreach ($apiResult->channels as $item) {
                $channels[$item->name] = ['id' => $item->id, 'name' => $item->name, 'description' => $item->purpose->value];
            }

            if (isset($request['text'])) {
                $regex = "/^" . $request['text'] . "(\w+)/";
                $channelsThatMatchFilter = preg_grep($regex, array_keys($channels));
                $channels = array_intersect_key($channels, array_flip($channelsThatMatchFilter));
            }

            foreach ($channels as $channel) {
                $tmp = "<#%channelId%|%channelName%> :: %description%\n";

                $tmp = str_replace('%channelId%', $channel['id'], $tmp);
                $tmp = str_replace('%channelName%', $channel['name'], $tmp);
                $tmp = str_replace('%description%', $channel['description'], $tmp);

                $resultData .= $tmp;
            }
        }

        $arrayResult = array(
            'response_type' => 'ephemeral',
            'text' => $resultData
        );

        return json_encode($arrayResult, JSON_UNESCAPED_LINE_TERMINATORS | JSON_UNESCAPED_UNICODE);
    }

    /**
     *
     */
    public function slashChannelHelp()
    {
        $resultData = array();
//        $resultData['response_type'] = 'ephemeral';
//        $resultData['text'] = '✔ Use `/canais` para listar todos os canais\n✔ Use `/canais PALAVRA` para listar todos os canais iniciando com *PALAVRA*';

        $resultData['blocks'] = [
            array(
                'type' => 'section',
                'text' =>  array(
                    'type' => 'mrkdwn',
                    'text' => ''
                )
            )
        ];

//        return json_encode($resultData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return '✔ Use `/canais` para listar todos os canais\n✔ Use `/canais PALAVRA` para listar todos os canais iniciando com *PALAVRA*';
    }
}
