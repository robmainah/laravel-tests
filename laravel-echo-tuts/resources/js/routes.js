import PostsAll from '@/pages/posts/index.vue'
import PostForm from '@/pages/posts/form.vue'
import PostShow from '@/pages/posts/show.vue'

export default [
    {
        path: '/posts',
        name: 'AllPosts',
        component: PostsAll,
    },
    {
        path: '/posts/create',
        name: 'PostForm',
        component: PostForm,
    },
    {
        path: '/posts/:id',
        name: 'PostShow',
        component: PostShow,
    },
    {
        path: '/posts/:id/edit',
        name: 'PostEdit',
        component: PostForm,
    },
]
