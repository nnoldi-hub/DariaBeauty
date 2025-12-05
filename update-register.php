<?php
/**
 * UPDATE REGISTER VIEW
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/update-register.php
 * ȘTERGE DUPĂ FOLOSIRE!
 */

$rootDir = dirname(__DIR__);
$registerFile = $rootDir . '/resources/views/auth/register.blade.php';

$newContent = <<<'BLADE'
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Te înregistrezi ca...')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">-- Selectează rolul --</option>
                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client (programez servicii)</option>
                <option value="specialist" {{ old('role') == 'specialist' ? 'selected' : '' }}>Specialist (ofer servicii)</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
            <p class="mt-1 text-sm text-gray-600">
                <strong>Client:</strong> Poți căuta specialiști și programa servicii<br>
                <strong>Specialist:</strong> Poți oferi servicii și primi programări
            </p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
BLADE;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Register View</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .box { background: #252526; padding: 15px; margin: 10px 0; border-left: 3px solid #007acc; }
    </style>
</head>
<body>
    <h1 style='color:#4ec9b0;'>🔄 Update Register View</h1>
    
    <div class='box'>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
            // Make backup
            $backupFile = $registerFile . '.backup.' . date('Y-m-d-His');
            if (file_exists($registerFile)) {
                copy($registerFile, $backupFile);
                echo "<p class='success'>✓ Backup created: " . basename($backupFile) . "</p>";
            }
            
            // Write new content
            if (file_put_contents($registerFile, $newContent)) {
                echo "<p class='success'><strong>✓ File updated successfully!</strong></p>";
                echo "<p class='warning'>Now clear cache and refresh /register page</p>";
                echo "<p><a href='clear-cache.php'><button style='background:#007acc; color:white; padding:10px 20px; border:none; cursor:pointer; margin:5px;'>Clear Cache</button></a></p>";
                echo "<p><a href='/register' target='_blank'><button style='background:#4ec9b0; color:white; padding:10px 20px; border:none; cursor:pointer; margin:5px;'>Open Register Page</button></a></p>";
                echo "<hr><p class='error'><strong>ȘTERGE ACEST FIȘIER ACUM: update-register.php</strong></p>";
            } else {
                echo "<p class='error'>✗ Failed to write file. Check permissions!</p>";
            }
        } else {
            ?>
            <h2>Confirm Update</h2>
            <p class='warning'>This will update: <code><?php echo $registerFile; ?></code></p>
            <p>A backup will be created automatically.</p>
            
            <form method="POST">
                <input type="hidden" name="confirm" value="1">
                <button type="submit" style='background:#f48771; color:white; padding:10px 20px; border:none; cursor:pointer; font-size:16px;'>
                    ✓ Confirm Update
                </button>
            </form>
            <?php
        }
        ?>
    </div>
</body>
</html>
