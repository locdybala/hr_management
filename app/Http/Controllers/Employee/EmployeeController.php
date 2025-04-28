<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $employee = Auth::user()->employee;
        return view('employee.dashboard', compact('employee'));
    }

    public function editProfile()
    {
        $employee = Auth::user()->employee;
        return view('employee.profile.edit', compact('employee'));
    }

    public function updateProfile(Request $request)
    {
        $employee = Auth::user()->employee;

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:employees,email,' . $employee->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($employee->avatar) {
                Storage::disk('public')->delete($employee->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $employee->update($validated);

        return redirect()->route('employee.dashboard')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }
} 