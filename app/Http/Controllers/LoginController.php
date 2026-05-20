public function authenticate(Request $request)
{
    // Cari user berdasarkan email
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Auth::attempt() akan otomatis mengecek password dan membuat Session Laravel
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard'); // Arahkan ke Dashboard Controller kita
    }

    return back()->withErrors(['email' => 'Login gagal.']);
}
