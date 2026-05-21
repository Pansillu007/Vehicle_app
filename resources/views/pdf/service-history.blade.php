<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Service History - {{ $vehicle->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #2563eb; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; }
        th { background: #eff6ff; }
    </style>
</head>
<body>
    <h1>Service History</h1>
    <p>{{ $vehicle->name }} ({{ $vehicle->number_plate }})</p>
    <table>
        <thead>
            <tr><th>Date</th><th>Type</th><th>Provider</th><th>Mileage</th><th>Cost</th><th>Notes</th></tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->service_date->format('Y-m-d') }}</td>
                <td>{{ $service->service_type }}</td>
                <td>{{ $service->service_provider }}</td>
                <td>{{ number_format($service->mileage) }}</td>
                <td>${{ number_format($service->cost, 2) }}</td>
                <td>{{ Str::limit($service->description, 80) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
