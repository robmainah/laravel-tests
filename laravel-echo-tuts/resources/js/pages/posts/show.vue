<template>
    <div class="container">
        <h1>{{ post.title }}</h1>

        {{ post.updated_at }}

        <span v-if="post.published" class="label label-success" style="margin-left:15px;">Published</span>
        <span v-else class="label label-default" style="margin-left:15px;">Draft</span>

        <hr />

        <p class="lead">
            {{ post.content }}
        </p>

        <hr />

        <h3>Comments:</h3>

        <div style="margin-bottom:50px;">
            <textarea class="form-control" rows="3" v-model="commentBox" placeholder="Leave a comment"></textarea>
            <button @click="saveComment" class="btn btn-success" style="margin-top:10px">Save Comment</button>
        </div>

        <div v-for="(comment, index) in comments" :key="index"
            class="media" style="margin-top:20px;">
            <div class="media-left">
                <a href="#">
                    <img class="media-object mr-1" src="http://placeimg.com/80/80" alt="...">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading mb-0">{{ comment.user.name }}</h4>
                <span style="color: #aaa;">{{ comment.formatted_created_at }}</span>
                <p>{{ comment.body }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import { useRoute } from 'vue-router'

    const route = useRoute();
    const post = ref({});
    const comments = ref([]);
    const user = ref(null);
    const commentBox = ref(null);

    const getPost = () => {
        axios.get(`/api/posts/${route.params.id}`, {
            headers: {
                Authorization: 'Bearer s3MkOOg9btnpI4xBvN2Yg9rk4HN4QVFg42FAnwiZ'
            }
        })
        .then(response => {
            post.value = response.data
        })
    }

    const getComments = () => {
        axios.get(`/api/posts/${route.params.id}/comments`)
        .then(({data}) => {
            comments.value = data.data
        })
    }

    const saveComment = () => {
        axios.post(`/api/posts/${route.params.id}/comments`, { body: commentBox.value })
        .then((response) => {
            comments.value.unshift(response.data)
            commentBox.value = '';
        })
    }

    const newCommentListener = () => {
        Echo.channel(`posts.${route.params.id}`)
            .listen('.new-comment', (comment) => {
                comments.value.unshift(comment)
            })
    }
    
    onMounted(() => {
        getPost();
        getComments();
        newCommentListener();
    });
</script>
