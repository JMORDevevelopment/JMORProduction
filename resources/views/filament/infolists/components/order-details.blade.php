<div style="overflow-x:auto;">
<table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
<thead>
<tr style="border-bottom:2px solid #d1d5db;background:#f9fafb;">
<th style="padding:12px 16px;text-align:left;font-weight:700;color:#111827;">Item</th>
<th style="padding:12px 16px;text-align:left;font-weight:700;color:#111827;">Type</th>
<th style="padding:12px 16px;text-align:center;font-weight:700;color:#111827;">Qty</th>
<th style="padding:12px 16px;text-align:right;font-weight:700;color:#111827;">Price</th>
<th style="padding:12px 16px;text-align:right;font-weight:700;color:#111827;">Total</th>
</tr>
</thead>
<tbody>
@foreach ($record->order->orderDetails as $detail)
<tr style="border-bottom:1px solid #e5e7eb;">
<td style="padding:12px 16px;color:#000000;">{{ $detail->item }}</td>
<td style="padding:12px 16px;color:#1f2937;">{{ $detail->type }}</td>
<td style="padding:12px 16px;text-align:center;color:#000000;">{{ $detail->qty }}</td>
<td style="padding:12px 16px;text-align:right;color:#000000;">${{ number_format($detail->price, 2) }}</td>
<td style="padding:12px 16px;text-align:right;font-weight:600;color:#000000;">${{ number_format($detail->sub_total, 2) }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tr style="border-top:2px solid #d1d5db;background:#f9fafb;">
<td colspan="4" style="padding:12px 16px;text-align:right;font-weight:600;color:#111827;">Sub Total</td>
<td style="padding:12px 16px;text-align:right;font-weight:600;color:#000000;">${{ number_format($record->order->sub_total ?? 0, 2) }}</td>
</tr>
<tr style="background:#ffffff;">
<td colspan="4" style="padding:12px 16px;text-align:right;font-weight:600;color:#111827;">Discount</td>
<td style="padding:12px 16px;text-align:right;font-weight:600;color:#b91c1c;">-${{ number_format($record->order->discount ?? 0, 2) }}</td>
</tr>
<tr style="border-top:3px solid #111827;background:#f9fafb;">
<td colspan="4" style="padding:14px 16px;text-align:right;font-weight:800;font-size:1.05rem;color:#000000;">Grand Total</td>
<td style="padding:14px 16px;text-align:right;font-weight:800;font-size:1.05rem;color:#047857;">${{ number_format($record->order->grand_total ?? 0, 2) }}</td>
</tr>
</tfoot>
</table>
</div>
