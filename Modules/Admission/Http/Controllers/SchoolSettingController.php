<?php

namespace Modules\Admission\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use RuntimeException;

class SchoolSettingController extends Controller
{
    private const SETTINGS = [
        'principal' => 'PRINCIPAL',
        'school_year' => 'SCHOOL_YEAR',
        'school_name' => 'SCHOOL_NAME',
        'school_managing_agency' => 'SCHOOL_MANAGING_AGENCY',
        'school_login_description' => 'SCHOOL_LOGIN_DESCRIPTION',
    ];

    private const DEFAULTS = [
        'PRINCIPAL' => 'Hoàng Thụy Bích Thủy',
        'SCHOOL_YEAR' => '2026-2027',
        'SCHOOL_NAME' => 'TRƯỜNG TIỂU HỌC NGUYỄN VĂN HƯỞNG',
        'SCHOOL_MANAGING_AGENCY' => 'ỦY BAN NHÂN DÂN PHƯỜNG PHÚ THUẬN',
        'SCHOOL_LOGIN_DESCRIPTION' => 'Hệ thống quản trị & đăng nhập giáo viên / quản lý',
    ];

    public function edit(): View
    {
        $this->addMissingEnvironmentDefaults();

        $settings = [];

        foreach (self::SETTINGS as $field => $configKey) {
            $settings[$field] = (string) config('app.' . $field, '');
        }

        return view('Admission::pages.admin.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'principal' => ['required', 'string', 'max:255'],
            'school_year' => ['required', 'string', 'max:20', 'regex:/^\d{4}\s*-\s*\d{4}$/'],
            'school_name' => ['required', 'string', 'max:255'],
            'school_managing_agency' => ['required', 'string', 'max:255'],
            'school_login_description' => ['required', 'string', 'max:500'],
        ], [
            'school_year.regex' => 'Năm học phải có định dạng 2026-2027.',
        ]);

        $envPath = base_path('.env');
        $contents = file_get_contents($envPath);

        if ($contents === false) {
            throw new RuntimeException('Không thể đọc tệp cấu hình .env.');
        }

        foreach (self::SETTINGS as $field => $envKey) {
            $contents = $this->setEnvironmentValue($contents, $envKey, $validated[$field]);
        }

        $this->writeEnvironmentFile($envPath, $contents);
        Artisan::call('config:clear');

        return to_route('admin.admission.settings.edit')
            ->with('success', 'Đã cập nhật thông tin nhà trường.');
    }

    private function setEnvironmentValue(string $contents, string $key, string $value): string
    {
        $escapedValue = str_replace(
            ["\\", '"', "\r", "\n"],
            ["\\\\", '\\"', ' ', ' '],
            trim($value)
        );
        $line = $key . '="' . $escapedValue . '"';
        $pattern = '/^[ \t]*' . preg_quote($key, '/') . '[ \t]*=.*$/m';

        if (preg_match($pattern, $contents) === 1) {
            return (string) preg_replace($pattern, $line, $contents, 1);
        }

        return rtrim($contents) . PHP_EOL . $line . PHP_EOL;
    }

    private function addMissingEnvironmentDefaults(): void
    {
        $envPath = base_path('.env');
        $contents = file_get_contents($envPath);

        if ($contents === false) {
            throw new RuntimeException('Không thể đọc tệp cấu hình .env.');
        }

        $updatedContents = $contents;

        foreach (self::DEFAULTS as $key => $defaultValue) {
            $pattern = '/^[ \t]*' . preg_quote($key, '/') . '[ \t]*=/m';

            if (preg_match($pattern, $updatedContents) !== 1) {
                $updatedContents = $this->setEnvironmentValue($updatedContents, $key, $defaultValue);
            }
        }

        if ($updatedContents !== $contents) {
            $this->writeEnvironmentFile($envPath, $updatedContents);
            Artisan::call('config:clear');
        }
    }

    private function writeEnvironmentFile(string $envPath, string $contents): void
    {
        $temporaryPath = $envPath . '.tmp';

        if (file_put_contents($temporaryPath, $contents, LOCK_EX) === false) {
            throw new RuntimeException('Không thể ghi tệp cấu hình tạm.');
        }

        chmod($temporaryPath, fileperms($envPath) & 0777);

        if (!rename($temporaryPath, $envPath)) {
            @unlink($temporaryPath);
            throw new RuntimeException('Không thể cập nhật tệp cấu hình .env.');
        }
    }
}
