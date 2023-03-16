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
                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" v-model="form.name" class="form-control " id="name"
                                aria-describedby="nameHelp" placeholder="Enter full name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" v-model="form.email" class="form-control " id="email"
                                aria-describedby="nameHelp" placeholder="Enter full name">
                        </div>
                    </form>

                    <div class="form-group">
                        <label for="email">Password</label>
                        <input type="password" v-model="form.password" class="form-control " id="password"
                            aria-describedby="nameHelp" placeholder="Enter password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="createUser">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, reactive } from 'vue';

    const users = ref([]);
    const closeUserModal = ref(null);

    const form = reactive({
        name: '',
        email: '',
        password: '',
    });

    const getUsers = () => {
        axios.get('/api/users').then(data => {
            users.value = data.data
        });
    };

    const createUser = () => {
        axios.post('/api/users', form).then(response => {
            users.value.unshift(response.data);

            form.name = '';
            form.email = '';
            form.password = '';

            closeUserModal.value.click()
        });
    }

    onMounted(() => {
        getUsers()
    });
</script>
