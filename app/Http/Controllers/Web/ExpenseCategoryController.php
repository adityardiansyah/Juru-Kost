<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::withCount('expenses')->get();
        return view('finance.expense-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string',
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('finance.expense-categories.index')
            ->with('success', 'Kategori pengeluaran berhasil ditambahkan!');
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string',
        ]);

        $expenseCategory->update($validated);

        return redirect()->route('finance.expense-categories.index')
            ->with('success', 'Kategori pengeluaran berhasil diupdate!');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->count() > 0) {
            return redirect()->route('finance.expense-categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan!');
        }

        $expenseCategory->delete();

        return redirect()->route('finance.expense-categories.index')
            ->with('success', 'Kategori pengeluaran berhasil dihapus!');
    }
}
