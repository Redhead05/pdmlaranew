<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" value="{{ old('name') }}" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- NIA -->
        <div class="mt-4">
            <x-input-label for="nia" value="NIA" />
            <x-text-input id="nia" name="nia" type="text" class="mt-1 block w-full" required value="{{ old('nia') }}" />
            <x-input-error :messages="$errors->get('nia')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" value="{{ old('email') }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" value="Role" />
            <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded">
                <option value="user" {{ old('role')==='user' ? 'selected' : '' }}>User</option>
                <option value="asesor" {{ old('role')==='asesor' ? 'selected' : '' }}>Asesor</option>
                <option value="admin" {{ old('role')==='admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Is Active -->
        <div class="mt-4 flex items-center">
            <input id="is_active" name="is_active" type="checkbox" class="rounded border-gray-300" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active" class="ms-2 text-sm text-gray-600">Aktif</label>
            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
        </div>

        <hr class="my-6">

        <!-- User Detail -->
        <div class="mt-4">
            <x-input-label for="address_home" value="Alamat Rumah" />
            <textarea id="address_home" name="address_home" class="mt-1 block w-full border-gray-300 rounded">{{ old('address_home') }}</textarea>
            <x-input-error :messages="$errors->get('address_home')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="home_city" value="Kota Rumah" />
            <x-text-input id="home_city" name="home_city" type="text" class="mt-1 block w-full" value="{{ old('home_city') }}" />
            <x-input-error :messages="$errors->get('home_city')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="address_work" value="Alamat Kantor" />
            <textarea id="address_work" name="address_work" class="mt-1 block w-full border-gray-300 rounded">{{ old('address_work') }}</textarea>
            <x-input-error :messages="$errors->get('address_work')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="work_city" value="Kota Kantor" />
            <x-text-input id="work_city" name="work_city" type="text" class="mt-1 block w-full" value="{{ old('work_city') }}" />
            <x-input-error :messages="$errors->get('work_city')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="gender" value="Gender" />
            <x-text-input id="gender" name="gender" type="text" class="mt-1 block w-full" placeholder="L / P" value="{{ old('gender') }}" />
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="type_asesor" value="Tipe Asesor" />
            <x-text-input id="type_asesor" name="type_asesor" type="text" class="mt-1 block w-full" value="{{ old('type_asesor') }}" />
            <x-input-error :messages="$errors->get('type_asesor')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Register
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
