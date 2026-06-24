<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import BaseAvatar from '@/components/base/BaseAvatar.vue';
import BaseButton from '@/components/base/BaseButton.vue';
import BaseCard from '@/components/base/BaseCard.vue';
import BaseInput from '@/components/base/BaseInput.vue';
import BaseSelect from '@/components/base/BaseSelect.vue';
import BaseTextarea from '@/components/base/BaseTextarea.vue';

const genderOptions = [
    { value: '', label: 'Prefer not to say' },
    { value: 'female', label: 'Woman' },
    { value: 'male', label: 'Man' },
    { value: 'non-binary', label: 'Non-binary' },
    { value: 'other', label: 'Other' },
];

const props = defineProps<{
    profile: {
        age: number | null;
        gender: string | null;
        photo_url: string | null;
        bio: string | null;
    };
}>();

const form = useForm({
    age: props.profile.age ?? '',
    gender: props.profile.gender ?? '',
    photo_url: props.profile.photo_url ?? '',
    bio: props.profile.bio ?? '',
});

function submit(): void {
    form.put('/profiles/me');
}
</script>

<template>
    <Head title="Your profile" />

    <div class="mx-auto w-full max-w-2xl p-4 sm:p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Your profile</h1>
            <p class="mt-1 text-sm text-zinc-400">
                This is how others will see you on TruLuv.
            </p>
        </div>

        <BaseCard>
            <form class="space-y-5" @submit.prevent="submit">
                <div class="flex items-center gap-4">
                    <BaseAvatar
                        :src="form.photo_url || null"
                        :alt="'You'"
                        size="lg"
                        ring
                    />
                    <div class="flex-1">
                        <BaseInput
                            v-model="form.photo_url"
                            label="Photo URL"
                            type="url"
                            placeholder="https://..."
                            :error="form.errors.photo_url"
                            hint="Paste a link to your best photo."
                        />
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <BaseInput
                        v-model="form.age"
                        label="Age"
                        type="number"
                        required
                        :error="form.errors.age"
                    />
                    <BaseSelect
                        v-model="form.gender"
                        label="Gender"
                        :options="genderOptions"
                        :error="form.errors.gender"
                    />
                </div>

                <BaseTextarea
                    v-model="form.bio"
                    label="Bio"
                    required
                    :rows="5"
                    placeholder="Tell people what makes you, you."
                    :error="form.errors.bio"
                />

                <div class="flex justify-end">
                    <BaseButton type="submit" :loading="form.processing">
                        Save profile
                    </BaseButton>
                </div>
            </form>
        </BaseCard>
    </div>
</template>
