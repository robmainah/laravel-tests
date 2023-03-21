<template>
    <div class="container">
        <h1 v-if="editMode">Edit Post</h1>
        <h1 v-else>New Post</h1>

        <hr />

        <form @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="post_title">Title</label>
                <input type="text" class="form-control" id="post_title" v-model="form.title" placeholder="Title">
            </div>

            <div class="form-group">
                <label for="post_content">Post Content</label>
                <textarea class="form-control" rows="8" id="post_content" v-model="form.content" placeholder="Write something amazing..."></textarea>
            </div>

            <div class="form-group">
                <label><input type="checkbox" v-model="form.published" style="margin-right: 15px;">Published</label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Save Post</button>
        </form>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import { useRoute, useRouter } from 'vue-router'

    const route = useRoute();
    const router = useRouter();
    const editMode = ref(false);
    const form = ref({
        title: '',
        content: '',
        published: '',
    })

    const getPost = () => {
        axios.get(`/api/posts/${route.params.id}`)
        .then(response => {
            form.value = response.data
        })
    }

    const handleSubmit = () => {
        if (editMode.value) {
            return updatePost();
        }

        createPost();
    }

    const createPost = () => {
        axios.post(`/api/posts`, form.value)
        .then(response => {
            router.push('/posts');
        })
    }

    const updatePost = () => {
        axios.put(`/api/posts/${route.params.id}`, form.value)
        .then(response => {
            router.push('/posts');
        })
    }
    
    onMounted(() => {
        if (route.name === 'PostEdit') {
            editMode.value = true;
            getPost();
        }
    });
</script>
