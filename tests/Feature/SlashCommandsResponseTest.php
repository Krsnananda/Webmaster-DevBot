<?php

namespace Tests\Feature;

use Tests\TestCase;

class SlashCommandsResponseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetSlashChannelAllChannels()
    {
        putenv('SLACK_BOT_TOKEN=xoxb-991511022660-1037911013601-TQLAYIYbODjIZoCElbYBXRXj');
        // Should call SlashCommandsApi@getChannelsListage and get a JSON response with at least one channel
        // Be sure that src-home was listed, since it's the main channel inside workspace
        $dataSent = array(
            'command' => '/canais',
            'text' => '',
            'response_url' => 'https://hooks.slack.com/services/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',
            'trigger_id' => '',
            'user_id' => 'UUWNYF5CH',
            'user_name' => '',
            'channel_id' => 'G011BQPHQF6',
        );

        $response = $this->post(route('slash-commands.channel'), $dataSent);
        $response->assertStatus(200);
        $this->assertStringContainsStringIgnoringCase('src-home', $response->getContent());
    }

    public function testGetSlashChannelWithHelp()
    {
        $dataSent = array(
            'command' => '/canais',
            'text' => 'help',
            'response_url' => 'https://hooks.slack.com/services/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',
            'trigger_id' => '',
            'user_id' => 'UUWNYF5CH',
            'user_name' => '',
            'channel_id' => 'G011BQPHQF6',
        );
        $response = $this->post(route('slash-commands.channel'), $dataSent);

        $jsonExpected = json_encode(array(
            'response_type' => 'ephemeral',
            'text' => '✔ Use `/canais` para listar todos os canais\n✔ Use `/canais PALAVRA` para listar todos os canais iniciando com *PALAVRA*'
        ));

        $response->assertOk();
        $this->assertJson($response->content());
        $this->assertJsonStringEqualsJsonString($jsonExpected, $response->content());
    }

    public function testGetSlashChannelWithFilter()
    {
        putenv('SLACK_BOT_TOKEN=xoxb-991511022660-1037911013601-TQLAYIYbODjIZoCElbYBXRXj');
        // Should call SlashCommandsApi@getChannelsListage and get a JSON response with at least one channel
        // Be sure that src-home was listed, since it's the main channel inside workspace
        $dataSent = array(
            'command' => '/canais',
            'text' => 'duv',
            'response_url' => 'https://hooks.slack.com/services/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',
            'trigger_id' => '',
            'user_id' => 'UUWNYF5CH',
            'user_name' => '',
            'channel_id' => 'G011BQPHQF6',
        );

        $response = $this->post(route('slash-commands.channel'), $dataSent);
        $response->assertStatus(200);
        $this->assertStringContainsStringIgnoringCase('|duv', $response->getContent());
    }
}
