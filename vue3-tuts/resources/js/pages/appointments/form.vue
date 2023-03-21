<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <template v-if="!editMode">Create Appointment</template>
                        <template v-else>Edit Appointment</template>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/appointments">Appointments</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <template v-if="!editMode">Create</template>
                            <template v-else>Edit</template>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot:default="{ errors }">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" v-model="form.title" 
                                                class="form-control" id="title"
                                                :class="{'is-invalid': errors.title}"
                                                placeholder="Enter Title">
                                            <span class="invalid-feedback">{{ errors.title }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Client Name</label>
                                            <select id="client" class="form-control" v-model="form.client_id" :class="{ 'is-invalid': errors.client_id }">
                                                <option value="" disabled>Select Client</option>
                                                <option :value="client.id" v-for="(client, index) in clients" :key="index">{{ client.first_name }} {{ client.last_name }}</option>
                                            </select>
                                            <span class="invalid-feedback">{{ errors.client_id }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_time">Start Time</label>
                                            <input type="text" v-model="form.start_time" class="form-control flatpickr"
                                                :class="{ 'is-invalid': errors.start_time }" id="start_time">
                                            <span class="invalid-feedback">{{ errors.start_time }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_time">End Time</label>
                                            <input type="text" v-model="form.end_time" class="form-control flatpickr"
                                                :class="{ 'is-invalid': errors.end_time }" id="end_time">
                                            <span class="invalid-feedback">{{ errors.end_time }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea v-model="form.description" class="form-control"
                                        :class="{'is-invalid': errors.description}"
                                        id="description" rows="3"
                                        placeholder="Enter Description"></textarea>
                                    <span class="invalid-feedback">{{ errors.description }}</span>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, onMounted, ref } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import { useToastr } from '@/toastr'
    import { Form, Field } from 'vee-validate';
    import flatpickr from 'flatpickr';
    import 'flatpickr/dist/themes/light.css';

    const router = useRouter();
    const route = useRoute();
    const toastr = useToastr();

    const form = ref({
        title: '',
        client_id: '',
        start_time: '',
        end_time: '',
        description: '',
    })

    const handleSubmit = (values, actions) => {
        if (editMode) {
            return editAppointments(values, actions);
        }

        createAppointments(values, actions);
    }

    const createAppointments = (values, actions) => {
        axios.post('/api/appointments', form)
        .then(response => {
            router.push('/appointments')
            toastr.success('Appointment created successfully')
        })
        .catch(error => {
            actions.setErrors(error.response.data.errors)
            toastr.error(error.response.data.message)
        })
    }

    const editAppointments = (values, actions) => {
        console.log(form.value);
        axios.put(`/api/appointments/${route.params.id}`, form.value)
        .then(response => {
            router.push('/appointments')
            toastr.success('Appointment updated successfully')
        })
        .catch(error => {
            actions.setErrors(error.response.data.errors)
            toastr.error(error.response.data.message)
        })
    }

    const clients = ref()
    const getClients = () => {
        axios.get('/api/clients')
        .then(response => {
            clients.value = response.data
        })
        .catch(error => {
            toastr.error(error.response.data.message)
        })
    }

    const getAppointment = () => {
        axios.get(`/api/appointments/${route.params.id}/edit`)
        .then(({data}) => {
            form.value = data.data
        })
        .catch(error => {
            console.log(error);
            toastr.error(error.response.data.message)
        })
    }

    const editMode = ref(false)

    onMounted(() => {
        if (route.name === 'editAppointment') {
            editMode.value = true;
            getAppointment();
        }

        getClients();
        flatpickr(".flatpickr", {
            enableTime: true,
            dateFormat: 'Y-m-d h:i K',
            defaultHour: 10,
        });
    })
</script>
