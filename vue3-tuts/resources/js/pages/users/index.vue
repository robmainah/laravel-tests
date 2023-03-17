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
            <button type="button" @click.self="editing = false" ref="addNewUserBtn" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addNewUserBtn">
                Add New User
            </button>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="index">
                                <th>{{ ++index }}</th>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>
                                    <a href="#" class="pointer" role=button @click.prevent="editUser(user)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" ref="userFormModal" id="addNewUserBtn" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add New User</span>
                    </h5>
                    <button type="button" ref="closeUserModal" @click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <Form ref="form"
                    @submit="handleSubmit"
                    :validation-schema="editing ? editUserSchema : createUserSchema"
                    v-slot="{ errors }" :initial-values="formValues">
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
                        <button type="button" @click="closeModal" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { Form, Field } from 'vee-validate';
    import * as yup from 'yup';
    import { useToastr } from '../../toastr.js';

    const toastr = useToastr();
    const users = ref([]);
    const editing = ref(false);
    const form = ref(null);
    const formValues = ref();

    const closeUserModal = ref(null);

    const getUsers = () => {
        axios.get('/api/users').then(data => {
            users.value = data.data
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
            users.value.unshift(response.data);
            closeUserModal.value.click();
            resetForm();
            toastr.success('User created successfully');
        }).catch(error => {
            toastr.error('There was an error. Please try again.');
            if (error.response.data.errors) {
                setErrors(error.response.data.errors)
            }
        })
    };

    const addNewUserBtn = ref(null);
    const editUser = (user) => {
        addNewUserBtn.value.click();
        editing.value = true;
        form.value.resetForm();

        formValues.value = {
            id: user.id,
            name: user.name,
            email: user.email,
        };
    }

    const handleSubmit = (values, actions) => {
        editing.value ? updateUser(values, actions) : createUser(values, actions);
    }

    const updateUser = (values, { setErrors, resetForm }) => {
        axios.patch(`/api/users/${formValues.value.id}`, values)
        .then(response => {
            const index = users.value.findIndex(user => user.id === response.data.id);
            users.value[index] = response.data
            closeUserModal.value.click();
            resetForm();
            toastr.success('User updated successfully');
        }).catch(error => {
            toastr.error('There was an error. Please try again.');
            if (error.response.data.errors) {
                setErrors(error.response.data.errors)
            }
        });
    }

    const closeModal = () => {
        formValues.value = {
            id: '',
            name: '',
            email: '',
        };
    };

    onMounted(() => {
        getUsers();
    });
</script>
