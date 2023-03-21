<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>All Posts</h1>
            </div>

            <div class="col-md-4">
                <router-link to="/posts/create" class="btn btn-primary pull-right" style="margin-top:15px;">Create New
                    Post</router-link>
            </div>
        </div>
        <hr />
        <table class="table">
            <thead> 
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(post, index) in posts" :key="index">
                    <th>{{ ++index }}</th>
                    <td>{{ post.title }}</td>
                    <td>{{ post.published ? "Published" : "Draft" }}</td>
                    <td>
                        <router-link :to="{name: 'PostShow', params: { id: post.id }}" class="btn btn-sm btn-success me-1">View</router-link>
                        <router-link :to="{name: 'PostEdit', params: { id: post.id }}" class="btn btn-sm btn-info">Edit</router-link>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <!-- {{ $posts -> links() }} -->
        </div>

    </div>
</template>

<script setup>

    import { ref, onMounted } from 'vue'

    const posts = ref([])

    const getPosts = () => {
        axios.get('/api/posts')
            .then(({ data }) => {
                posts.value = data.data
            })
    }

    onMounted(() => {
        getPosts();
    });
</script>
