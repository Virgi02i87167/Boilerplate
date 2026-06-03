<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;

class ClienteController extends Controller
{
    public function index()
    {
        Gate::authorize('gestionar-clientes');
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        Gate::authorize('gestionar-clientes');
        return view('clientes.create');
    }

    public function store(StoreClienteRequest $request)
    {
        // Crear registro en la BD
        Cliente::create($request->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function edit(Cliente $cliente)
    {
        Gate::authorize('gestionar-clientes');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        Gate::authorize('gestionar-clientes');
        try {
            $cliente->delete();
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('clientes.index')->with('error', 'No se puede eliminar el cliente porque tiene reservaciones registradas.');
        }
    }
}
