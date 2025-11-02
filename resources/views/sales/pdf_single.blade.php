<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta #{{ $sale->id }}</title>
    
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 20px; color: #333; font-size: 12px;}
        .container { padding: 0px 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #10B981; font-size: 24px; } /* Color Verde */
        .details { margin-bottom: 20px; font-size: 11px; }
        .details table { width: 100%; border-collapse: collapse; }
        .details th, .details td { padding: 5px; text-align: left; }
        .details th { color: #555; font-weight: bold; width: 120px;}
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        .items-table th { background-color: #eee; font-weight: bold; }
        .text-right { text-align: right; }
        .total-section { margin-top: 15px; text-align: right; }
        .total-section h3 { margin: 3px 0; font-size: 14px; font-weight: bold;}
        .status { font-weight: bold; padding: 3px 8px; border-radius: 10px; display: inline-block; font-size: 10px; }
        .status-pagada { background-color: #d1fae5; color: #065f46; }
        .status-cancelada { background-color: #fee2e2; color: #991b1b; }
        .footer { text-align: center; margin-top: 25px; font-size: 10px; color: #888; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Recibo de Venta #{{ $sale->id }}</h1>
        </div>

        <div class="details">
            <table>
                <tr>
                    <th>Fecha y Hora:</th>
                    <td>{{ $sale->created_at->format('d/m/Y H:i A') }}</td>
                    <th>Cliente:</th>
                    <td>{{ $sale->cliente ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Atendido por:</th>
                    <td>{{ $sale->user->name }}</td>
                    <th>Estado:</th>
                    <td>
                         <span class="status status-{{ $sale->estado }}">
                            {{ ucfirst($sale->estado) }}
                        </span>
                    </td>
                </tr>
                 <tr>
                    <th>Método Pago:</th>
                    <td>{{ ucfirst($sale->metodo_pago) }}</td>
                    @if($sale->metodo_pago == 'efectivo')
                        <th>Recibido:</th><td>${{ number_format($sale->monto_recibido, 0) }}</td>
                    @elseif($sale->metodo_pago == 'transferencia')
                        <th>Referencia:</th><td>{{ $sale->referencia_transferencia }}</td>
                    @endif
                </tr>
                 @if($sale->metodo_pago == 'efectivo' && $sale->cambio > 0)
                 <tr><th>Cambio:</th><td colspan="3" style="font-weight:bold; color: #2563eb;">${{ number_format($sale->cambio, 0) }}</td></tr>
                 @endif
            </table>
        </div>

        <h3>Productos</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->details as $detail)
                    <tr>
                        <td>{{ $detail->product->nombre ?? 'N/A' }}</td>
                        <td class="text-right">{{ $detail->cantidad }}</td>
                        <td class="text-right">${{ number_format($detail->precio_unitario, 0) }}</td>
                        <td class="text-right">${{ number_format($detail->subtotal, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <h3>Total Venta: ${{ number_format($sale->total, 0) }}</h3>
        </div>

        <div class="footer">
            Gracias por su compra - Fruver App
        </div>
    </div>
</body>
</html>