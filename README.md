# Pathao API Integration

A Laravel package for integrating with the Pathao Courier API.

This package provides a small service layer, facade, config file, and Laravel auto-discovery support for common Pathao Courier operations such as creating orders, checking delivery prices, listing cities/zones/areas, tracking orders, and cancelling orders.

## Requirements

- PHP 7.4 or higher
- Laravel 8, 9, 10, or 11
- Guzzle 7

## Installation

Install the package with Composer:

```bash
composer require kejubayer/pathao-api-integration
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=pathao-config
```

Add your Pathao credentials to `.env`:

```env
PATHAO_BASE_URL=https://api-hermes.pathao.com
PATHAO_CLIENT_ID=your-client-id
PATHAO_CLIENT_SECRET=your-client-secret
PATHAO_USERNAME=your-username
PATHAO_PASSWORD=your-password
PATHAO_WEBHOOK_ROUTE=pathao/webhook/parcel-status
```

Laravel package auto-discovery will register the service provider and facade automatically.

Run the migrations to create the parcel status webhook table:

```bash
php artisan migrate
```

## Configuration

The package configuration is stored in `config/pathao.php`.

| Key | Environment Variable | Description |
| --- | --- | --- |
| `base_url` | `PATHAO_BASE_URL` | Pathao API base URL. Defaults to `https://api-hermes.pathao.com`. |
| `client_id` | `PATHAO_CLIENT_ID` | Pathao API client ID. |
| `client_secret` | `PATHAO_CLIENT_SECRET` | Pathao API client secret. |
| `username` | `PATHAO_USERNAME` | Pathao merchant username. |
| `password` | `PATHAO_PASSWORD` | Pathao merchant password. |
| `webhook_route` | `PATHAO_WEBHOOK_ROUTE` | Webhook route path for parcel status callbacks. Defaults to `pathao/webhook/parcel-status`. |

## Usage

Import the facade:

```php
use Pathao;
```

### Get Access Token

```php
$token = Pathao::getAccessToken();
```

### Create Order

```php
$stores = Pathao::stores();
$storeId = $stores['data']['data'][0]['store_id'];

$order = Pathao::createOrder([
    'store_id' => $storeId,
    'merchant_order_id' => 'ORD-1001',
    'recipient_name' => 'Customer Name',
    'recipient_phone' => '017XXXXXXXX',
    'recipient_address' => 'House 1, Road 2, Dhaka',
    'delivery_type' => 48,
    'item_type' => 2,
    'special_instruction' => 'Handle with care',
    'item_quantity' => 1,
    'item_weight' => 0.5,
    'amount_to_collect' => 1200,
    'item_description' => 'Product description',
]);
```

Pathao requires `store_id` when creating an order. Use `Pathao::stores()` to get your available stores, then pass the selected `store_id` to `Pathao::createOrder()`.

### Price Calculation

```php
$price = Pathao::priceCalculation([
    'store_id' => 12345,
    'item_type' => 2,
    'delivery_type' => 48,
    'item_weight' => 0.5,
    'recipient_city' => 1,
    'recipient_zone' => 2,
]);
```

### Store List

```php
$stores = Pathao::stores();
```

### City List

```php
$cities = Pathao::cities();
```

### Zone List

```php
$zones = Pathao::zones($cityId);
```

### Area List

```php
$areas = Pathao::areas($zoneId);
```

### Track Order

```php
$tracking = Pathao::trackOrder($consignmentId);
```

### Cancel Order

```php
$cancelled = Pathao::cancelOrder($consignmentId);
```

## Webhook

The package registers a POST webhook route for parcel status updates:

```text
POST /pathao/webhook/parcel-status
```

Use this URL in your Pathao webhook/callback settings:

```text
https://your-domain.com/pathao/webhook/parcel-status
```

When Pathao sends a parcel status callback, the package stores it in the `pathao_parcel_statuses` table.

Stored columns:

| Column | Description |
| --- | --- |
| `consignment_id` | Consignment ID from the webhook payload, when available. |
| `merchant_order_id` | Merchant order ID from the webhook payload, when available. |
| `store_id` | Pathao store ID from the webhook payload. |
| `event` | Pathao webhook event name, such as `order.created`. |
| `delivery_fee` | Delivery fee from the webhook payload. |
| `pathao_updated_at` | Pathao `updated_at` value from the webhook payload. |
| `pathao_timestamp` | Pathao `timestamp` value from the webhook payload. |
| `payload` | Full webhook request payload as JSON. |
| `received_at` | Time the webhook was received. |

Example webhook payload:

```json
{
    "consignment_id": "12ABC345",
    "merchant_order_id": "ORD-1001",
    "updated_at": "2024-12-27 23:49:43",
    "timestamp": "2024-12-27T17:49:43+00:00",
    "store_id": 130820,
    "event": "order.created",
    "delivery_fee": 83.46
}
```

Read saved parcel statuses:

```php
use Kejubayer\PathaoIntegration\Models\PathaoParcelStatus;

$statuses = PathaoParcelStatus::latest()->get();
```

To customize the webhook URL, change `PATHAO_WEBHOOK_ROUTE`:

```env
PATHAO_WEBHOOK_ROUTE=api/pathao/parcel-status
```

## Data Reference

### Create Order Data

| Field | Type | Required | Description |
| --- | --- | --- | --- |
| `store_id` | integer | Yes | Pathao store ID from `Pathao::stores()`. |
| `merchant_order_id` | string | No | Your internal order ID. |
| `recipient_name` | string | Yes | Customer name. |
| `recipient_phone` | string | Yes | Customer phone number. |
| `recipient_secondary_phone` | string | No | Additional customer phone number. |
| `recipient_address` | string | Yes | Full delivery address. |
| `recipient_city` | integer | No | City ID from `Pathao::cities()`. |
| `recipient_zone` | integer | No | Zone ID from `Pathao::zones($cityId)`. |
| `recipient_area` | integer | No | Area ID from `Pathao::areas($zoneId)`. |
| `delivery_type` | integer | Yes | Delivery type ID supported by Pathao. |
| `item_type` | integer | Yes | Item type ID supported by Pathao. |
| `special_instruction` | string | No | Delivery instruction. |
| `item_quantity` | integer | Yes | Number of items. |
| `item_weight` | number | Yes | Parcel weight. |
| `amount_to_collect` | number | Yes | Cash collection amount. Use `0` for non-COD orders. |
| `item_description` | string | No | Parcel or product description. |

### Price Calculation Data

| Field | Type | Required | Description |
| --- | --- | --- | --- |
| `store_id` | integer | Yes | Pathao store ID. |
| `item_type` | integer | Yes | Item type ID supported by Pathao. |
| `delivery_type` | integer | Yes | Delivery type ID supported by Pathao. |
| `item_weight` | number | Yes | Parcel weight. |
| `recipient_city` | integer | Yes | Destination city ID. |
| `recipient_zone` | integer | Yes | Destination zone ID. |

### Method Reference

| Method | Description |
| --- | --- |
| `getAccessToken()` | Issues an access token using configured Pathao credentials. |
| `createOrder(array $data)` | Creates a courier order. |
| `priceCalculation(array $data)` | Calculates delivery price. |
| `stores()` | Returns available merchant store list. |
| `cities()` | Returns available city list. |
| `zones($cityId)` | Returns zones for a city. |
| `areas($zoneId)` | Returns areas for a zone. |
| `trackOrder($consignmentId)` | Returns tracking information for a consignment. |
| `cancelOrder($consignmentId)` | Cancels an order by consignment ID. |

## API Endpoints Used

| Method | HTTP | Endpoint |
| --- | --- | --- |
| `getAccessToken()` | POST | `/aladdin/api/v1/issue-token` |
| `createOrder()` | POST | `/aladdin/api/v1/orders` |
| `priceCalculation()` | POST | `/aladdin/api/v1/merchant/price-plan` |
| `stores()` | GET | `/aladdin/api/v1/stores` |
| `cities()` | GET | `/aladdin/api/v1/city-list` |
| `zones()` | GET | `/aladdin/api/v1/cities/{cityId}/zone-list` |
| `areas()` | GET | `/aladdin/api/v1/zones/{zoneId}/area-list` |
| `trackOrder()` | GET | `/aladdin/api/v1/orders/{consignmentId}/info` |
| `cancelOrder()` | POST | `/aladdin/api/v1/orders/{consignmentId}/cancel` |

## Dependency Injection

You can also inject the contract instead of using the facade:

```php
use Kejubayer\PathaoIntegration\Contracts\PathaoInterface;

class OrderController
{
    public function store(PathaoInterface $pathao)
    {
        return $pathao->cities();
    }
}
```

## License

This package is open-sourced software licensed under the MIT license.
