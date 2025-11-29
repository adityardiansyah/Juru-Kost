<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        $categories = IncomeCategory::withCount('incomes')->get();
        return view('finance.income-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:income_categories,name',
            'description' => 'nullable|string',
        ]);

        IncomeCategory::create($validated);

        return redirect()->route('finance.income-categories.index')
            ->with('success', 'Kategori pemasukan berhasil ditambahkan!');
    }

    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:income_categories,name,' . $incomeCategory->id,
            'description' => 'nullable|string',
        ]);

        $incomeCategory->update($validated);

        return redirect()->route('finance.income-categories.index')
            ->with('success', 'Kategori pemasukan berhasil diupdate!');
    }

    public function destroy(IncomeCategory $incomeCategory)
    {
        if ($incomeCategory->incomes()->count() > 0) {
            return redirect()->route('finance.income-categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan!');
        }

        $incomeCategory->delete();

        return redirect()->route('finance.income-categories.index')
            ->with('success', 'Kategori pemasukan berhasil dihapus!');
    }
}
