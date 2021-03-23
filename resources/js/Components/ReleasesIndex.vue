<template>
    <div>
        <!-- Generate Release -->
        <jet-form-section @submitted="createRelease">
            <template #title>
                Create a Release
            </template>

            <template #description>
                Create a new release.
            </template>

            <template #form>
                <!-- Version -->
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="version" value="Version" />
                    <jet-input id="version" type="text" class="mt-1 block w-full" v-model="createReleaseForm.name" autofocus />
                    <jet-input-error :message="createReleaseForm.errors.version" class="mt-2" />
                </div>

                <!-- Date -->
                <div class="col-span-6">
                    <jet-label for="released_on" value="Date of release" />
                    <jet-input id="released_on" type="text" class="mt-1 block w-full" v-model="createReleaseForm.released_on" autofocus />
                    <jet-input-error :message="createReleaseForm.errors.released_on" class="mt-2" />
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="createReleaseForm.recentlySuccessful" class="mr-3">
                    Created.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': createReleaseForm.processing }" :disabled="createReleaseForm.processing">
                    Create
                </jet-button>
            </template>
        </jet-form-section>

        <div v-if="releases.length > 0">
            <jet-section-border />

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <jet-action-section>
                    <template #title>
                        Releases
                    </template>

                    <template #description>
                        List of releases.
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between" v-for="release in releases" :key="release.id">
                                <div>
                                    {{ release.version }}
                                </div>

                                    <div class="text-sm text-gray-400">
                                        {{ release.released_on }}
                                    </div>

                                <div class="flex items-center">

                                    <button class="cursor-pointer ml-6 text-sm text-gray-400 underline"
                                        @click="updateRelease(release)"
                                    >
                                        Update
                                    </button>

                                    <button class="cursor-pointer ml-6 text-sm text-red-500" @click="deleteRelease(release)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </jet-action-section>
            </div>
        </div>

    </div>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetButton from '@/Jetstream/Button'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default {
        components: {
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetDangerButton,
            JetDialogModal,
            JetFormSection,
            JetInput,
            JetCheckbox,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            JetSectionBorder,
        },

        props: [
            'releases',
        ],

        data() {
            return {
                createReleaseForm: this.$inertia.form({
                    version: '',
                    released_on: '',
                    notes: '',
                }),
            }
        },

        methods: {
            createRelease() {
                this.createReleaseForm.post(route('releases.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.createReleaseForm.reset()
                    }
                })
            },

            updateRelease(release) {
            },

            deleteRelease(release) {
            },
        },
    }
</script>
