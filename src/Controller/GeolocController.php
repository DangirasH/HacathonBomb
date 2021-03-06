<?php

namespace App\Controller;

use App\Model\AddressManager;
use App\Model\AirManager;

class GeolocController extends AbstractController
{
    public array $airQuality = [
        1 => 'Bien',
        2 => 'Viable',
        3 => 'Moyen',
        4 => 'Mauvais',
        5 => 'Toxique',
    ];

    public function index(): ?string
    {
        $addressManager = new AddressManager();
        $lat = 0;
        $lon = 0;
        $detailsAirQuality = 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address = array_map('trim', $_POST);

            $data = $addressManager->search($address['housenumber'], $address['street'], $address['postcode']);
            $details = $data['features'][0]['geometry']['coordinates'];
            $lon = $details[0];
            $lat = $details[1];

            $airManager = new AirManager();
            $air = $airManager->show($lat, $lon);
            $detailsAirQuality = $air['list'][0]['main']['aqi'];

            header('Location: bomb?lon=' . $lon . '&lat=' . $lat . '&airQuality=' . $detailsAirQuality);
            return null;
        }

        return $this->twig->render('Geoloc/index.html.twig', [
            'airQuality' => $this->airQuality,
            'detailsAirQuality' => $detailsAirQuality,
            'lon' => $lon,
            'lat' => $lat,
        ]);
    }
}
