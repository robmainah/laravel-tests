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
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#createUserModal">
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="index">
                                <th>{{ ++index }}</th>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                            </tr>
                        </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New User</h5>
                    <button type="button" ref="closeUserModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <Form @submit="createUser" :validation-schema="schema" v-slot="{ errors }" autocomplete="off">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, reactive } from 'vue';
    import { Form, Field } from 'vee-validate';
    import * as yup from 'yup';

    const users = ref([]);
    const closeUserModal = ref(null);

    // const form = reactive({
    //     name: '',
    //     email: '',
    //     password: '',
    // });

    const getUsers = () => {
        axios.get('/api/users').then(data => {
            users.value = data.data
        });
    };

    const schema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().required().min(4),
    })

    const createUser = (values, { resetForm }) => {
        axios.post('/api/users', values).then(response => {
            users.value.unshift(response.data);
            closeUserModal.value.click();
            resetForm();
        });
    }

    onMounted(() => {
        getUsers()
    });
</script>
