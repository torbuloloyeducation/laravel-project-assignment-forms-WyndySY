<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
}); 

// both routes above works but view works only for static webpages.  ← # is not valid PHP comment

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});


// Activity 2 - email form
Route::get('/formtest', function () {
    return view('formtest', ['emails' => session('emails', [])]);  // ← was 'emails', fix to 'formtest'
});

Route::post('/formtest', function () {
    $emails = session('emails', []);

    if (count($emails) >= 5) {
        return back()->with('warning', 'Maximum of 5 emails reached.')->withInput();
    }

    request()->validate([
        'email' => 'required|email',
    ]);

    $newEmail = request('email');

    if (in_array($newEmail, $emails)) {
        return back()->with('error', 'That email is already in the list.')->withInput();
    }

    $emails[] = $newEmail;
    session(['emails' => $emails]);

    return back()->with('success', 'Email added successfully!');
});

Route::post('/formtest/delete', function () {
    $emails = session('emails', []);
    $emailToDelete = request('email');
    $emails = array_values(array_filter($emails, fn($e) => $e !== $emailToDelete));
    session(['emails' => $emails]);
    return back()->with('success', 'Email removed.');
});