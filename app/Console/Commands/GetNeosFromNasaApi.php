<?php

namespace App\Console\Commands;

use App\Neo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\{Psr7, Client as HttpClient};

class GetNeosFromNasaApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nasa:get_neos';

    /**
     * The GuzzleHttp Instance.
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The Nasa API Base URL.
     * 
     * @var string
     */
    protected $base_url;

    /**
     * The Nasa API Key.
     * 
     * @var string
     */
    protected $api_key;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all the Near Earth Objects data for the last 3 days from Nasa API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HttpClient $client)
    {
        parent::__construct();
        
        $this->client   = $client;
        $this->base_url = env('NASA_API_BASE_URL');
        $this->api_key  = env('NASA_API_KEY');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Fetching NEOs...');

        try {
            $res = $this->client->request('GET', $this->base_url . 'feed', [
                'query' => [
                    'start_date' => Carbon::today()->subDays(3)->toDateString(),
                    'end_date'   => Carbon::today()->toDateString(),
                    'detailed'   => false,
                    'api_key'    => $this->api_key,
                ]
            ]);

            $status = $res->getStatusCode();

            if ($status == 200) {
                $content = json_decode($res->getBody());
                if ($content->element_count) {
                    $neos_data = $content->near_earth_objects;
                    foreach ($neos_data as $date => $neos) {
                        foreach ($neos as $neo) {
                            if (!Neo::where('reference_id', $neo->neo_reference_id)->exists()) {
                                Neo::create([
                                    'name'         => $neo->name, 
                                    'speed'        => $neo->close_approach_data[0]->relative_velocity->kilometers_per_hour, 
                                    'date'         => $date,
                                    'is_hazardous' => $neo->is_potentially_hazardous_asteroid, 
                                    'reference_id' => $neo->neo_reference_id, 
                                ]);   
                            }
                        }
                    }
                }
            }

        } catch (RequestException $e) {
            $this->error("The command failed with followinig error: \n" . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }

        $this->info('Done.');
    }
}
