<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Mail\SendAccountInfoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('employee_code', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%") ;
            });
        }
        $employees = $query->with(['department', 'position'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('admin.employees.create', compact('departments', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:employees',
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'department_id' => 'required',
            'position_id' => 'required',
            'phone' => 'required',
            'salary' => 'required|numeric',
            'status' => 'required',
        ]);

        $password = \Illuminate\Support\Str::random(8);
        $verificationToken = \Illuminate\Support\Str::random(60);
        $user = \App\Models\User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => 'employee',
            'status' => true,
            'verification_token' => $verificationToken,
            'email_verified_at' => null,
        ]);

        $employee = \App\Models\Employee::create([
            'user_id' => $user->id,
            'employee_code' => $request->employee_code,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'department_id' => $request->department_id,
            'position_id' => $request->position_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'salary' => $request->salary,
            'status' => $request->status,
        ]);

        // Gửi email xác thực tài khoản
        Mail::send('emails.verify', ['token' => $verificationToken], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Xác thực tài khoản');
        });

        // Gửi email thông tin tài khoản
        Mail::to($user->email)->send(new SendAccountInfoMail($user, $password));

        return redirect()->route('employees.index')->with('success', 'Thêm nhân viên thành công, đã gửi email xác thực và thông tin tài khoản!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::all();
        $positions = Position::all();
        return view('admin.employees.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $validated = $request->validate([
            'employee_code' => 'required|string|max:255|unique:employees,employee_code,' . $employee->id,
            'email' => 'required|email|max:255|unique:employees,email,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'salary' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,on_leave',
            'avatar' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('avatar')) {
            if ($employee->avatar) {
                Storage::disk('public')->delete($employee->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Cập nhật nhân viên thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->avatar) {
            Storage::disk('public')->delete($employee->avatar);
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Xóa nhân viên thành công!');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.employee.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'different:current_password'],
            'new_password_confirmation' => ['nullable', 'same:new_password'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('admin.employee.profile')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }
}
