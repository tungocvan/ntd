@extends('Admin::layouts.master')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Thông tin nhà trường</h1>
            <p class="mt-1 text-sm text-gray-600">
                Các thông tin này được sử dụng trên trang đăng nhập, biểu mẫu và biên nhận.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('admin.admission.settings.update') }}"
              class="max-w-3xl rounded-lg bg-white p-6 shadow">
            @csrf
            @method('PUT')

            @php
                $fields = [
                    'principal' => ['Hiệu trưởng', 'Hoàng Thụy Bích Thủy'],
                    'school_year' => ['Năm học', '2026-2027'],
                    'school_name' => ['Tên trường', 'TRƯỜNG TIỂU HỌC NGUYỄN VĂN HƯỞNG'],
                    'school_managing_agency' => ['Cơ quan quản lý', 'ỦY BAN NHÂN DÂN PHƯỜNG PHÚ THUẬN'],
                    'school_login_description' => ['Mô tả trang đăng nhập', 'Hệ thống quản trị & đăng nhập giáo viên / quản lý'],
                ];
            @endphp

            <div class="space-y-5">
                @foreach ($fields as $name => [$label, $placeholder])
                    <div>
                        <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-gray-700">
                            {{ $label }}
                        </label>
                        <input
                            id="{{ $name }}"
                            name="{{ $name }}"
                            type="text"
                            value="{{ old($name, $settings[$name]) }}"
                            placeholder="{{ $placeholder }}"
                            required
                            class="block w-full rounded-md border px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500
                                @error($name) border-red-500 @else border-gray-300 @enderror"
                        >
                        @error($name)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit"
                        class="rounded-md bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">
                    Lưu thay đổi
                </button>
                <a href="{{ route('admin.admission.index') }}"
                   class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50">
                    Quay lại
                </a>
            </div>
        </form>
    </div>
@endsection
