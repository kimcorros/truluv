<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    profile: {
        age: number | null;
        gender: string | null;
        bio: string | null;
    };
}>();

const form = useForm({
    age: props.profile.age ?? '',
    gender: props.profile.gender ?? '',
    bio: props.profile.bio ?? '',
});

function submit(): void {
    form.put('/profiles/me');
}
</script>

<template>
    <Head title="Your profile" />

    <div class="max-w-lg p-4">
        <h1 class="mb-4 text-xl font-semibold">Your profile</h1>

        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label class="block text-sm font-medium" for="age">Age</label>
                <input
                    id="age"
                    v-model="form.age"
                    type="number"
                    min="18"
                    max="120"
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                />
                <p v-if="form.errors.age" class="mt-1 text-sm text-red-600">{{ form.errors.age }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium" for="gender">Gender (optional)</label>
                <input
                    id="gender"
                    v-model="form.gender"
                    type="text"
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                />
                <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">{{ form.errors.gender }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium" for="bio">Bio</label>
                <textarea
                    id="bio"
                    v-model="form.bio"
                    rows="5"
                    class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                ></textarea>
                <p v-if="form.errors.bio" class="mt-1 text-sm text-red-600">{{ form.errors.bio }}</p>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="rounded bg-gray-900 px-4 py-2 text-sm text-white disabled:opacity-50"
            >
                Save profile
            </button>
        </form>
    </div>
</template>
