<template>
    <tr>
        <td scope="col">
            <input type="checkbox" :checked="selectAll" @change="toggleSelection">
        </td>
        <td>{{ index + 1 }}</td>
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
            <a href="#" @click.prevent="$emit('confirmUserDeletion', user)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>
</template>

<script setup>
    import { useToastr } from '@/toastr.js';

    import { ref } from 'vue'
    
    const toastr = useToastr();
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
        selectAll: Boolean,
    });

    const emit = defineEmits(['editUser', 'toggleSelection', 'confirmUserDeletion']);

    const changeRole = (user, role) => {
        axios.put(`api/users/${user.id}/update-role`, {role: role})
        .then(response => {
            toastr.success(response.data.message);
        })
    }

    const toggleSelection = () => {
        emit('toggleSelection', props.user)
    }
</script>
