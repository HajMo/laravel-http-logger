<?php

namespace Spatie\HttpLogger\Test;

use Spatie\HttpLogger\LogNonGetRequests;

class LogNonGetRequestsTest extends TestCase
{
    /** @var \Spatie\HttpLogger\LogNonGetRequests */
    protected $logProfile;

    public function setUp() : void
    {
        parent::setup();

        $this->logProfile = new LogNonGetRequests();

        config()->set('http-logger.environments', ['testing']);
    }

    /** @test */
    public function it_logs_post_patch_put_delete()
    {
        foreach (['post', 'put', 'patch', 'delete'] as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertTrue($this->logProfile->shouldLogRequest($request), "{$method} should be logged.");
        }
    }

    /** @test */
    public function it_doesnt_log_get_head_options_trace()
    {
        foreach (['get', 'head', 'options', 'trace'] as $method) {
            $request = $this->makeRequest($method, $this->uri);

            $this->assertFalse($this->logProfile->shouldLogRequest($request), "{$method} should not be logged.");
        }
    }

    /** @test */
    public function it_will_log_when_current_environment_match()
    {
        $request = $this->makeRequest('post', $this->uri);

        $this->assertTrue($this->logProfile->shouldLogRequest($request), 'post should be logged.');
    }

    /** @test */
    public function it_will_log_when_current_environment_dosent_match()
    {
        config()->set('http-logger.environments', ['production']);

        $request = $this->makeRequest('post', $this->uri);

        $this->assertFalse($this->logProfile->shouldLogRequest($request), 'post should not be logged.');
    }
}
