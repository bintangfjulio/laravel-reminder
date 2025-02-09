<div>
    <form class="card-body" wire:submit.prevent="register">
        <h1 class="text-center font-bold">Daftar</h1>
        <div class="form-control">
            <label class="label">
                <span class="label-text">Nama <span class="text-red-500">*</span></span>
            </label>
            <input wire:model.blur="name" type="text" placeholder="Nama"
                class="input input-bordered @error('name') border-red-500 @enderror" required />
            @error('name')
                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
            @enderror
        </div>
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
        <div class="form-control">
            <label class="label">
                <span class="label-text">Konfirmasi Password <span class="text-red-500">*</span></span>
            </label>
            <input wire:model.blur='confirmPassword' type="password" placeholder="Konfirmasi password"
                class="input input-bordered  @error('confirmPassword') border-red-500 @enderror" required />
            @error('confirmPassword')
                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
            @enderror
        </div>
        <label class="label">
            <a wire:navigate href="/login" class="label-text-alt link link-hover">Sudah punya akun?</a>
        </label>
        <div class="form-control mt-6">
            <button class="btn btn-ghost bg-black text-white" type="submit">Daftar</button>
        </div>
    </form>
</div>
