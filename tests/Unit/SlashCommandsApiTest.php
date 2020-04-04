<?php

namespace Tests\Unit;

use App\Http\Api\SlashCommandsApi;
use PHPUnit\Framework\TestCase;

class SlashCommandsApiTest extends TestCase
{
    public function testGetChannels_AllChannels()
    {
        $dataToReceive = '{"ok":true,"channels":[';
        $slashCommands = new SlashCommandsApi();
        $jsonResult = $slashCommands->callApiChannelsListage();

        $this->assertJson($jsonResult);
        $this->assertStringContainsStringIgnoringCase($dataToReceive, $jsonResult);
    }

    protected function setUp(): void
    {
        parent::setUp();
        putenv('SLACK_BOT_TOKEN=xoxb-991511022660-1037911013601-TQLAYIYbODjIZoCElbYBXRXj');
    }
}
