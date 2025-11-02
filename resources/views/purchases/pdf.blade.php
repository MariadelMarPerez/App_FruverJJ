<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Compra #{{ $purchase->id }}</title>
    {{-- Estilos para el PDF --}}
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 20px; color: #333; font-size: 11px; } /* Tamaño de fuente base ajustado */
        .container { padding: 0px 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #f97316; font-size: 22px; } /* Naranja */
        .header p { color: #666; margin: 2px 0; font-size: 11px;}
        .details { margin-bottom: 20px; font-size: 10px; } /* Tamaño fuente detalles */
        .details table { width: 100%; border-collapse: collapse; }
        .details th, .details td { padding: 4px 6px; text-align: left; } /* Menos padding */
        .details th { color: #555; font-weight: bold; width: 100px;} /* Ancho fijo para etiquetas */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 10px; } /* Menos padding */
        .items-table th { background-color: #eee; font-weight: bold; }
        .text-right { text-align: right; }
        .total-section { margin-top: 15px; text-align: right; }
        .total-section h3 { margin: 2px 0; font-size: 13px; font-weight: bold;} /* Tamaño ajustado */
        .status { font-weight: bold; padding: 2px 6px; border-radius: 10px; display: inline-block; font-size: 9px; } /* Más pequeño */
        .status-completada { background-color: #d1fae5; color: #065f46; }
        .status-anulada { background-color: #fee2e2; color: #991b1b; }
        .footer { text-align: center; margin-top: 25px; font-size: 9px; color: #888; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Detalle de Compra #{{ $purchase->id }}</h1>
            <p>Fruver App - Sistema de Gestión</p>
        </div>

        <div class="details">
            <table>
                <tr>
                    <th>Proveedor:</th>
                    <td>{{ $purchase->provider->name }}</td>
                    <th>Fecha Registro:</th>
                    <td>{{ $purchase->created_at->format('d/m/Y H:i A') }}</td>
                </tr>
                <tr>
                    <th>Registrado por:</th>
                    <td>{{ $purchase->user->name }}</td>
                    <th>Estado:</th>
                    <td>
                        <span class="status status-{{ $purchase->status }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <h3>Productos Incluidos</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-right">Cantidad (Kg)</th> {{-- Añadido Kg --}}
                    <th class="text-right">Costo Unitario (/Kg)</th> {{-- Añadido /Kg --}}
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->details as $detail)
                    <tr>
                        <td>{{ $detail->product->nombre ?? 'Producto no encontrado' }}</td>
                        {{-- Mostramos cantidad con 2 decimales y 'Kg' --}}
                        <td class="text-right">{{ number_format(floatval($detail->quantity), 2) }} Kg</td>
                        <td class="text-right">${{ number_format($detail->cost, 2) }}</td>
                        <td class="text-right">${{ number_format(floatval($detail->quantity) * floatval($detail->cost), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <h3>Costo Total: ${{ number_format($purchase->total_cost, 2) }}</h3>
        </div>

        <div class="footer">
            Generado el {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
