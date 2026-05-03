<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ExchangeRate;

class FetchApiData extends Command
{
    protected $signature = 'api:fetch';
    protected $description = 'Fetch exchange rates and save to DB';

    public function handle()
    {
        Log::info('Exchange rate fetch started');

        try {
            $response = Http::timeout(20)
                ->retry(3, 2000)
                ->get('https://open.er-api.com/v6/latest/USD');

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['rates'])) {
                    throw new \Exception('Invalid API response');
                }

                $count = 0;
                $now = now();

                foreach ($data['rates'] as $currency => $rate) {
                    ExchangeRate::updateOrCreate(
                        [
                            'base_currency' => 'USD',
                            'target_currency' => $currency,
                        ],
                        [
                            'rate' => $rate,
                            'fetched_at' => $now,
                        ]
                    );

                    $count++;
                }

                Log::info('Exchange rates updated', [
                    'count' => $count
                ]);

                $this->info("Saved {$count} rates");

            } else {
                Log::error('API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                $this->error('Failed to fetch exchange rates');
            }

        } catch (\Exception $e) {
            Log::critical('Exchange rate fetch exception', [
                'message' => $e->getMessage()
            ]);

            $this->error('Exception occurred');
        }

        Log::info('Exchange rate fetch finished');
    }
}
