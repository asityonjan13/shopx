<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use FileUploadTrait;

    function index(): View
    {
        return view('frontend.dashboard.account.index');
    }

    function profileUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email,' . auth('web')->id()],
            'avatar' => ['nullable', 'image', 'max:2048']
        ]);

        $user = auth('web')->user();

        if($request->hasFile('avatar')){
            $filepath = $this->uploadFile($request->file('avatar'), $user->avatar);
            if($filepath) {
                $user->avatar = $filepath;
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with(
            AlertService::updated()
        );
    }

    function passwordUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth('web')->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with(
            AlertService::updated('Password updated successfully.')
        );
    }
}
