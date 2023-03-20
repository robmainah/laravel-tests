<template>
    <tr>
        <th>{{ index + 1 }}</th>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.date_created }}</td>
        <td>
            <select name="" class="form-control" @change="changeRole(user, $event.target.value)">
                <option v-for="(role, index) in roles" :key="index"
                :value="role.value"
                :selected="user.role === role.name">{{ role.name }}</option>
            </select>
        </td>
        <td>
            <a href="#" @click.prevent="$emit('editUser', user)">
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="confirmDeleteUser(user)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>

    <button ref="confirmDeleteUserBtn" hidden data-toggle="modal" data-target="#deletUserModal">
    </button>

    <div class="modal fade" ref="deletUserModal" id="deletUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Confirm to Delete User?
                    </h5>
                    <button type="button" ref="closeDeleteModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <h5>Are you sure you want to delete the user?</h5>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" @click="deleteUser()" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useToastr } from '@/toastr.js';

    import { ref } from 'vue'
    
    const toastr = useToastr();
    const closeDeleteModal = ref(null);
    const confirmDeleteUserBtn = ref(null);
    const form = ref(null);
    const roles = ref([
        {
            name: 'ADMIN',
            value: 1
        },
        {
            name: 'USER',
            value: 2
        },
    ])

    const props = defineProps({
        user: Object,
        index: Number,
    });

    const emit = defineEmits(['userDeleted', 'editUser']);

    const confirmDeleteUser = (user) => {
        form.value = user.id;
        confirmDeleteUserBtn.value.click();
    }

    const deleteUser = () => {
        axios.delete(`/api/users/${form.value.id}`)
        .then(response => {
            closeDeleteModal.value.click();
            toastr.success(response.data.message);
            emit('userDeleted', form.value.id);
            form.value = null;
        }).catch(error => {
            toastr.error(error.response.data.message);
        });
    }

    const changeRole = (user, role) => {
        axios.put(`api/users/${user.id}/update-role`, {role: role})
        .then(response => {
            console.log(response);
            toastr.success(response.data.message);
        })
    }
</script>
