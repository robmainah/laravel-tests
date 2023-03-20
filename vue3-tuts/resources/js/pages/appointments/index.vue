<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointments</h1>
                </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard">Home</router-link>
                            </li>
                            <li class="breadcrumb-item active">Appointments</li>
                        </ol>
                    </div>
            </div>
        </div>
    </div>

    <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <router-link to="/appointments/create">
                                    <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i>
                                        Add New Appointment</button>
                                </router-link>
                            </div>
                            <div class="btn-group">
                                <button
                                    :class="[typeof selectedStatus === 'undefined' ? 'btn-secondary' : 'btn-default']"
                                    @click="getAppointments()"
                                    type="button" class="btn btn-secondary">
                                    <span class="mr-1">All</span>
                                    <span class="badge badge-pill badge-info">{{ appointmentsCount }}</span>
                                </button>

                                <button v-for="(status, index) in appointmentStatus" :key="index"
                                    @click="getAppointments(status.value)"
                                    :class="[selectedStatus === status.value ? 'btn-secondary' : 'btn-default']"
                                    type="button" class="btn">
                                    <span class="mr-1">{{ status.name }}</span>
                                    <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Client Name</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in appointments.data" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ item.client }}</td>
                                            <td>{{ item.start_time }}</td>
                                            <td>{{ item.end_time }}</td>
                                            <td>
                                                <span class="badge" :class="`badge-${item.status.color}`">{{ item.status.name }}</span>
                                            </td>
                                            <td>
                                                <a href="">
                                                    <i class="fa fa-edit mr-2"></i>
                                                </a>

                                                <a href="">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script setup>
    import { ref, onMounted, computed } from "vue";
    import { useToastr } from '../../toastr.js';

    const toast = useToastr();
    const appointments = ref({data: []});
    const selectedStatus = ref(null)
    // const appointmentsStatus = { scheduled: 1, confirmed: 2, cancelled: 3 };

    const appointmentStatus = ref([])
    const getAppointmentStatus = () => {
        axios.get('/api/appointment-status')
        .then(response => {
            appointmentStatus.value = response.data
        })
        .catch(error => {
            toast.error(error.response.data.message)
        })
    }

    const getAppointments = (status) => {
        selectedStatus.value = status;
        const params = {};

        if (status) {
            params.status = status;
        }

        axios.get('/api/appointments', {
            params: params,
        })
        .then(response => {
            appointments.value = response.data
        })
        .catch(error => {
            toast.error(error.response.data.message)
        })
    }

    const appointmentsCount = computed(() => {
        return appointmentStatus.value
            .map(status => status.count)
            .reduce((acc, value) => acc + value, 0);
    })

    onMounted(() => {
        getAppointments();
        getAppointmentStatus();
    });
</script>
