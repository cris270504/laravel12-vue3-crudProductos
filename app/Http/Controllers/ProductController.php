<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('products/Index', [
            'products' => Product::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('products/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre'      => ['required','string','max:50'],
            'descripcion' => ['nullable','string'],
            'precio'      => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'activo'      => ['required','boolean'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show($id): Response
    {
        $product = Product::findOrFail($id);

        return Inertia::render('products/Show', [
            'product' => $product,
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return Inertia::render('products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'nombre'      => ['required','string','max:50'],
            'descripcion' => ['nullable','string'],
            'precio'      => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'activo'      => ['required','boolean'],
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id): Response
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return Inertia::render('products/Index', [
            'products' => Product::all(),
        ]);
    }
}
