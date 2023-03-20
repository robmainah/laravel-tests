<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <button type="button" @click="is_editing = false" ref="addNewUserBtn" class="btn btn-primary mb-2" data-toggle="modal" data-target="#userFormModal">
                    Add New User
                </button>
                <div>
                    <input type="text" v-model="searchQuery" class="form-control"
                    placeholder="Search...">
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Date Created</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody v-if="users.data.length">
                            <Item v-for="(user, index) in users.data" 
                                :key="index"
                                :user=user
                                :index=index
                                @user-deleted="userDeleted"
                                @edit-user="editUser"
                            />
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" class="text-center text-danger">No results found...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <Bootstrap4Pagination :data="users" @pagination-change-page="getUsers"/>
        </div>
    </div>

    <div class="modal fade" ref="userFormModal" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="is_editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h5>
                    <button type="button" ref="closeUserModalRef" @click="closeUserModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <Form ref="form"
                    @submit="handleSubmit"
                    :validation-schema="is_editing ? editUserSchema : createUserSchema"
                    v-slot="{ errors }" :initial-values="form ? form.getValues() : form ">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control" :class="{'is-invalid': errors.name}" id="name"
                                aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control" :class="{'is-invalid': errors.email}" id="email"
                                aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Password</label>
                            <Field name="password" type="password" class="form-control" :class="{'is-invalid': errors.password}" id="password"
                                aria-describedby="nameHelp" placeholder="Enter password" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeUserModal" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import Item from './loop/Item.vue'

    import { ref, onMounted, watch } from 'vue';
    import { Form, Field } from 'vee-validate';
    import * as yup from 'yup';
    import { useToastr } from '../../toastr.js';
    import { debounce } from 'lodash';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';

    const toastr = useToastr();
    const users = ref({data: []});
    const is_editing = ref(false);
    const form = ref(null);
    const closeUserModalRef = ref(null);
    const addNewUserBtn = ref(null);

    const getUsers = (page=1) => {
        axios.get(`/api/users?page=${page}`)
        .then(response => {
            users.value = response.data
        });
    };

    const createUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().required().min(4),
    })

    const editUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().when((password, schema) => {
            return password ? schema.min(4) : schema;
        }),
    });

    const createUser = (values, { resetForm, setErrors }) => {
        axios.post('/api/users', values)
        .then(response => {
            users.value.unshift(response.data.data);
            closeUserModalRef.value.click();
            resetForm();
            toastr.success('User created successfully');
        }).catch(error => {
            toastr.error('There was an error. Please try again.');
            if (error.response.data.errors) {
                setErrors(error.response.data.errors)
            }
        })
    };

    const editUser = (user) => {
        addNewUserBtn.value.click();
        is_editing.value = true;
        form.value.setValues(user)
    }

    const handleSubmit = (values, actions) => {
        is_editing.value ? updateUser(values, actions) : createUser(values, actions);
    }

    const updateUser = (values, { setErrors }) => {
        axios.patch(`/api/users/${form.value.getValues()['id']}`, values)
        .then(response => {
            const index = users.value.findIndex(user => user.id === response.data.data.id);
            users.value[index] = response.data.data
            closeUserModalRef.value.click();
            toastr.success('User updated successfully');
        }).catch(error => {
            if (error.response.data.errors) {
                setErrors(error.response.data.errors)
            }
            toastr.error(error.response.data.message);
        });
    }

    const userDeleted = (userId) => {
        const index = users.value.findIndex(user => user.id === userId);
        users.value.splice(index, 1);
    }

    const closeUserModal = () => {
        form.value = null;
    }

    const searchQuery = ref(null)

    const search = () => {
        axios.get(`/api/search`, {
            params: {
                query: searchQuery.value,
            }
        })
        .then(response => {
            users.value = response.data
        }).catch(error => {
            console.log(error);
        })
    }

    watch(searchQuery, debounce(() => {
        search();
    }, 300));    
    
    onMounted(() => {
        getUsers();
    });
</script>
