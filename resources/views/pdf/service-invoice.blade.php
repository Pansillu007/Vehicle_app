<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $service->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 20px; }
        h1 { color: #2563eb; margin: 0; }
        .amount { font-size: 22px; font-weight: bold; color: #2563eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>VehiclePro Invoice</h1>
        <p>Invoice #{{ $service->id }} · {{ $service->service_date->format('F d, Y') }}</p>
    </div>
    <p><strong>Vehicle:</strong> {{ $vehicle->name }} ({{ $vehicle->number_plate }})</p>
    <p><strong>Service:</strong> {{ $service->service_type }}</p>
    <p><strong>Provider:</strong> {{ $service->service_provider }}</p>
    <p><strong>Mileage:</strong> {{ number_format($service->mileage) }}</p>
    <p>{{ $service->description }}</p>
    <p class="amount">Total: ${{ number_format($service->cost, 2) }}</p>
</body>
</html>
