<?php

namespace App\Http\Controllers;

use App\Mail\ContactRequest;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\SpeakerSearchRequest;
use App\Repository\UserPublicProfileRepository;

class PublicProfileController extends Controller
{
    private $userPublicProfileRepository;

    public function __construct(UserPublicProfileRepository $publicProfileRepository)
    {
        $this->userPublicProfileRepository = $publicProfileRepository;
    }

    public function index(SpeakerSearchRequest $request) 
    {
        return view('account.public-profile.index', [
            'speakers' => $this->userPublicProfileRepository->getPublicSpeakerOrderByName($request->query('query')),
            'query' => $request->original,
        ]);
    }

    public function show($profileSlug)
    {
        $user = $this->userPublicProfileRepository->findPublicUserByProfileSlug($profileSlug);
        return view('account.public-profile.show', [
            'user' => $user,
            'talks' => $this->userPublicProfileRepository->getOrderedUserPublicTalks($user),
            'bios' => $this->userPublicProfileRepository->getUserPublicBio($user),
        ]);
    }

    public function showTalk($profileSlug, $talkId)
    {
        $user = $this->userPublicProfileRepository->findPublicUserByProfileSlug($profileSlug);
        return view('talks.show-public', [
            'user' => $user,
            'talk' => $this->userPublicProfileRepository->findUserPublicTalk($user, $talkId),
        ]);
    }

    public function showBio($profileSlug, $bioId)
    {
        $user = $this->userPublicProfileRepository->findPublicUserByProfileSlug($profileSlug);
        return view('bios.show-public', [
            'user' => $user,
            'bio' => $this->userPublicProfileRepository->findUserPublicBio($user, $bioId),
        ]);
    }

    public function showEmailForm($profileSlug)
    {
        $user = $this->userPublicProfileRepository->findPublicUserByProfileSlug($profileSlug);
        return view('account.public-profile.email', [
            'user' => $user,
        ]);
    }

    public function postEmail(SendEmailRequest $request, $profileSlug)
    {
        $user = $this->userPublicProfileRepository->findPublicUserByProfileSlug($profileSlug);
        $validatedData = $request->validated();
        Mail::to($user->email)->send(new ContactRequest($validatedData['email'], $validatedData['name'], $validatedData['message']));
        return redirect()->route('speakers-public.show', $user->profile_slug)
            ->with('success-message', 'Message sent!');
    }
}