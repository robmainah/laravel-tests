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

        <div class="d-flex align-items-center">
            <h3 class="me-3">Comments:</h3>
            <span v-if="personTyping">{{ personTyping.name }} is typing...</span>
        </div>

        <div style="margin-bottom:50px;">
            <textarea class="form-control" rows="3" @keydown="showTyping" @keyup="hideTyping" v-model="commentBox" placeholder="Leave a comment"></textarea>
            <button @click="saveComment" class="btn btn-success" style="margin-top:10px">Save Comment</button>
        </div>

        <div v-for="(comment, index) in comments" :key="index"
            class="d-flex" style="margin-top:20px;">
            <div class="me-2">
                <a href="#">
                    <img class="rounded-circle" src="http://placeimg.com/80/80" alt="...">
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
    import { debounce } from 'lodash'

    const route = useRoute();
    const post = ref({});
    const comments = ref([]);
    const user = ref(null);
    const commentBox = ref(null);
    const personTyping = ref(null);

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

    const getUser = () => {
        axios.get(`/api/user`)
        .then(({data}) => {
            user.value = data
        })
    }

    const saveComment = () => {
        axios.post(`/api/posts/${route.params.id}/comments`, { body: commentBox.value })
        .then((response) => {
            comments.value.unshift(response.data)
            commentBox.value = '';
        })
    }

    const channel = Echo.join(`posts.${route.params.id}`);

    const showTyping = (event) => {
        channel.whisper('startedTyping', {
            person_typing: user.value
        })
    }

    const hideTyping = (event) => {
        channel.whisper('stoppedTyping')
    }

    const newCommentListener = () => {
        channel.here((users) => {
            console.log(users);
        })
        .joining((user) => {
            console.log(user.name + " joining");
        })
        .leaving((user) => {
            console.log(user.name + " leaving");
        })
        .listen('.new-comment', (comment) => {
            comments.value.unshift(comment)
        })
        .listenForWhisper('startedTyping', (event) => {
            personTyping.value = event.person_typing;
        })
        .listenForWhisper('stoppedTyping', debounce((event) => {
            console.log("stopping");
            personTyping.value = null;
        }, 700))
    }
    
    onMounted(() => {
        getPost();
        getComments();
        getUser();
        newCommentListener();
    });
</script>
