<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePegawaiRequest;
use App\Http\Requests\Admin\UpdatePegawaiRequest;
use App\Models\Position;
use App\Models\Department;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['position', 'department', 'atasan', 'roles']);

        if (($search = $request->input('search')) !== null && trim($search) !== '') {
            $term = mb_strtolower(trim($search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(nip) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$term}%"]);
            });
        }

        if ($departmentId = $request->input('department_id')) {
            $query->where('department_id', $departmentId);
        }

        if ($positionId = $request->input('position_id')) {
            $query->where('position_id', $positionId);
        }

        $pegawais = $query->orderBy('name')->paginate(10)->withQueryString();
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('id')->get();

        return view('admin.pegawai.index', compact('pegawais', 'departments', 'positions'));
    }

    public function create()
    {
        $positions = Position::orderBy('id')->get();
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        // Atasan potential is generally position level 1 to 5
        $atasans = User::whereHas('position', function ($q) {
            $q->whereIn('level', [1, 2, 3, 4, 5]);
        })->orderBy('name')->get();

        return view('admin.pegawai.create', compact('positions', 'departments', 'atasans', 'roles'));
    }

    public function store(StorePegawaiRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        $role = $data['role'];
        unset($data['role']);

        $user = User::create($data);
        $user->assignRole($role);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data Pegawai berhasil ditambahkan.');
    }

    public function show(User $pegawai)
    {
        $pegawai->load(['position', 'department', 'atasan', 'bawahan', 'roles']);

        return view('admin.pegawai.show', compact('pegawai'));
    }

    public function edit(User $pegawai)
    {
        $positions = Position::orderBy('id')->get();
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $atasans = User::where('id', '!=', $pegawai->id)
            ->whereHas('position', function ($q) {
                $q->whereIn('level', [1, 2, 3, 4, 5]);
            })->orderBy('name')->get();

        return view('admin.pegawai.edit', compact('pegawai', 'positions', 'departments', 'atasans', 'roles'));
    }

    public function update(UpdatePegawaiRequest $request, User $pegawai)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $role = $data['role'];
        unset($data['role']);

        $pegawai->update($data);
        $pegawai->syncRoles([$role]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data Pegawai berhasil diperbarui.');
    }

    public function destroy(User $pegawai)
    {
        if ($pegawai->bawahan()->count() > 0) {
            return redirect()->route('admin.pegawai.index')
                ->with('error', 'Gagal menghapus! Pegawai ini memiliki bawahan terdaftar. Ubah atasan bawahannya terlebih dahulu.');
        }

        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data Pegawai berhasil dihapus.');
    }
}
