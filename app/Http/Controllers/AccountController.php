<?php

namespace App\Http\Controllers;

use App\Repository\UserRepository;
use App\Http\Requests\UpdateAccountRequest;

class AccountController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show()
    {
        return view('account.show', [
            'user' => $this->userRepository->getAuthUser()
        ]);
    }

    public function edit()
    {
        return view('account.edit', [
            'user' => $this->userRepository->getAuthUser()
        ]);
    }

    public function update(UpdateAccountRequest $request)
    {
        $this->userRepository->updateAuthUser($request->validated());

        return redirect()->route('account.show')
            ->with('success-message', 'Successfully edited account.');
    }

    public function delete()
    {
        return view('account.confirm-delete');
    }

    public function destroy()
    {
        $this->userRepository->deleteAuthUser();

        return redirect()->route('home')
            ->with('success-message', 'Successfully deleted account.');
    }

    public function export()
    {
        $fileName = uniqid();
        $path = storage_path('app');
        $exportName = sprintf('export_%s.json', date('Y_m_d'));

        $this->userRepository->exportUserData($fileName);

        return response()
            ->download($path . '/' . $fileName, $exportName)
            ->deleteFileAfterSend();
    }
   
    public function showOAuthSettings()
    {
        return view('account.oauth-settings');
    }
}