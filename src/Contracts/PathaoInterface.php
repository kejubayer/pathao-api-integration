<?php

namespace Kejubayer\PathaoIntegration\Contracts;

interface PathaoInterface
{
    public function getAccessToken();

    public function createOrder(array $data);

    public function priceCalculation(array $data);

    public function stores();

    public function cities();

    public function zones($cityId);

    public function areas($zoneId);

    public function trackOrder($consignmentId);

    public function cancelOrder($consignmentId);
}
