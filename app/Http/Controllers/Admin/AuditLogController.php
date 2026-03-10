<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    /**
     * Display the audit trail list.
     */
    public function index(Request $request)
    {
        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(25);

        return view('settings.audit_trail', compact('logs'));
    }

    /**
     * Export audit logs to CSV.
     */
    public function export()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 compatibility (Absolute First Bytes)
            fputs($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Report Metadata
            fputcsv($handle, ['LAPORAN JEJAK AUDIT (AUDIT TRAIL)'], ';');
            fputcsv($handle, ['Perusahaan:', 'NusaBiz Enterprise Suite'], ';');
            fputcsv($handle, ['Tanggal Laporan:', now()->format('d M Y H:i:s')], ';');
            fputcsv($handle, ['Dicetak Oleh:', auth()->user()->name ?? 'System'], ';');
            fputcsv($handle, [], ';'); // Empty row for spacing

            // CSV Header (Using Semicolon for Excel Windows compatibility)
            fputcsv($handle, [
                'Waktu',
                'User',
                'Peran',
                'Event',
                'Model',
                'Reference ID',
                'Detail Perubahan',
                'IP Address'
            ], ';');

            // Database data in chunks for performance
            AuditLog::with('user')->latest()->chunk(100, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    $details = '';
                    if ($log->event === 'updated') {
                        $lines = [];
                        foreach ($log->new_values as $key => $value) {
                            $old = is_array($log->old_values[$key] ?? '') ? '...' : ($log->old_values[$key] ?? '-');
                            $new = is_array($value) ? '...' : $value;
                            $lines[] = mb_strtoupper(str_replace('_', ' ', $key)) . ": [" . $old . "] -> [" . $new . "]";
                        }
                        $details = implode(" | ", $lines);
                    } elseif ($log->event === 'created') {
                        $details = '--- Registrasi Data Baru ---';
                    } else {
                        $details = '--- Penghapusan Data ---';
                    }

                    fputcsv($handle, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user->name ?? 'System',
                        $log->user->role ?? 'N/A',
                        strtoupper($log->event),
                        class_basename($log->auditable_type),
                        $log->auditable_id,
                        trim($details, ' | '),
                        $log->ip_address
                    ], ';');
                }
            });

            fclose($handle);
        });

        $filename = 'audit_trail_export_' . now()->format('Ymd_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
