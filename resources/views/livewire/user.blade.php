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

        <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin">
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
                @foreach ($users as $user)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="capitalize">{{ $user->role }}</td>
                        <td>{{ Carbon::parse($user->created_at)->translatedFormat('d F Y H:i') }}
                        </td>
                        <td>
                            <div class="flex justify-center items-center gap-2">
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin">
                                    View
                                </button>
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin">
                                    Edit
                                </button>
                                <button class="btn text-white btn-sm bg-black border-none px-3 text-sm font-thin">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
