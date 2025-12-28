<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingCoupon;
use Illuminate\Http\Request;

class MarketingCouponController extends Controller
{
    public function index()
    {
        $coupons = MarketingCoupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:marketing_coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        MarketingCoupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon créé avec succès !');
    }

    public function show(MarketingCoupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(MarketingCoupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, MarketingCoupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:marketing_coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon mis à jour avec succès !');
    }

    public function destroy(MarketingCoupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon supprimé avec succès !');
    }
}
