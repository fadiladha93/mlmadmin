<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Confirm Action</h3>
                </div>

                <div class="modal-body">
                    <slot name="message">
                        <div class="message">
                            You are about to make a change to the tree, please review before submitting.
                        </div>
                    </slot>
                    <slot name="body"></slot>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info btn-sm btn-submit" @click="$emit('submit')">Submit</button>
                    <button type="button" class="btn btn-secondary btn-sm" @click="$emit('close')">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ConfirmModal',
    }
</script>
