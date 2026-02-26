<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan');
    }

    public function data(Request $request)
    {
        $from   = $request->get('from', now()->subDays(7)->toDateString());
        $to     = $request->get('to', now()->toDateString());
        $status = $request->get('status', '');

        $query = Order::with(['table', 'orderItems.product'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        if ($status) $query->where('status', $status);

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Chart data 7 hari
        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = Carbon::today()->subDays($i);
            $total = Order::where('status', 'done')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $chart[] = [
                'label' => $date->format('d/m'),
                'total' => $total,
            ];
        }

        return response()->json(['orders' => $orders, 'chart' => $chart]);
    }

    public function exportExcel(Request $request)
    {
        $from   = $request->get('from', now()->subDays(7)->toDateString());
        $to     = $request->get('to', now()->toDateString());
        $status = $request->get('status', '');

        $query = Order::with(['table', 'orderItems.product'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        if ($status) $query->where('status', $status);
        $orders = $query->orderBy('created_at', 'desc')->get();

        $filename = 'laporan-kopi-brader-' . $from . '-' . $to . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['No Order', 'Meja', 'Items', 'Total', 'Metode Bayar', 'Status', 'Catatan', 'Waktu']);

            foreach ($orders as $order) {
                $items = $order->orderItems->map(function($i) {
                    return ($i->product->name ?? '?') . ' x' . $i->quantity;
                })->join(', ');

                $statusLabel = [
                    'pending' => 'Menunggu',
                    'process' => 'Diproses',
                    'done'    => 'Selesai',
                    'cancel'  => 'Dibatal',
                ];

                fputcsv($file, [
                    '#' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    'Meja ' . ($order->table->table_number ?? '-'),
                    $items,
                    $order->total_price,
                    strtoupper($order->payment_method),
                    $statusLabel[$order->status] ?? $order->status,
                    $order->notes ?? '',
                    $order->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF(Request $request)
    {
        $from   = $request->get('from', now()->subDays(7)->toDateString());
        $to     = $request->get('to', now()->toDateString());
        $status = $request->get('status', '');

        $query = Order::with(['table', 'orderItems.product'])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        if ($status) $query->where('status', $status);
        $orders = $query->orderBy('created_at', 'desc')->get();

        $done    = $orders->where('status', 'done');
        $total   = $done->sum('total_price');
        $avg     = $done->count() ? round($total / $done->count()) : 0;

        $statusLabel = [
            'pending' => 'Menunggu',
            'process' => 'Diproses',
            'done'    => 'Selesai',
            'cancel'  => 'Dibatal',
        ];
        $payLabel = [
            'qris'  => 'QRIS',
            'dana'  => 'DANA',
            'gopay' => 'GoPay',
            'cash'  => 'Cash',
        ];

        $html = '<!DOCTYPE html><html><head>
        <meta charset="UTF-8">
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }
            .header { text-align: center; margin-bottom: 24px; border-bottom: 3px solid #d4ff00; padding-bottom: 16px; }
            .header h1 { font-size: 22px; color: #000; }
            .header p { color: #555; margin-top: 4px; }
            .stats { display: flex; gap: 12px; margin-bottom: 20px; }
            .stat { flex: 1; background: #f5f5f5; border-radius: 8px; padding: 12px; text-align: center; }
            .stat-val { font-size: 16px; font-weight: 800; color: #000; }
            .stat-label { font-size: 10px; color: #666; margin-top: 3px; }
            table { width: 100%; border-collapse: collapse; margin-top: 8px; }
            th { background: #1a1a2e; color: #d4ff00; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
            td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
            tr:nth-child(even) td { background: #fafafa; }
            .status-done    { color: #16a34a; font-weight: 700; }
            .status-cancel  { color: #dc2626; }
            .status-pending { color: #ca8a04; }
            .status-process { color: #7c3aed; }
            .footer { margin-top: 20px; text-align: center; color: #888; font-size: 10px; border-top: 1px solid #eee; padding-top: 12px; }
        </style>
        </head><body>';

        $html .= '<div class="header">';
        $html .= '<h1>☕ Kopi Brader</h1>';
        $html .= '<p>Laporan Penjualan: ' . $from . ' s/d ' . $to . '</p>';
        $html .= '</div>';

        $html .= '<div class="stats">';
        $html .= '<div class="stat"><div class="stat-val">Rp ' . number_format($total, 0, ',', '.') . '</div><div class="stat-label">Total Pendapatan</div></div>';
        $html .= '<div class="stat"><div class="stat-val">' . $orders->count() . '</div><div class="stat-label">Total Order</div></div>';
        $html .= '<div class="stat"><div class="stat-val">' . $done->count() . '</div><div class="stat-label">Order Selesai</div></div>';
        $html .= '<div class="stat"><div class="stat-val">Rp ' . number_format($avg, 0, ',', '.') . '</div><div class="stat-label">Rata-rata/Order</div></div>';
        $html .= '</div>';

        $html .= '<table><thead><tr><th>#Order</th><th>Meja</th><th>Items</th><th>Total</th><th>Bayar</th><th>Status</th><th>Waktu</th></tr></thead><tbody>';

        foreach ($orders as $order) {
            $items = $order->orderItems->map(fn($i) => ($i->product->name ?? '?') . ' x' . $i->quantity)->join(', ');
            $st    = $order->status;
            $html .= '<tr>';
            $html .= '<td>#' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '</td>';
            $html .= '<td>Meja ' . ($order->table->table_number ?? '-') . '</td>';
            $html .= '<td>' . $items . '</td>';
            $html .= '<td>Rp ' . number_format($order->total_price, 0, ',', '.') . '</td>';
            $html .= '<td>' . ($payLabel[$order->payment_method] ?? $order->payment_method) . '</td>';
            $html .= '<td class="status-' . $st . '">' . ($statusLabel[$st] ?? $st) . '</td>';
            $html .= '<td>' . $order->created_at->format('d/m/Y H:i') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '<div class="footer">Dicetak pada ' . now()->format('d/m/Y H:i') . ' · Kopi Brader QR Order System</div>';
        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="laporan-kopi-brader-' . $from . '-' . $to . '.html"');
    }
}