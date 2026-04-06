<?php

namespace App\Http\Controllers;

use App\Models\IssuanceItem;
use App\Models\ReturnTransaction;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function processReturn(Request $request, $id)
    {
        $validated = $request->validate([
            'returned_quantity' => 'required|integer|min:1',
            'condition'         => 'required|in:good,damaged,lost',
            'notes'             => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Find the issuance item
            $issuanceItem = IssuanceItem::findOrFail($id);

            // Validate quantity
            $remainingQty = $issuanceItem->quantity_issued - ($issuanceItem->quantity_returned ?? 0);
            if ($validated['returned_quantity'] > $remainingQty) {
                return redirect()->back()
                    ->with('error', "Cannot return more than {$remainingQty} items.");
            }

            // Create return transaction record
            ReturnTransaction::create([
                'issuance_item_id' => $issuanceItem->id,
                'issuance_id'      => $issuanceItem->issuance_id,
                'item_id'          => $issuanceItem->item_id,
                'processed_by'     => auth()->id(),
                'quantity_returned'=> $validated['returned_quantity'],
                'condition'        => $validated['condition'],
                'notes'            => $validated['notes'],
                'restocked'        => $validated['condition'] === 'good',
                'returned_at'      => now(),
            ]);

            // Update issuance item
            $newReturnedQty = ($issuanceItem->quantity_returned ?? 0) + $validated['returned_quantity'];
            $newStatus      = $newReturnedQty >= $issuanceItem->quantity_issued ? 'returned' : 'issued';
            if ($validated['condition'] === 'lost') {
                $newStatus = 'lost';
            }

            $issuanceItem->update([
                'quantity_returned'    => $newReturnedQty,
                'status'               => $newStatus,
                'return_date'          => now(),
                'condition_on_return'  => $validated['condition'],
                'notes'                => $validated['notes'],
            ]);

            // If condition is good, restock the item inventory
            if ($validated['condition'] === 'good') {
                $item = Item::find($issuanceItem->item_id);
                if ($item) {
                    $item->increment('quantity', $validated['returned_quantity']);
                }
            }

            // Log audit
            if (class_exists(\App\Models\AuditLog::class)) {
                \App\Models\AuditLog::create([
                    'user_id'      => auth()->id(),
                    'action'       => 'item_returned',
                    'module'       => 'returns',
                    'description'  => "Returned {$validated['returned_quantity']} x {$issuanceItem->item->name} — Condition: {$validated['condition']}",
                    'model_type'   => ReturnTransaction::class,
                    'model_id'     => $issuanceItem->id,
                    'ip_address'   => $request->ip(),
                    'user_agent'   => $request->userAgent(),
                    'url'          => $request->fullUrl(),
                    'method'       => $request->method(),
                    'performed_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Item return processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log failed audit safely — use integer 0 if model_id can't be determined
            if (class_exists(\App\Models\AuditLog::class)) {
                try {
                    \App\Models\AuditLog::create([
                        'user_id'      => auth()->id(),
                        'action'       => 'return_failed',
                        'module'       => 'returns',
                        'description'  => 'Failed to process return: ' . $e->getMessage(),
                        'model_type'   => IssuanceItem::class,
                        'model_id'     => is_numeric($id) ? (int)$id : 0,
                        'old_data'     => json_encode($request->only(['returned_quantity','condition','notes'])),
                        'ip_address'   => $request->ip(),
                        'user_agent'   => $request->userAgent(),
                        'url'          => $request->fullUrl(),
                        'method'       => $request->method(),
                        'performed_at' => now(),
                    ]);
                } catch (\Exception $logException) {
                    // Silently fail audit log
                }
            }

            return redirect()->back()->with('error', 'Failed to process return: ' . $e->getMessage());
        }
    }
}