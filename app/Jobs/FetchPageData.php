<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\bookmarksModel;
use GuzzleHttp\Client;

class FetchPageData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $bookmark;
    public $tries = 3;
    public $backoff = [10, 30, 60];

    public function __construct(bookmarksModel $bookmark)
    {
        $this->bookmark = $bookmark;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new Client();
        try {
            $response = $client->get($this->bookmark->url);
            $html = (string) $response->getBody();

            preg_match('/<title>(.*?)<\/title>/', $html, $title);
            preg_match('/<meta name="description" content="(.*?)"/', $html, $description);

            $this->bookmark->update(attributes: [
                'title' => $title[1] ?? null,
                'description' => $description[1] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to fetch metadata: " . $e->getMessage());
            throw $e;
        }

    }
}
