<div>
    <form class="card-body" wire:submit.prevent="login">
        <h1 class="text-center font-bold">Masuk</h1>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Email <span class="text-red-500">*</span></span>
            </label>
            <input wire:model.blur='email' type="email" placeholder="email"
                class="input input-bordered @error('email') border-red-500 @enderror" required />
            @error('email')
                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Password <span class="text-red-500">*</span></span>
            </label>
            <input wire:model.blur='password' type="password" placeholder="password"
                class="input input-bordered @error('password') border-red-500 @enderror" required />
            @error('password')
                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
            @enderror
        </div>
        <label class="label">
            <a wire:navigate href="/register" class="label-text-alt link link-hover">Belum punya akun?</a>
        </label>
        <div class="form-control mt-6">
            <button class="btn btn-ghost bg-black text-white" type="submit">Login</button>
        </div>
    </form>
</div>
