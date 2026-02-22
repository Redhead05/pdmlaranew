<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ url()->current() }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Admin-only fields: role and is_active -->
        @if(auth()->check() && method_exists(auth()->user(), 'hasAnyRole') && auth()->user()->hasAnyRole(['admin','adminlanding']))
            <div>
                <x-input-label for="role" :value="__('Role')" />
                <select id="role" name="role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md">
                    @foreach(\Spatie\Permission\Models\Role::all() as $r)
                        <option value="{{ $r->name }}" {{ old('role', $user->roles->pluck('name')->first() ?? '') == $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('role')" />
            </div>

            <div class="flex items-center gap-4">
                <x-input-label for="is_active" :value="__('Active')" />
                <input type="checkbox" id="is_active" name="is_active" value="1" class="ms-2" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
            </div>
        @endif

        <!-- User detail fields -->
        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md">
                <option value="">Select gender</option>
                <option value="L" {{ old('gender', $user->detail->gender ?? '') == 'L' ? 'selected' : '' }}>L</option>
                <option value="P" {{ old('gender', $user->detail->gender ?? '') == 'P' ? 'selected' : '' }}>P</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        <div>
            <x-input-label for="address_home" :value="__('Home Address')" />
            <x-text-input id="address_home" name="address_home" type="text" class="mt-1 block w-full" :value="old('address_home', $user->detail->address_home ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('address_home')" />
        </div>

        <div>
            <x-input-label for="home_city" :value="__('Home City')" />
            <x-text-input id="home_city" name="home_city" type="text" class="mt-1 block w-full" :value="old('home_city', $user->detail->home_city ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('home_city')" />
        </div>

        <div>
            <x-input-label for="address_work" :value="__('Work Address')" />
            <x-text-input id="address_work" name="address_work" type="text" class="mt-1 block w-full" :value="old('address_work', $user->detail->address_work ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('address_work')" />
        </div>

        <div>
            <x-input-label for="work_city" :value="__('Work City')" />
            <x-text-input id="work_city" name="work_city" type="text" class="mt-1 block w-full" :value="old('work_city', $user->detail->work_city ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('work_city')" />
        </div>

        <div>
            <x-input-label for="type_asesor" :value="__('Type Asesor')" />
            <x-text-input id="type_asesor" name="type_asesor" type="text" class="mt-1 block w-full" :value="old('type_asesor', $user->detail->type_asesor ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('type_asesor')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            {{-- toast is handled globally by profile.edit view using session('toast') --}}
        </div>
    </form>
</section>
