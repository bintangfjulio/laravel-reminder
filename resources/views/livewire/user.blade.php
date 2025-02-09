@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <h1 class="font-bold text-2xl">List User</h1>

    <div class="flex justify-between my-6 items-center">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-3 w-3/5 md:w-1/4">
            <input wire:model.live.debounce.400ms="search" type="text"
                class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full"
                placeholder="Search name..." />
        </label>

        <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
            wire:click="openModal('tambah')">
            Add User
        </button>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra">
            <thead class="text-black font-bold">
                <tr class="text-md text-center">
                    <td>No</td>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Role</td>
                    <td>Registered At</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="capitalize">{{ $user->role }}</td>
                        <td>{{ Carbon::parse($user->created_at)->translatedFormat('d F Y H:i') }}
                        </td>
                        <td>
                            <div class="flex justify-center items-center gap-2">
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                    wire:click="openModal('lihat', {{ $user->id }})">
                                    View
                                </button>
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                    wire:click="openModal('edit', {{ $user->id }})">
                                    Edit
                                </button>
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin"
                                    wire:click="openModal('hapus', {{ $user->id }})">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'hapus')
                <p>Are you sure to delete user <span class="text-red-500 font-medium">
                        {{ $name }}</span>?
                </p>
                <div class="modal-action">
                    <div class="flex space-x-2 justify-end">
                        <button
                            class="btn btn-sm btn-outline text-black border-black hover:bg-black hover:text-white hover:border-none"
                            wire:click="resetModal">Close</button>

                        <button class="btn btn-error btn-sm text-white" wire:click="delete">Yes, delete</button>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="saveData">
                    <div class="form-control mb-2">
                        <label class="label">
                            <span class="label-text">Name <span class="text-red-500">*</span></span>
                        </label>
                        <input wire:model.blur="name" type="text" placeholder="Name"
                            {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="input input-bordered @error('name') border-red-500 @enderror" required />
                        @error('name')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-control mb-2">
                        <label class="label">
                            <span class="label-text">Email <span class="text-red-500">*</span></span>
                        </label>
                        <input wire:model.blur='email' type="email" placeholder="email"
                            {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="input input-bordered @error('email') border-red-500 @enderror" required />
                        @error('email')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($modalAction != 'lihat')
                        <div class="form-control mb-2">
                            <label class="label">
                                @if ($modalAction === 'tambah')
                                    <span class="label-text">Password <span class="text-red-500">*</span></span>
                                @else
                                    <span class="label-text">Reset Password</span>
                                @endif
                            </label>
                            <input wire:model.blur='password' type="password" placeholder="password"
                                class="input input-bordered @error('password') border-red-500 @enderror" />
                            @error('password')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    @if ($modalAction === 'tambah' || ($modalAction === 'edit' && $password))
                        <div class="form-control mb-2">
                            <label class="label">
                                <span class="label-text">Konfirmasi Password <span class="text-red-500">*</span></span>
                            </label>
                            <input wire:model.blur='confirmPassword' type="password" placeholder="Konfirmasi password"
                                class="input input-bordered  @error('confirmPassword') border-red-500 @enderror"
                                required />
                            @error('confirmPassword')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <label class="form-control mb-2">
                        <div class="label">
                            <span class="label-text">Role <span class="text-red-500">*</span></span>
                        </div>
                        <select wire:model.blur="role" {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="select select-bordered select-md @error('role') border-red-500 @enderror">
                            <option value="" disabled>Select Role</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        @error('role')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="modal-action">
                        <div class="flex space-x-2 justify-end">
                            <button type="button"
                                class="btn btn-sm btn-outline text-black border-black hover:bg-black hover:text-white hover:border-none"
                                wire:click="resetModal">Close</button>

                            @if ($modalAction != 'lihat')
                                <button type="submit"
                                    class="btn btn-sm bg-black text-white">{{ $modalAction === 'edit' ? 'Save' : 'Add' }}</button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </dialog>
</div>
