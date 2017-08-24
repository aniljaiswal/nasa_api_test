<?php

namespace Tests\Feature;

use App\Neo;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\{Psr7, Client as HttpClient};
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NasaApiCommandTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    /** @test */
    public function nasa_command_fetches_and_saves_results_to_db()
    {
        /**
         * Note: For the sake of keeping things simple, I'm not mocking the external
         * Nasa API and HttpClient object but in all production test cases, all external APIs that are
         * not built by us should be mocked for their behavior and not be tested.
         *
         * The only code that needs to be tested is what was written by us.
         */
        
        $base_url = env('NASA_API_BASE_URL');
        $api_key  = env('NASA_API_KEY');
        $client   = new HttpClient;

        $res = $client->request('GET', $base_url . 'feed', [
            'query' => [
                'start_date' => Carbon::today()->subDays(3)->toDateString(),
                'end_date'   => Carbon::today()->toDateString(),
                'detailed'   => false,
                'api_key'    => $api_key,
            ]
        ]);

        $status = $res->getStatusCode();

        if ($status == 200) {
            $content = json_decode($res->getBody());

            if ($content->element_count) {
                $element_count = $content->element_count;
                
                Artisan::call('nasa:get_neos');

                $this->assertEquals($element_count, Neo::all()->count());
            }
        }
    }

    /** @test */
    public function test_hazardous_neos_endpoint()
    {
        $factories = factory(Neo::class, 10)->raw();

        foreach ($factories as $factory) {
            Neo::create($factory);
        }

        $hazardous = Neo::whereIsHazardous(true)->count();

        $response = $this->get('neo/hazardous', [
            'accept' => 'application/json',
        ]);

        $body = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($hazardous, count($body->data));
        $this->assertEquals($hazardous, $body->total);
    }

    /** @test */
    public function test_fastest_neo_endpoint()
    {
        $factories = factory(Neo::class, 10)->raw();

        foreach ($factories as $factory) {
            Neo::create($factory);
        }

        $fastest = Neo::whereIsHazardous(false)->orderByDesc('speed')->first();

        $response = $this->get('neo/fastest?hazardous=0', [
            'accept' => 'application/json',
        ]);

        $body = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($fastest->speed, $body->speed);
    }
}
