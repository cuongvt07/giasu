<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query
        $query = User::with('tutor');
        
        // Tìm kiếm theo tên hoặc email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Lọc theo vai trò
        $role = $request->get('role', 'student'); // Mặc định là học sinh
        
        if ($role === 'admin') {
            $query->where('is_admin', true);
        } elseif ($role === 'tutor') {
            $query->whereHas('tutor');
        } elseif ($role === 'student') {
            $query->whereDoesntHave('tutor')
                  ->where('is_admin', false);
        }
        // Nếu role=all thì không lọc
        
        // Lấy danh sách người dùng
        $users = $query->latest()->paginate(15);
            
        // Đếm số lượng người dùng và gia sư
        $userCount = User::count();
        $tutorCount = Tutor::count();
        $normalUserCount = $userCount - $tutorCount;
        
        return view('admin.users.index', compact('users', 'userCount', 'tutorCount', 'normalUserCount', 'role'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_admin' => ['boolean'],
        ], [
            'name.required' => 'Tên không được bỏ trống',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.unique' => 'Email này đã được sử dụng',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin ?? false,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công.');
    }

    public function show(User $user)
    {
        // Tải thêm thông tin gia sư nếu người dùng này là gia sư
        $user->load('tutor');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'is_admin' => ['boolean'],
        ], [
            'name.required' => 'Tên không được bỏ trống',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'email.unique' => 'Email này đã được sử dụng',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->is_admin ?? false,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    public function destroy(User $user)
    {
        // Kiểm tra xem người dùng đang cố gắng xóa chính mình không
        if (Auth::check() && $user->getKey() === Auth::user()->getKey()) {
            return back()->with('error', 'Không thể xóa tài khoản của chính mình.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công.');
    }
} 