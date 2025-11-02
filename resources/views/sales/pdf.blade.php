<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - {{ date('d/m/Y') }}</title>
    
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 20px; color: #333; font-size: 12px;}
        .container { padding: 0px 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #10B981; font-size: 24px; } /* Color Verde */
        .header p { color: #666; margin: 2px 0; font-size: 12px;}
        .filters { font-size: 10px; color: #555; margin-bottom: 15px; background-color:#f9f9f9; padding: 8px; border-radius: 4px; border: 1px solid #eee; }
        .stats { display: table; width: 100%; margin-bottom: 20px; border: 1px solid #eee; background: #fdfdfd; }
        .stat-item { display: table-cell; text-align: center; padding: 10px; border-left: 1px solid #eee;}
        .stat-item:first-child { border-left: none; }
        .stat-value { font-size: 16px; font-weight: bold; color: #10B981; }
        .stat-label { font-size: 10px; color: #666; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #10B981; color: white; font-weight: bold; } /* Color Verde */
        tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .total-row td { background-color: #D1FAE5 !important; font-weight: bold; } /* Verde claro */
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #888; border-top: 1px solid #eee; padding-top: 10px; }
        .product-list { font-size: 11px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Reporte de Ventas</h1>
            <p>Fruver App - Sistema de Gestión</p>
            <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

         {{-- Mostrar filtros  --}}
        <div class="filters">
            <strong>Filtros Aplicados:</strong> 
            @if(request('product_search')) Producto: {{ request('product_search') }} | @endif
            @if(request('client_search')) Cliente: {{ request('client_search') }} | @endif
            @if(request('date_from')) Desde: {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }} | @endif
            @if(request('date_to')) Hasta: {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }} | @endif
            @if(!request()->hasAny(['product_search', 'client_search', 'date_from', 'date_to'])) Ninguno @endif
        </div>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-value">{{ $totalVentas }}</div>
                <div class="stat-label">Total Ventas</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $productosVendidos }}</div>
                <div class="stat-label">Productos Vendidos</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${{ number_format($totalIngresos, 0) }}</div>
                <div class="stat-label">Ingresos Totales</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${{ $totalVentas > 0 ? number_format($totalIngresos / $totalVentas, 0) : '0.00' }}</div>
                <div class="stat-label">Promedio / Venta</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th class="text-right">Total</th>
                    <th>Registró</th>
                    <th>Método Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                    <td>{{ $sale->cliente ?: 'N/A' }}</td>
                    <td class="product-list">
                         {{-- Lista de productos y cantidades --}}
                         @foreach($sale->details as $detail)
                             {{ $detail->cantidad }}x {{ $detail->product->nombre }}<br>
                         @endforeach
                    </td>
                    <td class="text-right">${{ number_format($sale->total, 0) }}</td>
                    <td>{{ $sale->user->name }}</td>
                     <td>{{ ucfirst($sale->metodo_pago) }}</td>
                </tr>
                @empty
                 <tr><td colspan="7" style="text-align: center;">No hay ventas que coincidan con los filtros.</td></tr>
                @endforelse
                {{-- Fila de Totales Generales --}}
                 @if($sales->count() > 0)
                 <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>TOTAL GENERAL (Filtrado):</strong></td>
                    <td class="text-right"><strong>${{ number_format($totalIngresos, 0) }}</strong></td>
                    <td colspan="2"></td>
                 </tr>
                 @endif
            </tbody>
        </table>

        <div class="footer">
            Reporte generado automáticamente por Fruver App
        </div>
    </div>
</body>
</html>