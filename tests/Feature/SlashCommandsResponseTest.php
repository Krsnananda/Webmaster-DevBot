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

        $jsonExpected = "✔ Use `/canais` para listar todos os canais\n✔ Use `/canais PALAVRA` para listar todos os canais iniciando com *PALAVRA*";

        $response->assertOk();
        $this->assertStringContainsStringIgnoringCase($jsonExpected, $response->content());
    }

    public function testGetSlashChannelWithFilter()
    {
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
