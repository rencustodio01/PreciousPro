<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationService
{
    /**
     * Geocode an address to latitude and longitude using OpenStreetMap Nominatim.
     * Automatically tries progressively less specific queries if the exact address fails.
     *
     * @param string $address The address or location name to geocode (can be exact or approximate)
     * @return array|null ['lat' => float, 'lng' => float] or null if geocoding fails
     */
    public function geocode(string $address): ?array
    {
        $address = trim($address);

        if (empty($address)) {
            return null;
        }

        $attempts = $this->buildGeocodingQueries($address);

        foreach ($attempts as $query) {
            $coordinates = $this->queryNominatim($query);
            if ($coordinates) {
                Log::info('Successfully geocoded address', [
                    'original_address' => $address,
                    'query_used' => $query,
                    'coordinates' => $coordinates
                ]);
                return $coordinates;
            }
        }

        Log::warning('Nominatim geocoding returned no results for any query.', [
            'address' => $address,
            'attempts' => $attempts
        ]);

        return null;
    }

    private function buildGeocodingQueries(string $address): array
    {
        $queries = [$address];

        if (str_contains(strtolower($address), 'philippines')) {
            $queries[] = trim(str_ireplace('Philippines', '', $address));
        }

        $parts = array_filter(array_map('trim', explode(',', $address)));
        while (count($parts) > 1) {
            array_pop($parts);
            $queries[] = implode(', ', $parts);
        }

        return array_values(array_unique(array_filter($queries)));
    }

    private function queryNominatim(string $query): ?array
    {
        try {
            $response = Http::acceptJson()
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'PreciousPro/1.0 (https://preciouspro.local)',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data[0]) && isset($data[0]['lat'], $data[0]['lon'])) {
                    return [
                        'lat' => (float) $data[0]['lat'],
                        'lng' => (float) $data[0]['lon'],
                    ];
                }

                Log::warning('Nominatim returned no results.', ['query' => $query, 'response' => $data]);
            } else {
                Log::error('Nominatim request failed.', ['query' => $query, 'status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Nominatim geocoding exception: ' . $e->getMessage(), ['query' => $query]);
        }

        return null;
    }

    /**
     * Reverse geocode latitude and longitude to address using OpenStreetMap Nominatim.
     *
     * @param float $lat
     * @param float $lng
     * @return string|null
     */
    public function reverseGeocode(float $lat, float $lng): ?string
    {
        try {
            $response = Http::acceptJson()
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'PreciousPro/1.0 (https://preciouspro.local)',
                ])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $lat,
                    'lon' => $lng,
                    'format' => 'json',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['display_name'])) {
                    return $data['display_name'];
                }
            } else {
                Log::error('Nominatim reverse request failed.', ['lat' => $lat, 'lng' => $lng, 'status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Nominatim reverse geocoding exception: ' . $e->getMessage(), ['lat' => $lat, 'lng' => $lng]);
        }

        return null;
    }
}
